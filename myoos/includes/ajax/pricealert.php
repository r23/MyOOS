<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

/**
 * ensure this file is being included by a parent file
 */
defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');

// start the session
if ($session->hasStarted() === false) {
    $session->start();
}


require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/shopping_cart.php';

if (isset($_SESSION['formid']) && ($_SESSION['formid'] == $_POST['formid'])) {

    header("Content-Type: application/json");
    $contents = '<div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title" id="pricealertModalToggleLabel2">' . $aLang['text_price_alert_heading_2'] . '</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
		  
			<h3>' . $aLang['text_price_alert_intro'] . '</h3>
						<div class="form-group">
							<label class="sr-only" for="signin-email">Email</label>
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text" id="signin-email-icon"><i data-feather="mail"></i></span></div>
								<input class="form-control" type="email" name="email_address" id="signin-email" placeholder="' . $aLang['entry_email_address'] . '" aria-label="Email" aria-describedby="signin-email-icon" required>
								<div class="invalid-feedback">' . $aLang['text_please_provide_email_address'] . '</div>
							</div>
						</div>
								
						<div class="form-group">
							<label class="sr-only" for="signin-password">Password</label>
							<div class="input-group">
								<div class="input-group-prepend"><span class="input-group-text" id="signin-password-icon"><i data-feather="lock"></i></span></div>
								<input class="form-control" name="password" type="password" id="signin-password" placeholder="' . $aLang['entry_password'] . '" aria-label="Password" aria-describedby="signin-password-icon" required>
								<div class="invalid-feedback">' . $aLang['text_please_enter_a_password'] . '</div>
							</div>
						</div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-primary" type="submit">' . $aLang['button_save_price_alert'] . '</button>
          </div>  
        </div>';
    echo json_encode($contents, JSON_THROW_ON_ERROR);
}
