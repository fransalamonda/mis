<?php
/*
 * Plant ID
 */
define('PLANT_ID', "R01JAT-801");

/*
 * FTP properties
 */
define('FTP_USER', "YWRtaW4ucGlr");
define('FTP_PASS', "aGlqa2xtbjEyMw==");
define('FTP_SERVER', "10.10.10.22");

/*
 * DB Properties
 */
define('DB_USER', "root");
define('DB_PASS', "");
define('DB_SERVER', "localhost");
define('DB_NAME', "bash");
/*
 * DB SERVER
 */
define('DB_USER_SVR', "root");
define('DB_PASS_SVR', "");
define('DB_SERVER_SVR', "localhost");
define('DB_NAME_SVR', "bpcenter");
/*
 * ajax checking
 */
$headers = apache_request_headers();
define('IS_AJAX', (isset($headers['X-Requested-With']) && $headers['X-Requested-With'] == 'XMLHttpRequest'));

/*
 * temp dir
 */
define('TEMP_DIR', "./temp/");

/*
 * initial password
 */
define('INITIAL_PASSWORD', "827ccb0eea8a706c4c34a16891f84e7b");

/*
 * SETUP DATE
 */
define('SETUP_DATE_AWAL', "2014-02-10");
define('SETUP_DATE_AKHIR', "2015-03-31");

/*
 * SO Volume Tolerance Percentage
 */
//define('SO_PERCENTAGE', 0.15);
define('SO_PERCENTAGE', 0);


/*
 * DIR PATH
 */
define('SCHEMA',            ( @$_SERVER["HTTPS"] == "on" ) ? "https://" : "http://");
define('BASE_URL',          SCHEMA . $_SERVER["SERVER_NAME"] . '/mis/');