/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n'

export default ( { onClick } ) => {
	return (
		<tr className="row-footer" onClick={ onClick }>
			<td colSpan="8">
				<div className="last-crawl-data">
					<div>
						<strong>{ __( 'Google: ', 'rank-math' ) }</strong>
						<span className="blurred">{ __( 'Available in the PRO version', 'rank-math' ) }</span>
					</div>
					<div>
						<strong>{ __( 'Last Crawl: ', 'rank-math' ) }</strong>
						<span className="blurred">{ __( 'PRO Feature', 'rank-math' ) }</span>
					</div>
				</div>
			</td>
		</tr>
	)
}
