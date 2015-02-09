<?php

/**
 * AuditErrorHandler
 *
 * Catches all errors, including fatal errors, and stores them in runtime/errors.
 *
 * Tracks the user request data.
 *
 * @property $auditRequestId string|int
 *
 * Fatal error catching was inspired by FatalErrorCatch by Rustam Gumerov.
 * @author Rustam Gumerov <psrustik@yandex.ru>
 * @link https://github.com/psrustik/yii-fatal-error-catch
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Mr PHP
 * @link https://github.com/cornernote/yii-audit-module
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-audit-module/master/LICENSE
 *
 * @package yii-audit-module
 */
class AuditErrorHandler extends CErrorHandler
{

    /**
     * @var bool Set to false to only track error requests.  Defaults to true.
     */
    public $trackAllRequests = false;

    /**
     * @var bool Set to false to not handle fatal errors.  Defaults to true.
     */
    public $catchFatalErrors = true;

    /**
     * @var array Fatal error types that we want to catch
     */
    public $fatalErrorTypes = array(E_ERROR, E_PARSE, E_CORE_ERROR, E_CORE_WARNING, E_COMPILE_ERROR, E_COMPILE_WARNING);

    /**
     * @var array Request keys that we do not want to save in the tracking data.
     */
    public $auditRequestIgnoreKeys = array('PHP_AUTH_PW', 'password');

    /**
     * @var AuditRequest
     */
    private static $_auditRequest;

    /**
     * Init the error handler, register a shutdown function to catch fatal errors and track the request.
     * @return mixed
     */
    public function init()
    {
        // init the audit module
        Yii::app()->getModule('audit');

        // catch fatal errors
        if ($this->catchFatalErrors) {
            register_shutdown_function(array($this, 'handleFatalError'));
            //Yii::app()->onEndRequest[] = array($this, 'handleFatalError');
            if (substr(php_sapi_name(), 0, 3) != 'cli') {
                ob_start(array($this, 'handleFatalBuffer'));
            }
        }

        // track the request
        if ($this->trackAllRequests)
            $this->getAuditRequest();

        // call parent
        parent::init();
    }

    /**
     * Fatal error handler
     */
    public function handleFatalError()
    {
        $e = error_get_last();
        if ($e !== null && in_array($e['type'], $this->fatalErrorTypes)) {
            $event = new CErrorEvent($this, 500, 'Fatal error: ' . $e['message'], $e['file'], $e['line']);
            $this->handle($event);
            //Yii::app()->end(1); // end with abnormal ending
        }
    }

    /**
     * Clears the output buffer that was set in init if there was a fatal error.
     * @param $buffer
     * @return string
     */
    public function handleFatalBuffer($buffer)
    {
        $e = error_get_last();
        return ($e !== null && in_array($e['type'], $this->fatalErrorTypes)) ? '' : $buffer;
    }

    /**
     * Log the pretty html stack dump before the parent handles the error.
     * @param CErrorEvent|CExceptionEvent $event
     */
    public function handle($event)
    {
        if ($event instanceof CExceptionEvent)
            $this->logExceptionEvent($event);
        else
            $this->logErrorEvent($event);
        parent::handle($event);
    }

    /**
     * Generate an error stack dump.
     * @param $event CErrorEvent
     * @return string
     */
    public function logErrorEvent($event)
    {
        // create a new AuditError
        $auditError = new AuditError;
        $auditError->created = time();
        $auditError->status = 'new';
        $auditError->code = 500;
        $auditError->message = $event->message;
        $auditError->file = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $event->file);
        $auditError->line = $event->line;
        $auditError->source_code = AuditHelper::pack($this->renderSourceCode($auditError->file, $auditError->line, $this->maxSourceLines));

        // get the trace info and stack_dump
        $trace = debug_backtrace();
        // skip the first 3 stacks as they do not tell the error position
        if (count($trace) > 6)
            $trace = array_slice($trace, 6);
        $auditError->trace = '';
        foreach ($trace as $i => $t) {
            if (!isset($t['file']))
                $trace[$i]['file'] = 'unknown';
            $trace[$i]['file'] = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $trace[$i]['file']);

            if (!isset($t['line']))
                $trace[$i]['line'] = 0;

            if (!isset($t['function']))
                $trace[$i]['function'] = 'unknown';

            $auditError->trace .= "#$i {$trace[$i]['file']}({$trace[$i]['line']}): ";
            if (isset($t['object']) && is_object($t['object']))
                $auditError->trace .= get_class($t['object']) . '->';
            $auditError->trace .= "{$trace[$i]['function']}()\n";

            unset($trace[$i]['object']);

            if (isset($trace[$i]['args']))
                $trace[$i]['args'] = $this->cleanTrace($trace[$i]['args']);
        }
        $auditError->traces = json_encode($trace);
        $auditError->stack_trace = AuditHelper::pack($this->renderStackTrace($trace));

        // get the type info
        switch ($event->code) {
            case E_WARNING:
                $auditError->type = 'PHP warning';
                break;
            case E_NOTICE:
                $auditError->type = 'PHP notice';
                break;
            case E_USER_ERROR:
                $auditError->type = 'User error';
                break;
            case E_USER_WARNING:
                $auditError->type = 'User warning';
                break;
            case E_USER_NOTICE:
                $auditError->type = 'User notice';
                break;
            case E_RECOVERABLE_ERROR:
                $auditError->type = 'Recoverable error';
                break;
            default:
                $auditError->type = 'PHP error';
        }

        // get the AuditRequest
        $auditRequest = $this->getAuditRequest();
        $auditError->audit_request_id = $auditRequest ? $auditRequest->id : 0;

        // generate a hash of the error
        $auditError->hash = $this->hash($auditError->message . $auditError->file . $auditError->line);

        // save the AuditError
        $auditError->save(false);
    }

    /**
     * @param mixed $var
     * @return mixed
     */
    private function cleanTrace($var)
    {
        foreach ($var as $k => $v) {
            if (is_object($v)) {
                $var[$k] = get_class($v);
            }
            elseif (is_array($v)) {
                if ($k == 'GLOBALS' && isset($var['_REQUEST']) && isset($var['_GET']) && isset($var['_POST']) && isset($var['_COOKIE']) && isset($var['_FILES']) && isset($var['_SERVER'])) {
                    $var['GLOBALS'] = '$GLOBALS';
                    $var['_REQUEST'] = '$_REQUEST';
                    $var['_GET'] = '$_GET';
                    $var['_POST'] = '$_POST';
                    $var['_COOKIE'] = '$_COOKIE';
                    $var['_FILES'] = '$_FILES';
                    $var['_SERVER'] = '$_SERVER';
                }
                else {
                    $var[$k] = $this->cleanTrace($v);
                }
            }
        }
        return $var;
    }

    /**
     * Log an exception event.
     * @param $event CExceptionEvent
     */
    public function logExceptionEvent($event)
    {
        $this->logException($event->exception);
    }

    /**
     * Log an exception.
     * @param $exception Exception
     * @param null|string $extra
     * @return AuditError
     */
    public function logException($exception, $extra = null)
    {
        // create a new AuditError
        $auditError = new AuditError;
        $auditError->created = time();
        $auditError->status = 'new';
        $auditError->code = ($exception instanceof CHttpException) ? $exception->statusCode : 500;
        $auditError->error_code = $exception->getCode();
        $auditError->type = get_class($exception);
        $auditError->message = $exception->getMessage();
        $auditError->trace = $exception->getTraceAsString();
        if ($extra) {
            $auditError->extra = AuditHelper::pack($extra);
        }

        // get file and line
        $exactTrace = $this->getExactTrace($exception);
        if (!$exactTrace) {
            $auditError->file = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $exception->getFile());
            $auditError->line = $exception->getLine();
        }
        else {
            $auditError->file = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $exactTrace['file']);
            $auditError->line = $exactTrace['line'];
        }
        $auditError->source_code = AuditHelper::pack($this->renderSourceCode($auditError->file, $auditError->line, $this->maxSourceLines));

        // get traces
        $trace = $exception->getTrace();
        if ($trace) {
            foreach ($trace as $i => $t) {
                if (!isset($t['file']))
                    $trace[$i]['file'] = 'unknown';
                $trace[$i]['file'] = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $trace[$i]['file']);

                if (!isset($t['line']))
                    $trace[$i]['line'] = 0;

                if (!isset($t['function']))
                    $trace[$i]['function'] = 'unknown';

                if (isset($t['object']))
                    unset($trace[$i]['object']);
            }
        }
        $auditError->traces = json_encode($trace);
        $auditError->stack_trace = AuditHelper::pack($this->renderStackTrace($trace));

        // get the AuditRequest
        $auditRequest = $this->getAuditRequest();
        $auditError->audit_request_id = $auditRequest ? $auditRequest->id : 0;

        // generate a hash of the error
        $auditError->hash = $this->hash($auditError->message . $auditError->file . $auditError->line);

        // save the AuditError
        $auditError->save(false);
        return $auditError;
    }

    /**
     * @param array $traces
     * @return string
     */
    protected function renderStackTrace($traces = array())
    {
        $count = 0;
        if (!$traces)
            return '';
        $output = CHtml::openTag('table', array('width' => '100%'));
        foreach ($traces as $n => $trace) {
            if ($this->getIsCoreCode($trace))
                $cssClass = 'core collapsed';
            else if (++$count > 3)
                $cssClass = 'app collapsed';
            else
                $cssClass = 'app expanded';
            $hasCode = $trace['file'] !== 'unknown' && is_file($trace['file']);
            $output .= CHtml::openTag('tr', array('class' => 'trace ' . $cssClass));
            $output .= CHtml::tag('td', array('class' => 'number'), '#' . $n);
            $output .= CHtml::openTag('td', array('class' => 'content'));
            $output .= CHtml::openTag('div', array('class' => 'trace-file'));
            if ($hasCode) {
                $output .= CHtml::tag('div', array('class' => 'plus'), '+');
                $output .= CHtml::tag('div', array('class' => 'minus'), '-');
            }
            $output .= '&nbsp;' . htmlspecialchars($trace['file'], ENT_QUOTES, Yii::app()->charset) . "(" . $trace['line'] . "):";
            if (!empty($trace['class']))
                $output .= "<strong>{$trace['class']}</strong>{$trace['type']}";
            $output .= "<strong>{$trace['function']}</strong>(";
            if (!empty($trace['args']))
                $output .= htmlspecialchars($this->getArgumentsToString($trace['args']), ENT_QUOTES, Yii::app()->charset);
            $output .= ')';
            $output .= CHtml::closeTag('div');
            if ($hasCode) $output .= $this->getRenderSourceCode($trace['file'], $trace['line'], $this->maxTraceSourceLines);
            $output .= CHtml::closeTag('td');
            $output .= CHtml::closeTag('tr');
        }
        $output .= CHtml::closeTag('table');
        return $output;
    }

    /**
     * Checks if an AuditRequest has been set.
     * @return bool
     */
    public function hasAuditRequest()
    {
        return self::$_auditRequest ? true : false;
    }

    /**
     * Gets the AuditRequest, if one is not set then it records a new one.
     * @return AuditRequest
     */
    public function getAuditRequest()
    {
        // get existing Audit
        if (self::$_auditRequest)
            return self::$_auditRequest;

        return self::$_auditRequest = $this->recordAuditRequest();
    }

    /**
     * Generates and saves the AuditRequest data.
     */
    private function recordAuditRequest()
    {
        // create new Audit
        $auditRequest = new AuditRequest();

        // get info
        $auditRequest->created = time();
        $auditRequest->user_id = Yii::app()->hasComponent('user') ? Yii::app()->user->id : 0;
        $auditRequest->link = $this->getCurrentLink();
        $auditRequest->start_time = YII_BEGIN_TIME;

        $auditRequest->app = Yii::app()->id;
        $auditRequest->get = $_GET;
        $auditRequest->post = $_POST;
        $auditRequest->files = $_FILES;
        $auditRequest->session = $this->getShrinkedSession();
        $auditRequest->cookie = $_COOKIE;
        $auditRequest->server = $_SERVER;
        $auditRequest->config = $this->getYiiConfig();
        if (function_exists('getallheaders'))
            $auditRequest->request_headers = getallheaders();
        if (function_exists('headers_list'))
            $auditRequest->response_headers = headers_list();
        $auditRequest->php_input = file_get_contents('php://input');

        $auditRequest->ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null;
        $auditRequest->referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;

        // remove passwords
        $auditRequest->get = $this->removeValuesWithPasswordKeys($auditRequest->get, $passwordRemovedFromGet);
        $auditRequest->post = $this->removeValuesWithPasswordKeys($auditRequest->post, $passwordRemovedFromPost);
        $auditRequest->server = $this->removeValuesWithPasswordKeys($auditRequest->server);
        $auditRequest->config = $this->removeValuesWithPasswordKeys($auditRequest->config);
        if ($auditRequest->request_headers)
            $auditRequest->request_headers = $this->removeValuesWithPasswordKeys($auditRequest->request_headers);
        if ($auditRequest->response_headers)
            $auditRequest->response_headers = $this->removeValuesWithPasswordKeys($auditRequest->response_headers);
        if (($passwordRemovedFromGet || $passwordRemovedFromPost) && $auditRequest->server)
            $auditRequest->server = '*removed*';
        if ($passwordRemovedFromGet && $auditRequest->link)
            $auditRequest->link = '*removed*';
        if ($passwordRemovedFromPost && $auditRequest->php_input)
            $auditRequest->php_input = '*removed*';

        // set the closing data incase we are already in an endRequest
        if ($auditRequest->response_headers) {
            foreach ($auditRequest->response_headers as $header) {
                if (strpos(strtolower($header), 'location:') === 0) {
                    $auditRequest->redirect = trim(substr($header, 9));
                }
            }
        }

        // pack all
        $auditRequest->get = AuditHelper::pack($auditRequest->get);
        $auditRequest->post = AuditHelper::pack($auditRequest->post);
        $auditRequest->files = AuditHelper::pack($auditRequest->files);
        $auditRequest->session = AuditHelper::pack($auditRequest->session);
        $auditRequest->cookie = AuditHelper::pack($auditRequest->cookie);
        $auditRequest->server = AuditHelper::pack($auditRequest->server);
        $auditRequest->config = AuditHelper::pack($auditRequest->config);
        $auditRequest->request_headers = AuditHelper::pack($auditRequest->request_headers);
        $auditRequest->response_headers = AuditHelper::pack($auditRequest->response_headers);
        $auditRequest->php_input = AuditHelper::pack($auditRequest->php_input);

        // save
        $auditRequest->save(false);

        // add an event callback to update the audit at the end
        Yii::app()->onEndRequest = array($this, 'endAuditRequest');

        return $auditRequest;
    }

    /**
     * Callback to update the AuditRequest at the end of the Yii request.
     * @see getAuditRequest()
     */
    public function endAuditRequest()
    {
        $auditRequest = $this->getAuditRequest();
        if (function_exists('headers_list'))
            $auditRequest->response_headers = headers_list();
        if ($auditRequest->response_headers) {
            foreach ($auditRequest->response_headers as $header) {
                if (strpos(strtolower($header), 'location:') === 0) {
                    $auditRequest->redirect = trim(substr($header, 9));
                }
            }
        }
        $auditRequest->response_headers = $this->removeValuesWithPasswordKeys($auditRequest->response_headers);
        $auditRequest->response_headers = AuditHelper::pack($auditRequest->response_headers);

        $auditRequest->memory_usage = memory_get_usage();
        $auditRequest->memory_peak = memory_get_peak_usage();
        $auditRequest->audit_field_count = $auditRequest->auditFieldCount;
        $auditRequest->end_time = microtime(true);
        $auditRequest->total_time = $auditRequest->end_time - $auditRequest->start_time;
        $auditRequest->save(false);
    }

    /**
     * Gets a link to the current page or yiic script that is being run.
     * @return string
     */
    private function getCurrentLink()
    {
        if (Yii::app() instanceof CWebApplication) {
            return Yii::app()->getRequest()->getHostInfo() . Yii::app()->getRequest()->getUrl();
        }
        $link = 'yiic ';
        if (isset($_SERVER['argv'])) {
            $argv = $_SERVER['argv'];
            array_shift($argv);
            $link .= implode(' ', $argv);
        }
        return trim($link);
    }


    /**
     * Removes passwords from the given array.
     * @param $array
     * @param bool $passwordRemoved gets set to true if passwords were removed.
     * @return array
     */
    private function removeValuesWithPasswordKeys($array, &$passwordRemoved = false)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $value = $this->removeValuesWithPasswordKeys($value, $removedChild);
                if ($removedChild) {
                    $array[$key] = $value;
                    $passwordRemoved = true;
                }
            }
            else {
                foreach ($this->auditRequestIgnoreKeys as $ignoreKey) {
                    if (stripos($key, $ignoreKey) !== false) {
                        $array[$key] = '*password removed*';
                        $passwordRemoved = true;
                        continue;
                    }
                }
            }
        }
        return $array;
    }


    /**
     * Shrinks the session of huge datafields to prevent too much data being stored.
     * @return mixed
     */
    private function getShrinkedSession()
    {
        $serialized = '';
        if (isset($_SESSION)) {
            $serialized = serialize($_SESSION);
        }
        if (strlen($serialized) > 64000) {
            $sessionCopy = $_SESSION;
            $ignoredKeys = array();
            foreach ($_SESSION as $key => $value) {
                $size = strlen(serialize($value));
                if ($size > 1000) {
                    unset($sessionCopy[$key]);
                    $ignoredKeys[$key] = $key;
                }
            }
            $sessionCopy['__ignored_keys_in_audit'] = $ignoredKeys;
            $serialized = serialize($sessionCopy);
        }
        return unserialize($serialized);
    }

    /**
     * @return array
     */
    private function getYiiConfig()
    {
        return array_merge($this->prepareConfigData(get_object_vars(Yii::app())), array(
            'params' => $this->prepareConfigData(Yii::app()->params),
            'modules' => $this->prepareConfigData(Yii::app()->modules),
            'components' => $this->prepareConfigData(Yii::app()->components),
        ));
    }

    /**
     * @param $data
     * @return array
     */
    private function prepareConfigData($data)
    {
        $result = array();
        foreach ($data as $key => $value) {
            if (is_object($value)) {
                $value = array_merge(array(
                    'class' => get_class($value)
                ), get_object_vars($value));
            }
            $result[$key] = $value;
        }
        return $result;
    }

    /**
     * Grant public access to protected method for error view rendering.
     *
     * @param $file
     * @param $errorLine
     * @param $maxLines
     * @return string
     */
    public function getRenderSourceCode($file, $errorLine, $maxLines)
    {
        return parent::renderSourceCode($file, $errorLine, $maxLines);
    }

    /**
     * Grant public access to protected method for error view rendering.
     *
     * @param $trace
     * @return bool
     */
    public function getIsCoreCode($trace)
    {
        return parent::isCoreCode($trace);
    }

    /**
     * Grant public access to protected method for error view rendering.
     *
     * @param $args
     * @return string
     */
    public function getArgumentsToString($args)
    {
        return parent::argumentsToString($args);
    }

    /**
     * Hash a long string to a short string.
     * @link http://au1.php.net/crc32#111931
     *
     * @param $data
     * @return string
     */
    function hash($data)
    {
        static $map = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $hash = bcadd(sprintf('%u', crc32($data)), 0x100000000);
        $str = '';
        do {
            $str = $map[bcmod($hash, 62)] . $str;
            $hash = bcdiv($hash, 62);
        } while ($hash >= 1);
        return $str;
    }

}
