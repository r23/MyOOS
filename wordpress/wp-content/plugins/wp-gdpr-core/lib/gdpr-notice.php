<?php

namespace  wp_gdpr\lib;

/**
 * register notice
 */
class Gdpr_Notice {
	/**
	 * message of notice
	 */
	protected $message = 'Your opperation was successfull';

	/**
	 * translation source
	 */
	protected $translation_source = 'sample-text-domain';

	/**
	 * type of notice
	 */
	protected $type = 'info';

	/**
	 * extra classes
	 */
	protected $extra_css_classes;

	/**
	 * set message of notice
	 */
	public function set_message(  $message)
	{
		$this->message = $message;
	}

	/**
	 * register notice with defeault hook admin_notices
	 */
	public function register_notice( $action_hook = 'admin_notices' )
	{
		/**
		 * add action
		 */
		add_action( $action_hook, array( $this, 'register_notice_callback'  ));
	}

	/**
	 * set type of message
	 * possibilities:
	 * error   - color red
	 * warning - color yelow/orange
	 * info    - color blue
	 */
	public function set_type(  $type = 'info'  )
	{
		$this->type = $type;
	}

	/**
	 * add extra css classes for message div
	 */
	public function set_extra_css_classes( $extra_classes )
	{
		$this->extra_css_classes = $extra_classes;
	}

	public function register_notice_callback()
	{
		$class = 'notice notice-'. $this->get_type() .' ' . $this->get_extra_css_classes();
		$message = __( $this->get_message(), $this->get_translation_source() );

		printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) ); 
	}

	/**
	 *  set translation_source 
	 */
	public function set_translation_source( $translation_source )
	{
		$this->translation_source = $translation_source;
	}

	/**
	 * translation_source getter
	 */
	public function get_translation_source()
	{
		return $this->translation_source;
	}
	
	/**
	 * type getter
	 */
	public function get_type()
	{
		return $this->type;
	}

	/**
	 * extra_css_class getter
	 */
	public function get_extra_css_classes()
	{
		return $this->extra_css_classes;
	}

	/**
	 * message getter
	 */
	public function get_message()
	{
		return $this->message;
	}
}

