<?php 
	// load hybridauth base file, change the following paths if necessary 
	// note: in your application you probably have to include these only when required.
	$hybridauth_config = '/hybridauth/config.php';
	require_once( "/hybridauth/Hybrid/Auth.php" );
    require_once( "/config/dbconfig.php" );

	// database config
	$database_host = DB_HOST; 
	$database_user = DB_USER;
	$database_pass = DB_PASSWORD;
	$database_name = DB_NAME;

    global $database_link;
	$database_link = @ mysqli_connect( $database_host, $database_user, $database_pass, $database_name ) ;

	if ( ! $database_link ) {
		die( "Please edit the configuration file: <b>application.config.php</b>. <hr><b>Mysql error</b>: " . mysqli_error() );
	}

	function mysql_query_excute($sql ){ 
        global $database_link;

		$result = mysqli_query($database_link, $sql);

		if (!$result) {
			$message  = 'Invalid query: ' . mysqli_error() . "\n";
			$message .= 'Whole query: ' . $sql;
			die($message);
		}

		return $result;
	}
