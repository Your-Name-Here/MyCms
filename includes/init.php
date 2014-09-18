<?php
 /*** include the controller class ***/
 include __SITE_PATH . '/application/' . 'controller_base.class.php';

 /*** include the registry class ***/
 include __SITE_PATH . '/application/' . 'registry.class.php';

 /*** include the router class ***/
 include __SITE_PATH . '/application/' . 'router.class.php';

 /*** include the template class ***/
 //include __SITE_PATH . '/application/' . 'template.class.php'; // Original TPL Class
 include __SITE_PATH . '/application/' . 'rainTPL.class.php';
raintpl::$tpl_dir = __SITE_PATH . '/views/';
raintpl::$cache_dir = __SITE_PATH . '/cache/';
raintpl::configure( 'base_url', __SITE_PATH );
$config = parse_ini_file("application/config.ini", 1);
 /*** auto load model classes ***/
    function __autoload($class_name) {
    $filename = strtolower($class_name) . '.class.php';
    $file = __SITE_PATH . '/model/' . $filename;

    if (file_exists($file) == false)
    {
        return false;
    }
  include ($file);
}

 /*** a new registry object ***/
 $registry = new registry;
$registry->DB_Conn = $config['db_conn'];
$registry->config = $config;
 /*** create the database registry object ***/
$registry->database = new db($registry->DB_Conn);
$registry->plugin = new plugin();
?>
