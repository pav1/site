<?php
// this is a 2k micro mvc 'thing'.., what you looking for in inside ./application directory
// based on http://www.henriquebarroso.com/how-to-create-a-simple-mvc-framework-in-php/
error_reporting( E_ALL );
ini_set( "display_errors", 1 );

session_start();

$uri = array();

if( isset( $_GET['route'] ) ){ 
	$array_tmp_uri = preg_split('[\\/]', $_GET['route'], -1, PREG_SPLIT_NO_EMPTY);
	$uri['controller'] = @ $array_tmp_uri[0];
	$uri['method']     = @ $array_tmp_uri[1];
	$uri['var']        = @ $array_tmp_uri[2];

    //echo "controller ". $array_tmp_uri[0] .'<br>';
    //echo 'method '. $array_tmp_uri[1].'<br>';
    //echo 'var '. @ $array_tmp_uri[2];
    //die();
}
else{
	$uri['controller'] = "home";
	$uri['method']     = "index"; 
	$uri['var']        = ""; 
}

//Load config and base 
require_once("/config/application.config.php"); 
require_once("application/base.php"); 

//loads controller
$application = new application( $uri );

