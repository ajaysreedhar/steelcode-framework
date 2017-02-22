<?php
/**
 * sc.php - Creates steelcode project components
 *
 * Copyright (C) 2015 Ajay Sreedhar <ajaysreedhar468@gmail.com>
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

/* Define this constant to make sure that all other files
 * are NOT directly accessed and are invoked only from sc.php */
define ( 'LOAD_INC', true );

defined( 'ABSPATH' ) || define( 'ABSPATH', dirname( __FILE__ ) . '/' );

defined( 'APPLICATION_PATH' ) || define( 'APPLICATION_PATH', ABSPATH . 'application/' );

define( 'INCLUDE_PATH', 'steelcode-framework/' );

require_once ( INCLUDE_PATH . 'steelcode.php' );

$opt = new Steelcode_Console_Getopt();

/* Array of available short options */
$shortOpts = array (
    'l' => $opt::REQUIRED,
    'a' => $opt::REQUIRED,
    'c' => $opt::REQUIRED,
    'n' => $opt::REQUIRED,
    'p' => $opt::OPTIONAL
    );

/* Array of available long options */
$longOptions = array (
    'location'  => $opt::REQUIRED,
    'action'    => $opt::REQUIRED,
    'component' => $opt::REQUIRED,
    'name'      => $opt::REQUIRED,
    'parent'    => $opt::OPTIONAL
    );

$opt->setShortOption( $shortOpts );
$opt->setLongOption( $longOptions );

$options = $opt->getOptions();

try {
    if ( isset( $options['l'] ) || isset( $options['location'] ) ) {
        $location = ( isset($options['l'] ) ) ? $options['l'] : $options['location'];

    } else {
        exit( "No target location specified!\n" );
    }

    $config = array (
        'action'    => ( isset( $options['a'] ) ) ? $options['a'] : $options['action'],
        'component' => ( isset( $options['c'] ) ) ? $options['c'] : $options['component'],
        'name'      => ( isset( $options['n'] ) ) ? $options['n'] : $options['name'],
        );

    if ( isset( $options['p'] ) && $options['p'] != false )
        $config['parent'] = $options['p'];
    elseif ( isset( $options['parent'] ) && $options['parent'] != false )
        $config['parent'] = $options['parent'];

    if ( isset( $config['parent'] ) && Steelcode_String_Helper::isNull( $config['parent'] ) )
        unset( $config['parent'] );

    $project = new Steelcode_Project_Handler( $location, $config );
    $project->execute();

    echo "{$project->getMessageText()}\n";

} catch ( Steelcode_Project_Exception $ex ) {
    echo "{$ex->getMessage()}\n";
}
