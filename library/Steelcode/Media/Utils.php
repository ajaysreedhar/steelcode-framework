<?php
/**
 * Utils.php - Steelcode media utilities
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
 * Class Steelcode_Media_Utils
 *
 * @category Steelcode
 * @package Steelcode_Media
 */
class Steelcode_Media_Utils {

	/**
	 * Array of available extensions
	 *
	 * @var array
	 */
	private $_arrayExts = array();

	/**
	 * Check the file extension against a given array of extensions
	 *
	 * @param string $file : name of the file
	 * @param $extensions (optional) : array of extensions to cross check
	 *
	 * @return bool : true if match found else false
	 */
	public function checkFile( $file, $extensions ) {

		if ( is_array( $extensions ) && $extensions != null )
			$arrayExts = $extensions;

		elseif( is_string( $extensions ) )
			$arrayExts = $this->_arrayExts[$extensions];

		else
			$arrayExts = array();

		if ( is_dir( $file ) ) {
			return false;

		} else {
			$fileExtension = Steelcode_Media_Helper::fileExtension( $file );

			if ( in_array( $fileExtension, $arrayExts ) )
				return true;
		}

		return false;
	}

	/**
	 * Check whether the file is an image
	 *
	 * @param string $file : name of the file
	 * @return bool
	 */
	public function isImage( $file ) {
		return $this->checkFile( $file, $this->_arrayExts['image'] );
	}

	/**
	 * Check whether the file is a video clip
	 *
	 * @param string $file : name of the file
	 * @return bool
	 */
	public function isVideo( $file ) {
		return $this->checkFile( $file, $this->_arrayExts['video'] );
	}

	/**
	 * Check whether the file is an audio clip
	 *
	 * @param string $file  : name of the file
	 * @return bool
	 */
	public function isAudio( $file ) {
		return $this->checkFile( $file, $this->_arrayExts['audio'] );
	}

	/**
	 * Check whether the file is a text document
	 *
	 * @param string $file : name of the file
	 * @return bool
	 */
	public function isDocument( $file ) {
		return $this->checkFile( $file, $this->_arrayExts['document'] );
	}

	/**
	 * Identify the type of a file checking its extension
	 *
	 * @param string $file : name of the file
	 * @return string : type of file
	 */
	public function getFileType( $file ) {
		if ( is_dir( $file ) )
			return 'directory';

		$extension = Steelcode_Media_Helper::fileExtension( $file );

		foreach ( $this->_arrayExts as $type => $extensionList ) {
			if ( in_array( $extension, $extensionList ) ) {
				return $type;
			}
		}

		return 'unknown';
	}

	/**
	 * Get the properties of a file
	 *
	 * @param string $file : name of the file
	 * @return array : array of properties
	 */
	public function getFileProperties( $file ) {
		$fileType = $this->getFileType( $file );

		$fileSizeInBytes = filesize( $file );

		$fileSize = ( int ) $fileSizeInBytes;
		$fileSize = Steelcode_File_Helper::sizeString( $fileSize );
		$extension = Steelcode_Media_Helper::fileExtension( $file );

		$fileModifiedTime = date( 'd M Y', filemtime( $file ) );

		return array(
			'type'=> ( $fileType === 'unknown' ) ? strtoupper( $extension ) . ' file' : ucfirst( $fileType ),
			'size_as_string' => $fileSize,
			'size_in_bytes'  => $fileSizeInBytes,
			'date_modified'  => $fileModifiedTime
		);
	}

	/**
	 * Get available extensions for a given media type
	 *
	 * @param string $type
	 * @return array
	 */
	public function getExtensionsFor( $type ) {
		return isset( $this->_arrayExts[$type] ) ? $this->_arrayExts[$type] : array();
	}

	/**
	 * Set extensions for a given media type
	 *
	 * @param string $type
	 * @param array $extensions
	 */
	public function setExtensionsFor( $type, array $extensions ) {
		$this->_arrayExts[$type] = $extensions;
	}

	/**
	 * Add extensions for a given media type
	 *
	 * @param string $type
	 * @param array $extensions
	 */
	public function addExtensionsFor( $type, array $extensions ) {
		array_merge( $this->_arrayExts[$type], $extensions );
	}
}
