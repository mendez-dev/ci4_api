<?php

/*
 | --------------------------------------------------------------------
 | App Namespace
 | --------------------------------------------------------------------
 |
 | This defines the default Namespace that is used throughout
 | CodeIgniter to refer to the Application directory. Change
 | this constant to change the namespace that all application
 | classes should use.
 |
 | NOTE: changing this will require manually modifying the
 | existing namespaces of App\* namespaced-classes.
 */
defined('APP_NAMESPACE') || define('APP_NAMESPACE', 'App');

/*
 | --------------------------------------------------------------------------
 | Composer Path
 | --------------------------------------------------------------------------
 |
 | The path that Composer's autoload file is expected to live. By default,
 | the vendor folder is in the Root directory, but you can customize that here.
 */
defined('COMPOSER_PATH') || define('COMPOSER_PATH', ROOTPATH . 'vendor/autoload.php');

/*
 |--------------------------------------------------------------------------
 | Timing Constants
 |--------------------------------------------------------------------------
 |
 | Provide simple ways to work with the myriad of PHP functions that
 | require information to be in seconds.
 */
defined('SECOND') || define('SECOND', 1);
defined('MINUTE') || define('MINUTE', 60);
defined('HOUR')   || define('HOUR', 3600);
defined('DAY')    || define('DAY', 86400);
defined('WEEK')   || define('WEEK', 604800);
defined('MONTH')  || define('MONTH', 2592000);
defined('YEAR')   || define('YEAR', 31536000);
defined('DECADE') || define('DECADE', 315360000);

/*
 | --------------------------------------------------------------------------
 | Exit Status Codes
 | --------------------------------------------------------------------------
 |
 | Used to indicate the conditions under which the script is exit()ing.
 | While there is no universal standard for error codes, there are some
 | broad conventions.  Three such conventions are mentioned below, for
 | those who wish to make use of them.  The CodeIgniter defaults were
 | chosen for the least overlap with these conventions, while still
 | leaving room for others to be defined in future versions and user
 | applications.
 |
 | The three main conventions used for determining exit status codes
 | are as follows:
 |
 |    Standard C/C++ Library (stdlibc):
 |       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
 |       (This link also contains other GNU-specific conventions)
 |    BSD sysexits.h:
 |       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
 |    Bash scripting:
 |       http://tldp.org/LDP/abs/html/exitcodes.html
 |
 */
defined('EXIT_SUCCESS')        || define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          || define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         || define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   || define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  || define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') || define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     || define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       || define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      || define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      || define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

// SALT -------------------------------------------------------------------
defined('SALT')                || define('SALT', '.:6S@tz9M/PM%-*GebtM/PDM.bCfmg[D');
defined('HASH_ITERATIONS')     || define('HASH_ITERATIONS', 20);

/*
 | --------------------------------------------------------------------------
 | Número de registros por página
 | --------------------------------------------------------------------------
 |
 | El numero de registros que retornara por defecto al solicitar datos paginados
 */
defined('RECORDS_PER_PAGE') || define('RECORDS_PER_PAGE', 10);

/*
 | --------------------------------------------------------------------------
 | Nombres de tablas de la base de datos
 | --------------------------------------------------------------------------
 |
 | Almacena los nombres de las tablas en constantes, asi en caso de necesitar cambiar el nombre de
 | una tabla se cambiara en todos los lugares donde se haga referencia a ella
 */

defined('TBL_USER') || define('TBL_USER', 'user');
defined('TBL_GROUP') || define('TBL_GROUP', 'user_group');
defined('TBL_ROUTE') || define('TBL_ROUTE', 'route');
defined('TBL_ROUTE_PERMISSION') || define('TBL_ROUTE_PERMISSION', 'route_permission');
defined('TBL_PERMISSION') || define('TBL_PERMISSION', 'permission');
defined('TBL_USER_GROUP_PERMISSION') || define('TBL_USER_GROUP_PERMISSION', 'user_group_permission');
defined('TBL_APP_SETTINGS') || define('TBL_APP_SETTINGS', 'app_settings');
defined('TBL_CATEGORIE') || define('TBL_CATEGORIE', 'categorie');
