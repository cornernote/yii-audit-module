/**
 * Error JS
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright 2008-2013 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Mr PHP
 * @link https://github.com/cornernote/yii-audit-module
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-audit-module/master/LICENSE
 *
 * @package yii-audit-module
 */

$(document).ready(function () {
    var traceReg = new RegExp("(^|\\s)trace-file(\\s|$)");
    var collapsedReg = new RegExp("(^|\\s)collapsed(\\s|$)");
    var e = document.getElementsByTagName("div");
    for (var j = 0, len = e.length; j < len; j++) {
        if (traceReg.test(e[j].className)) {
            e[j].onclick = function () {
                var trace = this.parentNode.parentNode;
                if (collapsedReg.test(trace.className))
                    trace.className = trace.className.replace("collapsed", "expanded");
                else
                    trace.className = trace.className.replace("expanded", "collapsed");
            }
        }
    }
});