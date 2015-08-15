<?php
/**
 * Handler.php - Steelcode project handler
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
 * Class Steelcode_Project_Handler
 *
 * @category SteelCode
 * @package Steelcode_Project
 */
class Steelcode_Project_Handler {

    /**
     * Message associated  with last operation
     *
     * @var string
     */
    private $_messageText = "";

    /**
     * Error associated with last operation
     *
     * @var string
     */
    private $_errorText = "";

    /**
     * Project location
     *
     * @var string
     */
    private $_location = null;

    /**
     * Project configuration
     *
     * @var array
     */
    private $_config = array();

    /**
     * Object of component to be created
     *
     * @var mixed
     */
    private $_objComponent;

    /**
     * Class constructor
     *
     * @param string $location
     * @param array $config
     */
    public function __construct( $location=null, $config=array() ) {
        $this->_location = $location;
        $this->_config = $config;
    }

    /**
     * Create project components according to configuration
     *
     * @throws Steelcode_Project_Exception
     */
    public function execute() {
        if ( !isset( $this->_config['component'] ) ) {
            throw new Steelcode_Project_Exception( 'Component name not found' );
        }

        try {
            switch ($this->_config['component']) {
                case 'application':
                    $this->_objComponent = new Steelcode_Project_Application();
                    break;

                case 'domain':
                    $this->_objComponent = new Steelcode_Project_Domain();
                    break;

                case 'controller':
                    if ( !isset( $this->_config['parent'] ) ||
                        Steelcode_String_Helper::isNull( $this->_config['parent'] ) ) {

                        throw new Steelcode_Project_Exception(
                            '* Error! Domain is not specified for creating controller' );
                    }

                    $this->_objComponent = new Steelcode_Project_Controller();
                    $this->_objComponent->setDomain( $this->_config['parent'] );
                    break;

                case 'model':
                    $this->_objComponent = new Steelcode_Project_Model();
                    break;

                case 'layout':
                    $this->_config['name'] = "layout-{$this->_config['name']}";
                    $this->_objComponent = new Steelcode_Project_Layout();
                    break;

                case 'config':
                    $this->_objComponent = new Steelcode_Project_Config();
                    break;

                case 'bootstrap':
                    $this->_objComponent = new Steelcode_Project_Bootstrap();
                    break;

                default:
                    throw new Steelcode_Project_Exception(
                        "* Error! Invalid component name '{$this->_config['component']}' found");
                    break;
            }

            $this->_objComponent->setLocation( $this->_location );
            $this->_objComponent->create($this->_config['name']);

            $this->_messageText = $this->_objComponent->getMessageText();

        } catch ( Steelcode_Project_Exception $ex ) {
            $this->_errorText = $ex->getMessage();
            throw $ex;
        }
    }

    /**
     * Set project configuration
     *
     * @param array $config
     */
    public function setConfig( array $config ) {
        $this->_config = $config;
    }

    /**
     * Set project action
     *
     * @param string $action
     */
    public function setAction( $action ) {
        $this->_config['action'] = $action;
    }

    /**
     * Set project component
     *
     * @param string $component
     */
    public function setComponent( $component ) {
        $this->_config['component'] = $component;
    }

    /**
     * set project name
     *
     * @param string $name
     */
    public function setName( $name ) {
        $this->_config['name'] = $name;
    }

    /**
     * set project parent
     *
     * @param string $parent
     */
    public function setParent( $parent ) {
        $this->_config['parent'] = $parent;
    }

    /**
     * Get the message text
     *
     * @return string
     */
    public function getMessageText() {
        return $this->_messageText;
    }

    /**
     * Get the error message
     *
     * @return string
     */
    public function getErrorText() {
        return $this->_errorText;
    }
}