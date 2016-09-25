<?php 
// Facebook

// prevent direct calls
if ( ! class_exists('WP') ) { die(); }

// frontend
if ( isset( $frontend ) && $frontend == '1' ) {
	// service url
	$service_url = esc_url( 'https://www.facebook.com/sharer/sharer.php' );

	// build button url
	$button_url = $service_url . '?u=' . $share_url;

	// svg icon
	$svg_icon = '<svg width="32px" height="20px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 32"><path d="M17.1 0.2v4.7h-2.8q-1.5 0-2.1 0.6t-0.5 1.9v3.4h5.2l-0.7 5.3h-4.5v13.6h-5.5v-13.6h-4.5v-5.3h4.5v-3.9q0-3.3 1.9-5.2t5-1.8q2.6 0 4.1 0.2z"/></svg>';

	// colors
	$main_color = '#3b5998';
	$secondary_color = '#4273c8';

	// backend available?
	$backend_available = '1';

	// button title / label
	$button_title_array = array(
		'bg' => 'Сподели във Facebook',
	    'da' => 'Del på Facebook',
	    'de' => 'Bei Facebook teilen',
	    'en' => 'Share on Facebook',
	    'es' => 'Compartir en Facebook',
	    'fi' => 'Jaa Facebookissa',
	    'fr' => 'Partager sur Facebook',
	    'hr' => 'Podijelite na Facebooku',
	    'hu' => 'Megosztás Facebookon',
	    'it' => 'Condividi su Facebook',
	    'ja' => 'フェイスブック上で共有',
	    'ko' => '페이스북에서 공유하기',
	    'nl' => 'Delen op Facebook',
	    'no' => 'Del på Facebook',
	    'pl' => 'Udostępnij na Facebooku',
	    'pt' => 'Compartilhar no Facebook',
	    'ro' => 'Partajează pe Facebook',
	    'ru' => 'Поделиться на Facebook',
	    'sk' => 'Zdieľať na Facebooku',
	    'sl' => 'Deli na Facebooku',
	    'sr' => 'Podeli na Facebook-u',
	    'sv' => 'Dela på Facebook',
	    'tr' => 'Facebook\'ta paylaş',
	    'zh' => '在Facebook上分享',
	);
}
// backend
elseif ( isset( $backend ) && $backend == '1' ) {
	// if we have a fb id and secret, use it
	if ( isset( $shariff3UU['fb_id'] ) && isset( $shariff3UU['fb_secret'] ) ) {
		// on 32-bit PHP the constant is 4 and we have to disable the check because the id is too long
		if ( ( defined( PHP_INT_SIZE ) && PHP_INT_SIZE === '4' ) || ! defined( PHP_INT_SIZE ) ) {
			$fb_app_id = $shariff3UU['fb_id'];
		}
		else {
			$fb_app_id = absint( $shariff3UU['fb_id'] );
		}
		$fb_app_secret = sanitize_text_field( $shariff3UU['fb_secret'] );
		// get fb access token
		$fb_token = sanitize_text_field( wp_remote_retrieve_body( wp_remote_get( 'https://graph.facebook.com/oauth/access_token?client_id=' .  $fb_app_id . '&client_secret=' . $fb_app_secret . '&grant_type=client_credentials' ) ) );
		// use token to get share counts
		$facebook = sanitize_text_field( wp_remote_retrieve_body( wp_remote_get( 'https://graph.facebook.com/v2.2/?id=' . $post_url . '&' . $fb_token ) ) );
		$facebook_json = json_decode( $facebook, true );
		$nofbid = '0';
	}
	// otherwise use the normal way
	else { 
		$facebook = sanitize_text_field( wp_remote_retrieve_body( wp_remote_get( 'https://graph.facebook.com/?id=' . $post_url ) ) );
		$facebook_json = json_decode( $facebook, true );
		$nofbid = '1';
	}

	// store results - use total_count if it exists, otherwise use share_count - ordered based on proximity of occurrence
	if ( isset( $facebook_json['share'] ) && isset( $facebook_json['share']['share_count'] ) ) {
		$share_counts['facebook'] = intval( $facebook_json['share']['share_count'] );
	}
	elseif ( isset( $facebook_json['data'] ) && isset( $facebook_json['data'][0] ) && isset( $facebook_json['data'][0]['total_count'] ) ) {
		$share_counts['facebook'] = intval( $facebook_json['data'][0]['total_count'] );
	}
	elseif ( isset($facebook_json['data'] ) && isset( $facebook_json['data'][0] ) && isset( $facebook_json['data'][0]['share_count'] ) ) {
		$share_counts['facebook'] = intval( $facebook_json['data'][0]['share_count'] );
	}
	elseif ( isset( $facebook_json['id'] ) && ! isset( $facebook_json['error'] ) && $nofbid = '1' ) {
		$share_counts['facebook'] = '0';
	}
	// record errors, if enabled (e.g. request from the status tab)
	elseif ( isset( $record_errors ) && $record_errors == '1' ) {
		$service_errors['facebook'] = $facebook;
	}
}
