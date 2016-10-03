<?php
class Steelcode_Http_Request_Headers {

	public function readCookie( $cookie ) {
		if ( isset( $_COOKIE[$cookie] ) ) {
			return $_COOKIE[$cookie];
		}

		return null;
	}
}