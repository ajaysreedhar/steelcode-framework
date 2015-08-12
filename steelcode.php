<?php
/**
 * steelcode.php - Include this file in steelcode projects manually
 *
 * Copyright (C) 2014 Ajay Sreedhar <ajaysreedhar468@gmail.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */

if ( !defined( 'LOAD_INC' ) ) {
	require_once ( 'includes/404.php' );
	exit ( 0 );
}

$controller = "";

/**
 * Steelcode class auto loader function
 * 
 * Register this function using spl_autoload_register
 *
 * @param string $class
 */
function steelcode_autoloader( $class ) {
	
	global $controller;

	$delimiter = ( false === strpos( $class, '\\' ) ) ? '_' : '\\';
	
	$arr = explode( $delimiter, $class);

	$prefix = $arr[0];
	unset( $arr[0] );

	switch( $prefix ) {
		case 'Model':
			$class_path = constant( 'APPLICATION_PATH' ) . 'models/';
			$class_name = str_replace( 'Model_', '', $class );
			$include_path = "{$class_path}{$class_name}.php";
			break;

		case 'Steelcode':
			$class_path = implode( '/', $arr );
			$include_path = constant( 'INCLUDE_PATH' ) . "library/{$class_path}.php";
			break;

		case 'Controller':
			$include_path = $controller;
			break;

		case 'Config':
			$include_path = constant( 'APPLICATION_PATH' ) . 'configs/' . $arr[1] . '.php';
			break;

		case 'Bootstrap':
			$include_path = constant( 'APPLICATION_PATH' ) . 'Bootstrap.php';
			break;

		default:
			$class_path = implode( '/', $arr );
			$include_path = constant( 'INCLUDE_PATH' ) . "extensions/{$prefix}/{$class_path}.php";
			break;
	}


	/** @noinspection PhpIncludeInspection */
	$is_inc = ( include_once ( $include_path ) );

	if ( $is_inc === false || $is_inc != 1 ) {

		/** @noinspection PhpUnusedLocalVariableInspection */
		$path = $include_path;

		require_once ( 'includes/path-not-found.php' );
		exit( 0 );
	}
}

spl_autoload_register( 'steelcode_autoloader' );
