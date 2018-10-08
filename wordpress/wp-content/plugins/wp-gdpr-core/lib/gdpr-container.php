<?php

namespace wp_gdpr\lib;


class Gdpr_Container {
//TODO add register and resolve function
	protected static $container;

	//TODO change it in something better

	/**
	 * @param string $class_name
	 * @param array $arguments
	 *
	 * @return mixed
	 * @throws \Exception
	 * search in registered classes and create object
	 */
	public static function make(  $class_name, $arguments = null ) {
		/**
		 * //TODO add function to create objects when are called
		 * //TODO do not register objects in array
		 * //TODO register only the interface and name of class
		 */
		try {
			if ( defined( 'GDPR_TESTING' )  && true === GDPR_TESTING ) {
				return self::get_mock( $class_name, $arguments );
			} else {
				return self::get_object( $class_name, $arguments );
			}
		} catch ( \Exception $e ) {
			throw new \Exception( 'Class ' . $class_name . ' is not registered in container' );
		}
	}

	/**
	 * @param $class_name
	 * @param $arguments
	 * get mock of class
	 *
	 * @return object mock
	 */
	public static function get_mock( $class_name, $arguments ) {
		return self::$container[ $class_name ];
	}

	/**
	 * @param $class_name
	 * @param $arguments
	 *
	 * @return mixed
	 *
	 * get object of class
	 */
	public static function get_object( $class_name, $arguments ) {
		return new $class_name( $arguments );
	}

	/**
	 * register mock in tests
	 *
	 * @param $name
	 * @param $mock
	 */
	public static function register_mock( $name, $mock ) {
		self::$container[ $name ] = $mock;
	}
}