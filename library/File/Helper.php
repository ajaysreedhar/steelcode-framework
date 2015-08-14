<?php
class Steelcode_File_Helper {

	/**
	 * Checks whether a file exists or not
	 *
	 * @param string $file
	 * @return bool
	 */
	public static function exists( $file ) {
		return file_exists( $file );
	}
}