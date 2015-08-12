<?php
/* Define this constant to make sure that all other files
 * are NOT directly accessed and are invoked only from index.php */
define ( "LOAD_INC", true );

defined( "ABSPATH" ) || define( "ABSPATH", dirname( __FILE__ ) . "/" );

defined( "APPLICATION_PATH" ) || define( "APPLICATION_PATH", ABSPATH . "application/" );

/* Define path to the core steelcode classes
 * You may change this path if the core files are installed in some other directory */
define( "INCLUDE_PATH", "Steelcode/" );

require_once ( INCLUDE_PATH . "steelcode.php" );

$application = new Steelcode_Application();
$controller  = $application->getControllerPath();

$application->runApplication( APPLICATION_PATH );
