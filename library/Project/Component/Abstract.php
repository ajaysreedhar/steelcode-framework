<?php
/**
 * Abstract.php - Steelcode project component abstract
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
 * Class Steelcode_Project_Component_Abstract
 *
 * @category SteelCode
 * @package Steelcode_Project
 */
abstract class Steelcode_Project_Component_Abstract {

    /**
     * Error message associated with last
     * failed operation
     *
     * @var string
     */
    private $_errorText = "";

    /**
     * Message associated with last operation
     *
     * @var string
     */
    private $_messageText = "";

    /**
     * Present working directory
     *
     * @var string
     */
    protected $_location;

    /**
     * Name of the component to be created
     *
     * @var string
     */
    protected $_name;

    /**
     * Absolute path to the new component
     *
     * @var string
     */
    protected $_path;

    /**
     * File pointer
     *
     * @var resource
     */
    protected $_filePointer;

    /**
     * Set the message
     *
     * @param string $message
     */
    protected function _setMessageText( $message ) {
        $this->_messageText = $message;
    }

    /**
     * Set the error message
     *
     * @param string $errorText
     */
    protected function _setErrorText( $errorText ) {
        $this->_errorText = $errorText;
    }

    /**
     * Class constructor
     *
     * @param string $location
     * @param string $name
     */
    public function __construct( $location=null, $name=null ) {
        $this->_location = $location;
        $this->_name = $name;
    }

    /**
     * Close open files on shut down
     */
    public function __destruct() {
        if ( $this->_filePointer != null ) {
            fclose( $this->_filePointer );
        }
    }

    /**
     * Create the component
     *
     * @param string $name
     * @throws Steelcode_Project_Exception
     */
    public function create( $name=null ) {
        if ( is_string( $name ) && $name != null )
            $this->_name = $name;

        if ( false === $this->_createComponent() ) {
            throw new Steelcode_Project_Exception( $this->_errorText );
        }
    }

    /**
     * Set present working directory
     *
     * @param string $location
     */
    public function setLocation( $location ) {
        $this->_location = $location;
    }

    /**
     * Set component name
     *
     * @param string $name
     */
    public function setName( $name ) {
        $this->_name = $name;
    }

    /**
     * Get message text
     *
     * @return string
     */
    public function getMessageText() {
        return $this->_messageText;
    }

    /**
     * Get error text
     *
     * @return string
     */
    public function getErrorText() {
        return $this->_errorText;
    }

    /**
     * Must be implemented in child classes
     */
    abstract protected function _createComponent();
}
