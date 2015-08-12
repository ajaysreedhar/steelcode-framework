<?php
/* Define this constant to make sure that all other files
 * are NOT directly accessed and are invoked only from index.php */
define ( "LOAD_INC", true );

/* Define ABSPATH as path to this directory */
defined( "ABSPATH" ) || define( "ABSPATH", dirname( __FILE__ ) . "/" );

/* Define application path */
defined( "APPLICATION_PATH" ) || define( "APPLICATION_PATH", ABSPATH . "application/" );

/* Define path to the core steelcode classes
 * You may change this path if the core files are installed in some other directory */
define( "INCLUDE_PATH", "steelcode/" );

require_once ( INCLUDE_PATH . "includes/auto-loader.php" );

$obj_steelcode = new Steelcode_Application();

/* Define this constant to help autoloaded function to load 
 * current controller */
define( "CURRENT_CTRL", $obj_steelcode->getControllerPath() );

$obj_steelcode->runApplication();
