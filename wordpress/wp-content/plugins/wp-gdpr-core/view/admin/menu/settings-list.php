<div>
    <p><?php _e('Miscellaneous settings for your WP-GDPR configuration.', 'wp_gdpr'); ?></p>
    <form method="post" class="postbox">
		<?php
		foreach ( $options as $options_name => $option ) {
			switch ( $option['type'] ) {
				case 'checkbox':
					echo '<div class="postbox-group">';
					echo sprintf( '<label for="%s">%s</label>', $options_name, $option['label'] );
					echo sprintf( '<input type="checkbox" %s name="%s" id="gdpr_%s">', $option['value'], $options_name, $options_name );
					echo '</div>';
					break;
				case 'email':
					echo '<div class="postbox-group">';
					echo sprintf( '<label for="%s">%s</label>', $options_name, $option['label'] );
					echo sprintf( '<input id="gdpr_%s" type="email" name="%s" value="%s">', $options_name, $options_name, $option['value'] );
					echo '</div>';
					break;
				case 'text':
					echo '<div class="postbox-group">';
					echo sprintf( '<label for="%s">%s</label>', $options_name, $option['label'] );
					echo sprintf( '<input id="gdpr_%s" type="text" name="%s" value="%s">', $options_name, $options_name, $option['value'] );
					echo '</div>';
					break;
			}
		}
		?>

        <div class="postbox-group">
            <p>
            <small><a class="dpo_info"
                      href="https://edps.europa.eu/data-protection/data-protection/reference-library/data-protection-officer-dpo_en"
                      target="_blank"><b>More info</b></a> about DPO ( Data Protection Officer )
            </small>
            </p>
            <input type="submit" class="button button-primary" name="gdpr_save_global_settings"
                   value="<?php _e( 'Update settings', 'wp_gdpr' ); ?>">
        </div>

    </form>
</div>