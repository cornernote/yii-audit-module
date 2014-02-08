# Yii Audit Module

Track and display usage information including page requests, database field changes, php errors and yii logs.


## Features


### Visitor Request Tracking

- Track site activity including everything you need to know about the request.
- The error handler will automatically create an AuditRequest record for each visitor hit.
- When the application ends it will update the AuditRequest with memory and time information.

Tracks the following information:

- Links - Requested URL, referring URL, redirecting to URL (read from the headers at the end of the application)
- User - Visitors IP Address and logged in user's ID
- Superglobals - (`$_GET`/`$_POST`/`$_SESSION`/`$_FILES`/`$_COOKIE`), the arrays are serialized then compressed using gzip
- Timers - Start and end times of the application
- Memory - Memory usage and peak memory usage


### Model Field Tracking

- Tracks the old and new values each time your model is saved.
- Behavior can easily be attached to any model you want to track field changes.
- Each field change is related to an AuditRequest so you can see the entire state of the visitors action.
- Performs multiple inserts in a single query with `CDbCommandBuilder::createMultipleInsertCommand()`.
- Provides views that can be rendered into your application to show changed fields for your model.


### Error Tracking

- Full error stack dump is saved, even in live mode.
- Catches all errors, including fatal errors.
- View all the collected data from the module interface.
- Each error is related to an AuditRequest so you can see the entire state of the visitors action.


### Log Tracking

- Save logs to your database for easy real-time debugging or for checking on historical logs.
- Each log is related to an AuditRequest so you can see the entire state of the visitors action.


## Resources

- **[Documentation](http://cornernote.github.io/yii-audit-module)**
- **[GitHub Project](https://github.com/cornernote/yii-audit-module)**
- **[Yii Extension](http://www.yiiframework.com/extension/yii-audit-module)**


## Support

- Does this README need improvement?  Go ahead and [suggest a change](https://github.com/cornernote/yii-audit-module/edit/master/README.md).
- Found a bug, or need help using this project?  Check the [open issues](https://github.com/cornernote/yii-audit-module/issues) or [create an issue](https://github.com/cornernote/yii-audit-module/issues/new).


## License

[BSD-3-Clause](https://raw.github.com/cornernote/yii-audit-module/master/LICENSE), Copyright © 2013-2014 [Mr PHP](mailto:info@mrphp.com.au)


[![Mr PHP](https://raw.github.com/cornernote/mrphp-assets/master/img/code-banner.png)](http://mrphp.com.au) [![Project Stats](https://www.ohloh.net/p/yii-audit-module/widgets/project_thin_badge.gif)](https://www.ohloh.net/p/yii-audit-module)

[![Latest Stable Version](https://poser.pugx.org/cornernote/yii-audit-module/v/stable.png)](https://github.com/cornernote/yii-audit-module/releases/latest) [![Total Downloads](https://poser.pugx.org/cornernote/yii-audit-module/downloads.png)](https://packagist.org/packages/cornernote/yii-audit-module) [![Monthly Downloads](https://poser.pugx.org/cornernote/yii-audit-module/d/monthly.png)](https://packagist.org/packages/cornernote/yii-audit-module) [![Latest Unstable Version](https://poser.pugx.org/cornernote/yii-audit-module/v/unstable.png)](https://github.com/cornernote/yii-audit-module) [![Build Status](https://travis-ci.org/cornernote/yii-audit-module.png?branch=master)](https://travis-ci.org/cornernote/yii-audit-module) [![License](https://poser.pugx.org/cornernote/yii-audit-module/license.png)](https://raw.github.com/cornernote/yii-audit-module/master/LICENSE)
