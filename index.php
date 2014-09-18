<?php



 /*** define the site path ***/
 $site_path = realpath(dirname(__FILE__));
 define ('__SITE_PATH', $site_path);

 /*** include the init.php file ***/
 include 'includes/init.php';

 /*** error reporting ***/
 if($registry->config['Site']['debug']){error_reporting(E_ALL);} else {error_reporting(0);}
 
 /*** load the router ***/
 $registry->router = new router($registry);

 /*** set the controller path ***/
 $registry->router->setPath (__SITE_PATH . '/controller');

 /*** load up the template ***/
 $registry->template = new rainTPL($registry);

 /*** load the controller ***/
 $registry->router->loader();
?>
