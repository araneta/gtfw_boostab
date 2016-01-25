<?php
/**
 * @copyright Copyright (c) 2014, PT Gamatechno Indonesia
 * @license http://gtfw.gamatechno.com/#license
 **/

error_reporting(E_ALL);
//=============ApplicationId========================
$application['application_id'] = 200; //isikan dengan id aplikasi
//=============directory============================

// do not edit this config
$application['gtfw_base'] = GTFW_BASE_DIR;
$application['docroot'] = GTFW_APP_DIR;

$application['basedir'] = str_replace(basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['SCRIPT_NAME']); // with trailling slash
$application['baseaddress'] = "http" . ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "s" : "") . "://" . $_SERVER['HTTP_HOST']; // without trailling slash
$application['domain'] = ''; // name of domain

//============database============================
// connection number 0, digunakan base utk mengakses user
$application['db_conn'][0]['db_driv'] = 'adodb';
$application['db_conn'][0]['db_type'] = 'mysqlt';
$application['db_conn'][0]['db_host'] = 'localhost';
$application['db_conn'][0]['db_user'] = 'root';
$application['db_conn'][0]['db_pass'] = '12';
$application['db_conn'][0]['db_name'] = 'gtfw';
$application['db_conn'][0]['db_result_cache_lifetime'] = '';
$application['db_conn'][0]['db_result_cache_path'] = '';
$application['db_conn'][0]['db_debug_enabled'] = false;
$application['db_conn'][0]['db_port'] = '3306';
//============session============================
$application['use_session'] = true;
$application['session_save_handler'] = 'default'; // default|database 'default' is php internal save handler
$application['session_multiuser_enabled'] = false;
$application['session_name'] = 'GTFWSessID';
$application['session_save_path'] = null; //'/tmp/'; ///TODO: should not be here!!!, and pelase, support NULL value to fallback to PHP INI's session save path
$application['session_expire'] = 180; // in minutes
$application['session_cookie_params']['lifetime'] = 60 * $application['session_expire']; // in seconds
$application['session_cookie_params']['path'] = $application['basedir'];
$application['session_cookie_params']['domain'] = $application['domain'];
$application['session_cookie_params']['secure'] = false; // needs secure connection?

$application['session_sso_enabled'] = false;
$application['session_sso_name'] = 'GTFWSSOSessID';
$application['session_sso_session_save_path'] = '/path/to/sso/session/dir/'; // with trailing slash

//============default page============================
$application['default_module'] = 'login_default';
$application['default_submodule'] = 'login';
$application['default_action'] = 'view';
$application['default_type'] = 'html';

//============security===========================
$application['enable_security'] = true;
$application['enable_request_id'] = false;
$application['default_user'] = 'nobody';
$application['enable_url_obfuscator'] = false;
$application['url_obfuscator_exception'] = array('soap'); // list of exeption request/response type
$application['url_type'] = 'Long'; // type: Long or Short
$application['login_method'] = 'default';
$application['authentication_method'] = 'default';
$application['authorization_method'] = 'default';

$application['xss_clean'] = false;
$application['strip_html_tags'] = false;

//============application============================
$application['use_persistent_process'] = false;

//============development============================
$application['debug_mode'] = true;

//=========== Single Sign On ========================
$application['system_id'] = 'com.gamatechno.gtfw';
$application['sso_group'] = 'com_gamatechno_academica'; //FIXME: what if this system is associated with more than one sso group

//=========== Single Sign On Server ========================
$application['sso_ldap_connection'] = 3; // connection number available for ldap access, see db_conn above

//============== syslog =============================
$application['syslog_category'] = array(); // what category permitted to be printed out, array() equals all category
$application['syslog_enabled'] = false;
$application['syslog_io_engine'] = 'std'; // tcp, file, std
$application['syslog_log_path'] = '/tmp/';
$application['syslog_tcp_host'] = 'localhost';
$application['syslog_tcp_port'] = 9777;

//================ soapgateway ========================
$application['wsdl_use_cache'] = false; // use cached wsdl if available
$application['wsdl_cache_path'] = '/tmp/'; // use cached wsdl if available
$application['wsdl_cache_lifetime'] = 60 * 60 * 24/* one day!*/; // invalidate wsdl cache every x seconds

//================ additional config =====================
$application['menu_version'] = '2';
$application['paging_limit'] = 50;
$application['paging_limit_popup'] = 20;
$application['application_logo'] = 'logo.png';
$application['application_icon'] = 'favicon.ico';
