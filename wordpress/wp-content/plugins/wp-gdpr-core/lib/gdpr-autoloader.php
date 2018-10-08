<?php

/**
 * This class does include classes with namespaces as a file
 */
class Gdpr_Autoloader {

	const NAMESPACE_NAME = 'wp_gdpr\\';
	const GF_NAMESPACE_NAME = 'wp_gdpr_gf\\';
	const CFDB7_NAMESPACE_NAME = 'wp_gdpr_cfdb7\\';

	/**
	 * Register autoloader
	 */
	public function __construct() {
		spl_autoload_register( array( $this, 'autoloader_callback' ) );
	}

	/**
	 * Include class if the file is found
	 *
	 * @param $class    string  Full name of a class: namespaces\classname
	 */
	public function autoloader_callback( $class ) {
		if ( strpos( $class, self::NAMESPACE_NAME ) === 0 ) {
			$path = substr( $class, strlen( self::NAMESPACE_NAME ) );
			$path = strtolower( $path );
			$path = str_replace( '_', '-', $path );
			$path = str_replace( '\\', DIRECTORY_SEPARATOR, $path ) . '.php';
			$path = GDPR_DIR . DIRECTORY_SEPARATOR . $path;

			if ( file_exists( $path ) ) {
				include $path;
			}
			/**
			 * add on gf
			 */
			//TODO update this functions in all addons and solve this code here as examlpe on line 59
		} elseif ( strpos( $class, self::GF_NAMESPACE_NAME ) === 0 ) {
			$path = substr( $class, strlen( self::GF_NAMESPACE_NAME ) );
			$path = strtolower( $path );
			$path = str_replace( '_', '-', $path );
			$path = str_replace( '\\', DIRECTORY_SEPARATOR, $path ) . '.php';
			$path = GDPR_GF_DIR . DIRECTORY_SEPARATOR . $path;

			if ( file_exists( $path ) ) {
				include $path;
			}
		} elseif ( strpos( $class, self::CFDB7_NAMESPACE_NAME ) === 0 ) {
			$path = substr( $class, strlen( self::CFDB7_NAMESPACE_NAME ) );
			$path = strtolower( $path );
			$path = str_replace( '_', '-', $path );
			$path = str_replace( '\\', DIRECTORY_SEPARATOR, $path ) . '.php';
			$path = GDPR_CFDB7_DIR . DIRECTORY_SEPARATOR . $path;

			if ( file_exists( $path ) ) {
				include $path;
			}
		}elseif ( 0 ===  strpos( $class, 'wp_gdpr_' )){
			$chunks = explode('\\', $class);
			$path = apply_filters( 'autoloader_' . $chunks[0], $class  );

			if ( file_exists( $path ) ) {
				include $path;
			}
		}
	}
}

new Gdpr_Autoloader();
