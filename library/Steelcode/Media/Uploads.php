<?php
/**
 * Uploads.php - Steelcode media uploads
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
 * Class Steelcode_Media_Uploads
 *
 * @category Steelcode
 * @package Steelcode_Media
 */
class Steelcode_Media_Uploads {

	/**
	 * Maximum upload size in bytes
	 *
	 * @var int
	 */
	private $_fileMaxSize;

	/**
	 * Size of last uploaded file in bytes
	 *
	 * @var int
	 */
	private $_lastFileSize;

	/**
	 * Last uploaded file error code
	 *
	 * @var int
	 */
	private $_lastFileErrorCode;

	/**
	 * Uploads directory location
	 *
	 * @var string
	 */
	private $_mediaLocation;

	/**
	 * Last uploaded file name
	 *
	 * @var string
	 */
	private $_lastFileName;

	/**
	 * Last uploaded file MIME type
	 *
	 * @var string
	 */
	private $_lastFileType;

	/**
	 * Hashed name of last uploaded file
	 *
	 * @var string
	 */
	private $_lastFileHash;

	/**
	 * Extension of last uploaded file
	 *
	 * @var string
	 */
	private $_lastFileExt;

	/**
	 * Last occurred error as string
	 *
	 * @var string
	 */
	private $_lastFileError;

	/**
	 * Input index for incoming file
	 *
	 * @var string
	 */
	private $_inputIndex = 'media_file';

	/**
	 * Array of all allowed file extensions
	 *
	 * @var array
	 */
	private $_extensionList;

	/**
	 * Class constructor
	 *
	 * @param string $location
	 * @param array $extensions
	 * @param int $maxSize
	 */
	public function __construct( $location, $extensions, $maxSize=9000000 ) {
		$this->_mediaLocation = $location;
		$this->_extensionList = $extensions;

		$this->_fileMaxSize   = $maxSize;
	}

	/**
	 * Get file contents from $_FILE server variable and move
	 * the uploaded file to a location specified in $location
	 *
	 * @param string $location (optional) : upload media directory
	 * @throws Steelcode_Media_Exception
	 */
	public function uploadFile( $location = null ) {

		if ( !isset( $_FILES ) || !is_array( $_FILES ) ) {
			$this->_lastFileError = 'No files are selected to upload';
			throw new Steelcode_Media_Exception( $this->_lastFileError );
		}

		if ( Steelcode_String_Helper::isNull( $location ) )
			$mediaDir = $this->_mediaLocation;
		else
			$mediaDir = $location;

		if ( Steelcode_String_Helper::isNull( $mediaDir ) ) {
			$this->_lastFileError = 'No destination is set to move uploaded files';
			throw new Steelcode_Media_Exception( $this->_lastFileError );
		}

		$fileName = $_FILES[$this->_inputIndex]['name'];
		$fileSize = $_FILES[$this->_inputIndex]['size'];
		$fileType = $_FILES[$this->_inputIndex]['type'];

		$fileExt  = Steelcode_Media_Helper::fileExtension( $fileName );

		if ( $fileSize > $this->_fileMaxSize ) {
			$this->_lastFileError = 'File size is larger than the limit';
			throw new Steelcode_Media_Exception( $this->_lastFileError );
		}

		if ( false === in_array( $fileExt, $this->_extensionList ) ) {
			$this->_lastFileError = 'You can not upload this kind of file';
			throw new Steelcode_Media_Exception( $this->_lastFileError );
		}

		do {
			$shaName = sha1( $fileName );
			$shaName = str_shuffle( $shaName ) . ".{$fileExt}";

			$newFilePath = "{$mediaDir}{$shaName}";

		} while( file_exists( $newFilePath ) );


		if ( $_FILES[$this->_inputIndex]['error'] > 0 ) {
			$this->_lastFileError = 'Server set an error code (' . $_FILES[$this->_inputIndex]['error'] . ')';
			$this->_lastFileErrorCode = $_FILES[$this->_inputIndex]['error'];

			throw new Steelcode_Media_Exception( $this->_lastFileError,
				$this->_lastFileErrorCode );
		}

		if ( move_uploaded_file( $_FILES[$this->_inputIndex]['tmp_name'], $newFilePath ) ) {
			$this->_lastFileName = $fileName;
			$this->_lastFileType = $fileType;
			$this->_lastFileExt  = $fileExt;
			$this->_lastFileSize = $fileSize;
			$this->_lastFileHash  = $shaName;

		} else {
			$this->_lastFileError = 'There was an error while uploading the file';
			throw new Steelcode_Media_Exception( $this->_lastFileError );
		}
	}

	/**
	 * Sets a new upload directory
	 *
	 * @param string $location : upload directory
	 */
	public function setMediaLocation( $location ) {
		$this->_mediaLocation = $location;
	}

	/**
	 * Sets the maximum size of files that can be uploaded
	 *
	 * @param int $size : file size in bytes
	 */
	public function setMaxFileUploadSize( $size ) {
		$this->_fileMaxSize = $size;
	}

	/**
	 * Set input index for incoming file
	 *
	 * @param string $index
	 */
	public function setInputIndex( $index ) {
		$this->_inputIndex = "{$index}";
	}

	/**
	 * Get the current media location
	 *
	 * @return string : current media location
	 */
	public function getMediaLocation() {
		return $this->_mediaLocation;
	}

	/**
	 * Get the last occurred error code during file upload
	 *
	 * @return int : last error code
	 */
	public function getLastErrorCode() {
		return $this->_lastFileErrorCode;
	}

	/**
	 * Get the last error occurred during file upload
	 *
	 * @return string : last error message
	 */
	public function getLastErrorMessage() {
		return $this->_lastFileError;
	}

	/**
	 * Get the actual name of the last uploaded file
	 *
	 * @return string : actual name of last uploaded file
	 */
	public function getLastFileName() {
		return $this->_lastFileName;
	}

	/**
	 * Get the hashed name of last uploaded file
	 *
	 * @return string : SHA1 hashed name of the last uploaded file
	 */
	public function getLastFileHash() {
		return $this->_lastFileHash;
	}

	/**
	 * Get the sha1 equivalent of a file name
	 *
	 * @return string : SHA1 hashed name of the last uploaded file
	 */
	public function getUploadedFileName() {
		return $this->_lastFileHash;
	}

	/**
	 * Get the size of last uploaded file
	 *
	 * @return int : size of last uploaded file
	 */
	public function getLastFileSize( ) {
		return $this->_lastFileSize;
	}
}
