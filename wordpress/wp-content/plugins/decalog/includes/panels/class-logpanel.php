<?php
/**
 * Log panel for Tracy.
 *
 * Handles all base features for Tracy panels.
 *
 * @package Panels
 * @author  Pierre Lannoy <https://pierre.lannoy.fr/>.
 * @since   3.2.0
 */

namespace Decalog\Panel;

use Decalog\Handler\TracyHandler;
use Feather\Icons;

/**
 * Log panel for Tracy.
 *
 * Handles all base features for Tracy panels.
 *
 * @package Panels
 * @author  Pierre Lannoy <https://pierre.lannoy.fr/>.
 * @since   3.2.0
 */
class LogPanel extends AbstractPanel {

	/**
	 * {@inheritDoc}
	 */
	protected function get_icon() {
		return Icons::get_base64( 'alert-triangle', 'none', '#579FF4' );
	}

	/**
	 * {@inheritDoc}
	 */
	protected function get_name() {
		return sprintf( '%d Events captured by DecaLog', TracyHandler::count() );
	}

	/**
	 * {@inheritDoc}
	 */
	protected function get_title() {
		return sprintf( '%d Events', TracyHandler::count() );
	}

	/**
	 * {@inheritDoc}
	 */
	public function getTab() {
		if ( 0 !== TracyHandler::count() ) {
			return $this->get_standard_tab();
		}
		return null;
	}

	/**
	 * Renders HTML code for custom panel.
	 * @return string
	 */
	public function getPanel() {
		return $this->get_arrays_panel( TracyHandler::get(), true );
	}
}
