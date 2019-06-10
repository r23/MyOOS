<?php
/**
 * Import panel template.
 *
 * @package    RankMath
 * @subpackage RankMath\Admin
 */

?>
<form id="rank-math-import-form" class="rank-math-export-form cmb2-form" action="" method="post" enctype="multipart/form-data" accept-charset="<?php echo esc_attr( get_bloginfo( 'charset' ) ); ?>">

	<h3><?php esc_html_e( 'Import Settings', 'rank-math' ); ?></h3>

	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">
					<label for="import-me"><?php esc_html_e( 'Setting File', 'rank-math' ); ?></label>
				</th>
				<td>
					<input type="file" name="import-me" id="import-me" value="">
					<br>
					<span class="validation-message"><?php esc_html_e( 'Please select a file to import.', 'rank-math' ); ?></span>
					<p class="description"><?php esc_html_e( 'Import settings by locating setting file and clicking "Import settings".', 'rank-math' ); ?></p>
				</td>
			</tr>
		</tbody>
	</table>

	<footer>
		<?php wp_nonce_field( 'rank-math-import-settings' ); ?>
		<input type="hidden" name="object_id" value="import-plz">
		<input type="hidden" name="action" value="wp_handle_upload">
		<button type="submit" class="button button-primary button-xlarge"><?php esc_html_e( 'Import', 'rank-math' ); ?></button>
	</footer>
</form>
