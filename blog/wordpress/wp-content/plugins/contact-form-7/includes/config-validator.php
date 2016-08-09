<?php

class WPCF7_ConfigValidator {

	const error = 100;
	const error_maybe_empty = 101;
	const error_invalid_syntax = 102;
	const error_email_not_in_site_domain = 103;
	const error_html_in_message = 104;
	const error_multiple_controls_in_label = 105;

	private $contact_form;
	private $errors = array();

	public function __construct( WPCF7_ContactForm $contact_form ) {
		$this->contact_form = $contact_form;

		$config_errors = get_post_meta( $contact_form->id(),
			'_config_errors', true );

		foreach ( (array) $config_errors as $section => $errors ) {
			if ( empty( $errors ) ) {
				continue;
			}

			if ( ! is_array( $errors ) ) { // for back-compat
				$code = $errors;
				$this->add_error( $section, $code );
			} else {
				foreach ( (array) $errors as $error ) {
					if ( ! empty( $error['code'] ) ) {
						$code = $error['code'];
						$args = isset( $error['args'] ) ? $error['args'] : '';
						$this->add_error( $section, $code, $args );
					}
				}
			}
		}
	}

	public function contact_form() {
		return $this->contact_form;
	}

	public function is_valid() {
		return ! $this->count_errors();
	}

	public function count_errors() {
		$count = 0;

		foreach( $this->errors as $errors ) {
			$count += count( array_filter( $errors ) );
		}

		return $count;
	}

	public function collect_error_messages() {
		$error_messages = array();

		foreach ( $this->errors as $section => $errors ) {
			$error_messages[$section] = array();

			foreach ( $errors as $error ) {
				if ( empty( $error['args']['message'] ) ) {
					$message = $this->get_default_message( $error['code'] );
				} elseif ( empty( $error['args']['params'] ) ) {
					$message = $error['args']['message'];
				} else {
					$message = $this->build_message(
						$error['args']['message'],
						$error['args']['params'] );
				}

				$link = '';

				if ( ! empty( $error['args']['link'] ) ) {
					$link = $error['args']['link'];
				}

				$error_messages[$section][] = array(
					'message' => $message,
					'link' => $link );
			}
		}

		return $error_messages;
	}

	public function build_message( $message, $params = '' ) {
		$params = wp_parse_args( $params, array() );

		foreach ( $params as $key => $val ) {
			if ( ! preg_match( '/^[0-9A-Za-z_]+$/', $key ) ) { // invalid key
				continue;
			}

			$placeholder = '%' . $key . '%';

			if ( false !== stripos( $message, $placeholder ) ) {
				$message = str_ireplace( $placeholder, $val, $message );
			}
		}

		return $message;
	}

	public function get_default_message( $code ) {
		switch ( $code ) {
			case self::error_maybe_empty:
				return __( "This field can be empty depending on user input.", 'contact-form-7' );
			case self::error_invalid_syntax:
				return __( "This field has syntax errors.", 'contact-form-7' );
			case self::error_email_not_in_site_domain:
				return __( "This email address does not belong to the same domain as the site.", 'contact-form-7' );
			case self::error_html_in_message:
				return __( "HTML tags are not allowed in a message.", 'contact-form-7' );
			case self::error_multiple_controls_in_label:
				return __( "Multiple form controls are placed inside a single label element.", 'contact-form-7' );
			default:
				return '';
		}
	}

	public function add_error( $section, $code, $args = '' ) {
		$args = wp_parse_args( $args, array(
			'message' => '',
			'params' => array() ) );

		if ( ! isset( $this->errors[$section] ) ) {
			$this->errors[$section] = array();
		}

		$this->errors[$section][] = array( 'code' => $code, 'args' => $args );
	}

	public function remove_error( $section, $code ) {
		if ( empty( $this->errors[$section] ) ) {
			return;
		}

		foreach ( (array) $this->errors[$section] as $key => $error ) {
			if ( isset( $error['code'] ) && $error['code'] == $code ) {
				unset( $this->errors[$section][$key] );
			}
		}
	}

	public function validate() {
		$this->errors = array();

		$this->validate_form();
		$this->validate_mail( 'mail' );
		$this->validate_mail( 'mail_2' );
		$this->validate_messages();

		do_action( 'wpcf7_config_validator_validate', $this );

		$this->save();
		return $this->is_valid();
	}

	public function save() {
		delete_post_meta( $this->contact_form->id(), '_config_errors' );

		if ( $this->errors ) {
			update_post_meta( $this->contact_form->id(), '_config_errors',
				$this->errors );
		}
	}

	public function validate_form() {
		$body = $this->contact_form->prop( 'form' );
		$pattern = '%<label(?:[ \t\n]+.*?)?>(.+?)</label>%s';

		if ( preg_match_all( $pattern, $body, $matches ) ) {
			$manager = WPCF7_ShortcodeManager::get_instance();

			foreach ( $matches[1] as $insidelabel ) {
				$tags = $manager->scan_shortcode( $insidelabel );
				$fields_count = 0;

				foreach ( $tags as $tag ) {
					$tag = new WPCF7_Shortcode( $tag );

					if ( in_array( $tag->basetype, array( 'checkbox', 'radio' ) ) ) {
						$fields_count += count( $tag->values );

						if ( $tag->has_option( 'free_text' ) ) {
							$fields_count += 1;
						}
					} elseif ( ! empty( $tag->name ) ) {
						$fields_count += 1;
					}

					if ( 1 < $fields_count ) {
						$this->add_error( 'form.body',
							self::error_multiple_controls_in_label,
							array( 'link' => __( 'http://contactform7.com/configuration-errors/#form.body:error_multiple_controls_in_label', 'contact-form-7' ) ) );
						return;
					}
				}
			}
		}
	}

	public function validate_mail( $template = 'mail' ) {
		$components = (array) $this->contact_form->prop( $template );

		if ( ! $components ) {
			return;
		}

		if ( 'mail' != $template && empty( $components['active'] ) ) {
			return;
		}

		$components = wp_parse_args( $components, array(
			'subject' => '',
			'sender' => '',
			'recipient' => '',
			'additional_headers' => '',
			'body' => '' ) );

		$callback = array( $this, 'replace_mail_tags_with_minimum_input' );

		$subject = $components['subject'];
		$subject = new WPCF7_MailTaggedText( $subject,
			array( 'callback' => $callback ) );
		$subject = $subject->replace_tags();
		$subject = wpcf7_strip_newline( $subject );

		if ( '' === $subject ) {
			$this->add_error( sprintf( '%s.subject', $template ),
				self::error_maybe_empty,
				array( 'link' => __( 'http://contactform7.com/configuration-errors/#mail.subject:error_maybe_empty', 'contact-form-7' ) ) );
		}

		$sender = $components['sender'];
		$sender = new WPCF7_MailTaggedText( $sender,
			array( 'callback' => $callback ) );
		$sender = $sender->replace_tags();
		$sender = wpcf7_strip_newline( $sender );

		if ( ! wpcf7_is_mailbox_list( $sender ) ) {
			$this->add_error( sprintf( '%s.sender', $template ),
				self::error_invalid_syntax,
				array( 'link' => __( 'http://contactform7.com/configuration-errors/#mail.sender:error_invalid_syntax', 'contact-form-7' ) ) );
		} elseif ( ! wpcf7_is_email_in_site_domain( $sender ) ) {
			$this->add_error( sprintf( '%s.sender', $template ),
				self::error_email_not_in_site_domain,
				array( 'link' => __( 'http://contactform7.com/configuration-errors/#mail.sender:error_email_not_in_site_domain', 'contact-form-7' ) ) );
		}

		$recipient = $components['recipient'];
		$recipient = new WPCF7_MailTaggedText( $recipient,
			array( 'callback' => $callback ) );
		$recipient = $recipient->replace_tags();
		$recipient = wpcf7_strip_newline( $recipient );

		if ( ! wpcf7_is_mailbox_list( $recipient ) ) {
			$this->add_error( sprintf( '%s.recipient', $template ),
				self::error_invalid_syntax,
				array( 'link' => __( 'http://contactform7.com/configuration-errors/#mail.recipient:error_invalid_syntax', 'contact-form-7' ) ) );
		}

		$additional_headers = $components['additional_headers'];
		$additional_headers = new WPCF7_MailTaggedText( $additional_headers,
			array( 'callback' => $callback ) );
		$additional_headers = $additional_headers->replace_tags();
		$additional_headers = explode( "\n", $additional_headers );
		$mailbox_header_types = array( 'reply-to', 'cc', 'bcc' );

		foreach ( $additional_headers as $header ) {
			$header = trim( $header );

			if ( '' === $header ) {
				continue;
			}

			if ( ! preg_match( '/^([0-9A-Za-z-]+):(.+)$/', $header, $matches ) ) {
				$this->add_error( sprintf( '%s.additional_headers', $template ),
					self::error_invalid_syntax,
					array( 'link' => __( 'http://contactform7.com/configuration-errors/#mail.additional_headers:error_invalid_syntax', 'contact-form-7' ) ) );
			} elseif ( in_array( strtolower( $matches[1] ), $mailbox_header_types )
			&& ! wpcf7_is_mailbox_list( $matches[2] ) ) {
				$this->add_error( sprintf( '%s.additional_headers', $template ),
					self::error_invalid_syntax, array(
						'message' =>
							__( "The %name% field value is invalid.", 'contact-form-7' ),
						'params' => array( 'name' => $matches[1] ),
						'link' => __( 'http://contactform7.com/configuration-errors/#mail.additional_headers:error_invalid_syntax', 'contact-form-7' ) ) );
			}
		}

		$body = $components['body'];
		$body = new WPCF7_MailTaggedText( $body,
			array( 'callback' => $callback ) );
		$body = $body->replace_tags();

		if ( '' === $body ) {
			$this->add_error( sprintf( '%s.body', $template ),
				self::error_maybe_empty,
				array( 'link' => __( 'http://contactform7.com/configuration-errors/#mail.body:error_maybe_empty', 'contact-form-7' ) ) );
		}
	}

	public function validate_messages() {
		$messages = (array) $this->contact_form->prop( 'messages' );

		if ( ! $messages ) {
			return;
		}

		if ( isset( $messages['captcha_not_match'] )
		&& ! wpcf7_use_really_simple_captcha() ) {
			unset( $messages['captcha_not_match'] );
		}

		foreach ( $messages as $key => $message ) {
			$stripped = wp_strip_all_tags( $message );

			if ( $stripped != $message ) {
				$this->add_error( sprintf( 'messages.%s', $key ),
					self::error_html_in_message,
					array( 'link' => __( 'http://contactform7.com/configuration-errors/#messages:error_html_in_message', 'contact-form-7' ) ) );
			}
		}
	}

	public function replace_mail_tags_with_minimum_input( $matches ) {
		// allow [[foo]] syntax for escaping a tag
		if ( $matches[1] == '[' && $matches[4] == ']' ) {
			return substr( $matches[0], 1, -1 );
		}

		$tag = $matches[0];
		$tagname = $matches[2];
		$values = $matches[3];

		if ( ! empty( $values ) ) {
			preg_match_all( '/"[^"]*"|\'[^\']*\'/', $values, $matches );
			$values = wpcf7_strip_quote_deep( $matches[0] );
		}

		$do_not_heat = false;

		if ( preg_match( '/^_raw_(.+)$/', $tagname, $matches ) ) {
			$tagname = trim( $matches[1] );
			$do_not_heat = true;
		}

		$format = '';

		if ( preg_match( '/^_format_(.+)$/', $tagname, $matches ) ) {
			$tagname = trim( $matches[1] );
			$format = $values[0];
		}

		$example_email = 'example@example.com';
		$example_text = 'example';
		$example_blank = '';

		$form_tags = $this->contact_form->form_scan_shortcode(
			array( 'name' => $tagname ) );

		if ( $form_tags ) {
			$form_tag = new WPCF7_Shortcode( $form_tags[0] );

			$is_required = ( $form_tag->is_required() || 'radio' == $form_tag->type );

			if ( ! $is_required ) {
				return $example_blank;
			}

			$is_selectable = in_array( $form_tag->basetype,
				array( 'radio', 'checkbox', 'select' ) );

			if ( $is_selectable ) {
				if ( $form_tag->pipes instanceof WPCF7_Pipes ) {
					if ( $do_not_heat ) {
						$before_pipes = $form_tag->pipes->collect_befores();
						$last_item = array_pop( $before_pipes );
					} else {
						$after_pipes = $form_tag->pipes->collect_afters();
						$last_item = array_pop( $after_pipes );
					}
				} else {
					$last_item = array_pop( $form_tag->values );
				}

				if ( $last_item && wpcf7_is_mailbox_list( $last_item ) ) {
					return $example_email;
				} else {
					return $example_text;
				}
			}

			if ( 'email' == $form_tag->basetype ) {
				return $example_email;
			} else {
				return $example_text;
			}

		} else {
			$tagname = preg_replace( '/^wpcf7\./', '_', $tagname ); // for back-compat

			if ( '_post_author_email' == $tagname ) {
				return $example_email;
			} elseif ( '_' == substr( $tagname, 0, 1 ) ) { // maybe special mail tag
				return $example_text;
			}
		}

		return $tag;
	}
}
