<?php
/**
 * Interface.php - Interface for domain controllers
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
 * Interface Steelcode_Controller_Interface
 *
 * @category Steelcode
 * @package Steelcode_Domain
 */
interface Steelcode_Domain_Controller_Interface {

	/**
	 * Function init must be defined in each controller
	 */
	public function init();

	/**
	 * Function controllerAction must be defined in each controller
	 */
	public function controllerAction();
}