<?php
/**
 * Getopt.php - Steelcode console options processor
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

/**
 * Class Steelcode_Console_Getopt
 *
 * @category Steelcode
 * @package Steelcode_Console
 */
class Steelcode_Console_Getopt {

    const REQUIRED = 'required';        /* Characters followed by a colon (parameter requires value) */

    const OPTIONAL = 'optional';        /* Characters followed by two colons (optional value) */

    const INDIVIDUAL = 'individual';    /* Individual characters (do not accept values) */

    /**
     * Array of short options
     *
     * @var array
     */
    private $_shortOptions = array();

    /**
     * Array of long options
     *
     * @var array
     */
    private $_longOptions = null;

    /**
     * Build short options string from array
     *
     * @return string
     */
    private function _buildShortOptions() {

        $options = "";

        foreach( $this->_shortOptions as $key => $value ) {
            if ( $value === self::REQUIRED ) {
                $options .= "{$key}:";

            } elseif ( $value === self::OPTIONAL ) {
                $options .= "{$key}::";

            } else {
                $options .= "{$key}";
            }
        }

        return $options;
    }

    /**
     * Class constructor
     *
     * @param array $shortOptions
     * @param array $longOptions
     */
    public function __construct( array $shortOptions=array(), array $longOptions=null ) {
        $this->_shortOptions = $shortOptions;
        $this->_longOptions  = $longOptions;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function getOptions() {
        return getopt( self::_buildShortOptions(), $this->_longOptions );
    }

    /**
     * Set short options
     *
     * @param string|array $option
     * @param string $accept
     */
    public function setShortOption( $option, $accept=self::REQUIRED ) {
        if ( ! Steelcode_Array_Helper::isEmpty( $option ) ) {
            $this->_shortOptions = $option;
            return;
        }

        $this->_shortOptions[$option] = $accept;
    }

    /**
     * Set long options
     *
     * @param string|array $option
     * @param string $accept
     */
    public function setLongOption( $option, $accept=self::REQUIRED ) {
        if ( $this->_longOptions === null )
            $this->_longOptions = array();

        $build = ( ! Steelcode_Array_Helper::isEmpty( $option ) ) ? $option : array( $option => $accept );

        foreach ( $build as $option => $accept ) {

            if ( $accept === self::REQUIRED ) {
                $value = "{$option}:";

            } elseif ( $accept === self::OPTIONAL ) {
                $value = "{$option}::";

            } else {
                $value = $option;
            }

            array_push( $this->_longOptions, $value );
        }
    }
}
