<?php
/* ----------------------------------------------------------------------
   $Id: eng.php,v 1.3 2007/06/12 16:57:18 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2018 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: english.php,v 1.107 2003/02/17 11:49:25 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */


/* ----------------------------------------------------------------------
   If you made a translation, please send to 
      lang@oos-shop.de 
   the translated file. 
   ---------------------------------------------------------------------- */

 /**
  * look in your $PATH_LOCALE/locale directory for available locales..
  * on RedHat try 'en_US'
  * on FreeBSD try 'en_US.ISO_8859-1'
  * on Windows try 'en', or 'English'
  */
  @setlocale(LC_TIME, 'en_US');
  define('DATE_FORMAT_SHORT', '%m/%d/%Y');  // this is used for strftime()
  define('DATE_FORMAT_LONG', '%A %d %B, %Y'); // this is used for strftime()
  define('DATE_FORMAT', 'm/d/Y'); // this is used for date()
  define('DATE_TIME_FORMAT', DATE_FORMAT_SHORT . ' %H:%M:%S');
  define('DATE_TIME_FORMAT_SHORT', '%H:%M:%S');


 /**
  * Return date in raw format
  * $date should be in format mm/dd/yyyy
  * raw date is in format YYYYMMDD, or DDMMYYYY
  *
  * @param $date
  * @param $reverse
  * @return string
  */
function oos_date_raw($date, $reverse = FALSE) {
	if ($reverse) {
		return substr($date, 3, 2) . substr($date, 0, 2) . substr($date, 6, 4);
	} else {
		return substr($date, 6, 4) . substr($date, 0, 2) . substr($date, 3, 2);
	}
}


// Global entries for the <html> tag
define('LANG', 'en');

$aLang['welcome_msg'] = 'MANY PRODUCTS REDUCED 40%';
$aLang['welcome_msg_title'] = '';
$aLang['danger'] = 'Oh snap! You got an error!';
$aLang['warning'] = 'Warning!';

// theme/system/_header.html
$aLang['header_title_create_account'] = 'Create an Account';
$aLang['header_title_my_account'] = 'My Account';
$aLang['header_title_cart_contents'] = 'Cart Contents';
$aLang['header_title_checkout'] = 'Checkout';
$aLang['header_title_top'] = 'Home';
$aLang['header_title_logoff'] = 'Log Off';
$aLang['header_title_login'] = 'Log In';
$aLang['header_title_contact'] = 'Kontakt';
$aLang['header_title_whats_new'] = 'What\'s New?';
$aLang['header_select_language'] = 'Your Language';
$aLang['header_select_currencies'] = 'Currency';

$aLang['sub_title_total'] = 'Total:';

$aLang['block_heading_specials'] = 'Specials';


// footer text in includes/oos_footer.php
$aLang['footer_text_requests_since'] = 'requests since';

// text for gender
$aLang['male'] = 'Male';
$aLang['female'] = 'Female';
$aLang['male_address'] = 'Mr.';
$aLang['female_address'] = 'Ms.';
$aLang['email_greet_mr'] = 'Dear Mr. %s,';
$aLang['email_greet_ms'] = 'Dear Ms. %s,';
$aLang['email_greet_none'] = 'Hi,';


// text for date of birth example
define('DOB_FORMAT_STRING', 'mm/dd/yyyy');
$aLang['dob_format_string'] = 'mm/dd/yyyy';

// search block text in tempalate/your theme/block/search.html
$aLang['block_search_text'] = 'Use keywords to find the product you are looking for.';
$aLang['block_search_advanced_search'] = 'Advanced Search';
$aLang['text_search'] = 'search...';

// reviews block text in tempalate/your theme/block/reviews.html
$aLang['block_reviews_write_review'] = 'Write a review on this product!';
$aLang['block_reviews_no_reviews'] = 'There are currently no product reviews';
$aLang['block_reviews_text_of_5_stars'] = '%s of 5 Stars!';


// shopping_cart block text in tempalate/your theme/system/_header.html
$aLang['block_shopping_cart_empty'] = '0 items';
$aLang['sub_title_sub_total'] = 'Sub-Total';

// notifications block text in tempalate/your theme/block/products_notifications.html
$aLang['block_notifications_notify'] = 'Notify me of updates to <strong>%s</strong>';
$aLang['block_notifications_notify_remove'] = 'Do not notify me of updates to <strong>%s</strong>';

// wishlist 
$aLang['button_wishlist'] = 'Wishlist';
$aLang['block_wishlist'] = 'Wishlist';
$aLang['block_wishlist_empty'] = 'You have no items on your Wishlist';

// manufacturer block text in tempalate/your theme/block/
$aLang['block_manufacturer_info_homepage'] = '%s Homepage';
$aLang['block_manufacturer_info_other_products'] = 'Other products';

$aLang['block_add_product_id_text'] = 'Enter the model of the product you wish to add to your shopping cart.';

// information block text in tempalate/your theme/block/information.html
$aLang['block_information_imprint'] = 'Imprint';
$aLang['block_information_privacy'] = 'Privacy Notice';
$aLang['block_information_conditions'] = 'Conditions of Use';
$aLang['block_information_shipping'] = 'Shipping & Returns';
$aLang['block_information_gv'] = 'Gift Voucher FAQ';

// login
$aLang['entry_email_address'] = 'E-Mail Address';
$aLang['entry_password'] = 'Password';
$aLang['text_password_info'] = 'Password forgotten?';
$aLang['button_login'] = 'Login';
$aLang['login_block_new_customer'] = 'New Customer';
$aLang['login_block_account_edit'] = 'Edit Account Info.';
$aLang['login_block_account_history'] = 'Account History';
$aLang['login_block_order_history'] = 'Order History';
$aLang['login_block_address_book'] = 'My Address Book';
$aLang['login_block_product_notifications'] = 'Product Notifications';
$aLang['login_block_my_account'] = 'General Information';
$aLang['login_block_logoff'] = 'Log Off';
$aLang['login_entry_remember_me'] = 'Aoto Log On';

// checkout procedure text
$aLang['checkout_bar_delivery'] = 'Delivery Information';
$aLang['checkout_bar_payment'] = 'Payment Information';
$aLang['checkout_bar_confirmation'] = 'Confirmation';
$aLang['checkout_bar_finished'] = 'Finished!';

// pull down default text
$aLang['pull_down_default'] = 'Please Select';
$aLang['type_below'] = 'Type Below';

//newsletter
$aLang['block_newsletter_subscribe'] = 'subscribe to our weekly <strong>newsletter</strong>';
$aLang['block_newsletter_placeholder'] = 'Email your email...';
$aLang['block_newsletter_unsubscribe'] = 'Unsubscribe';
$aLang['error_email_address'] =  '<strong>Your e-mail address:</strong> None or invalid input!';
$aLang['newsletter_email_info'] =  'Your e-mail adress has been registered in our system.<br />An e-mail with a confirmation link has been send out. Click the link in order to complete registration!';
$aLang['newsletter_email_subject'] = 'Your newsletter account';
$aLang['newsletter_notice'] = 'You may unsubscribe at any moment. For that purpose, please find our contact info in the legal notice.';
$aLang['entry_newsletter_no'] = 'No';
$aLang['entry_newsletter_yes'] = 'Yes';
$aLang['text_email_active'] = 'Your e-mail address has successfully been registered for the newsletter!';
$aLang['text_email_active_error'] = 'An error occured, your e-mail address has not been registered for the newsletter!';
$aLang['text_email_del'] = 'Your e-mail address was deleted successfully from our newsletter-database.';
$aLang['text_email_del_error'] = 'An Error occured, your e-mail address has not been removed from our database!';


// footer
$aLang['get_in_touch_with_us'] = 'Get in touch with us';
$aLang['header_title_service'] = 'Shop service';
$aLang['block_service_new'] = 'New Products';
$aLang['block_service_specials'] = 'Specials';
$aLang['block_service_sitemap'] = 'Sitemap';
$aLang['block_service_advanced_search'] = 'Advanced Search';
$aLang['block_service_reviews'] = 'Reviews';
$aLang['block_service_shopping_cart'] = 'Cart Contents';
$aLang['block_service_contact'] = 'Contact';

$aLang['review_text'] = 'The \'Review Text\' must have at least ' . REVIEW_TEXT_MIN_LENGTH . ' characters.';
$aLang['review_rating'] = 'You must rate the product for your review.';
$aLang['review_headline'] = 'The \'Headline\' must have at least 10 characters.';
$aLang['form_error'] = '<strong>Sorry!</strong> You need to complete all mandatory (*) fields!';


// javascript messages
$aLang['js_error'] = 'Errors have occured during the process of your form!\nPlease make the following corrections:\n\n';
$aLang['js_gender'] = '* The \'Gender\' value must be chosen.\n';
$aLang['js_first_name'] = '* The \'First Name\' entry must have at least ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' characters.\n';
$aLang['js_last_name'] = '* The \'Last Name\' entry must have at least ' . ENTRY_LAST_NAME_MIN_LENGTH . ' characters.\n';
$aLang['js_dob'] = '* The \'Date of Birth\' entry must be in the format: xx/xx/xxxx (month/day/year).\n';
$aLang['js_email_address'] = '* The \'E-Mail Address\' entry must have at least ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' characters.\n';
$aLang['js_address'] = '* The \'Street Address\' entry must have at least ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' characters.\n';
$aLang['js_post_code'] = '* The \'Post Code\' entry must have at least ' . ENTRY_POSTCODE_MIN_LENGTH . ' characters.\n';
$aLang['js_city'] = '* The \'City\' entry must have at least ' . ENTRY_CITY_MIN_LENGTH . ' characters.\n';
$aLang['js_state'] = '* The \'State\' entry must be selected.\n';
$aLang['js_country'] = '* The \'Country\' entry must be selected.\n';
$aLang['js_password'] = '* The \'Password\' and \'Confirmation\' entries must match and have at least ' . ENTRY_PASSWORD_MIN_LENGTH . ' characters.\n';

$aLang['js_error_no_payment_module_selected'] = '* Please select a payment method for your order.\n';
$aLang['js_error_submitted'] = 'This form has already been submitted. Please press Ok and wait for this process to be completed.';

$aLang['error_no_payment_module_selected'] = 'Please select a payment method for your order.';

$aLang['category_company'] = 'Company Details';
$aLang['category_personal'] = 'Your Personal Details';
$aLang['category_address'] = 'Your Address';
$aLang['category_contact'] = 'Your Contact Information';
$aLang['category_options'] = 'Options';
$aLang['category_password'] = 'Your Password';
$aLang['entry_company'] = 'Company Name';
$aLang['entry_company_error'] = '';
$aLang['entry_company_text'] = '';
$aLang['entry_owner'] = 'Owner';
$aLang['entry_owner_error'] = '';
$aLang['entry_owner_text'] = '';
$aLang['entry_vat_id'] = 'VAT ID';
$aLang['entry_vat_id_error'] = 'The chosen VatID is not valid or not proofable at this moment! Please fill in a valid ID or leave the field empty.';
$aLang['entry_vat_id_text'] = '* for Germany and EU-Countries only';
$aLang['entry_gender'] = 'Gender';
$aLang['entry_gender_error'] = 'Please select your Gender.';
$aLang['entry_first_name'] = 'First Name';
$aLang['entry_first_name_error'] = 'Your First Name must contain a minimum of ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' characters.';
$aLang['entry_last_name'] = 'Last Name';
$aLang['entry_last_name_error'] = 'Your Last Name must contain a minimum of ' . ENTRY_LAST_NAME_MIN_LENGTH . ' characters.';
$aLang['entry_date_of_birth'] = 'Date of Birth';
$aLang['entry_date_of_birth_error'] = 'Your Date of Birth must be in this format: MM/DD/YYYY (eg 05/21/1970)';
$aLang['entry_date_of_birth_text'] = '(eg. 05/21/1970)';
$aLang['entry_email_address_error'] = 'Your E-Mail Address must contain a minimum of ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' characters.';
$aLang['entry_email_address_check_error'] = 'Your E-Mail Address does not appear to be valid - please make any necessary corrections.';
$aLang['entry_email_address_error_exists'] = 'Your E-Mail Address already exists in our records - please log in with the e-mail address or create an account with a different address.';

$aLang['entry_street_address'] = 'Street Address';
$aLang['entry_street_address_error'] = 'Your Street Address must contain a minimum of ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' characters.';
$aLang['entry_post_code'] = 'Post Code';
$aLang['entry_post_code_error'] = 'Your Post Code must contain a minimum of ' . ENTRY_POSTCODE_MIN_LENGTH . ' characters.';
$aLang['entry_city'] = 'City';
$aLang['entry_city_error'] = 'Your City must contain a minimum of ' . ENTRY_CITY_MIN_LENGTH . ' characters.';
$aLang['entry_state'] = 'State/Province';
$aLang['entry_state_error'] = 'Your State must contain a minimum of ' . ENTRY_STATE_MIN_LENGTH . ' characters.';
$aLang['entry_state_error_select'] = 'Please select a state from the States pull down menu.';

$aLang['entry_country'] = 'Country';
$aLang['entry_country_error'] = 'You must select a country from the Countries pull down menu.';
$aLang['entry_telephone_number'] = 'Telephone Number';
$aLang['entry_newsletter'] = 'Newsletter';
$aLang['entry_newsletter_text'] = '';
$aLang['entry_newsletter_yes'] = 'Subscribed';
$aLang['entry_newsletter_no'] = 'Unsubscribed';
$aLang['entry_newsletter_error'] = '';
$aLang['entry_password_confirmation'] = 'Password Confirmation:';
$aLang['entry_password_error'] = 'Your Password must contain a minimum of ' . ENTRY_PASSWORD_MIN_LENGTH . ' characters.';
$aLang['entry_password_error_not_matching'] = 'The Password Confirmation must match your Password.';

$aLang['password_hidden'] = '--HIDDEN--';
$aLang['entry_info_text'] = 'require_onced';
$aLang['entry_subject'] = 'Subject';

$aLang['entry_agree_error'] = 'Please accept our <strong>Terms and Conditions</strong> and <strong>Privacy Policy</strong>!';
$aLang['agree'] = 'By creating an account, you agree to the <a href="%s" target="_blank"><strong>Terms and Conditions</strong></a> and <a href="%s" target="_blank"><strong>Privacy Policy</strong></a>.';
$aLang['newsletter_agree'] = 'Yes, I wish to receive occasional emails about special offers, new products and exclusive promotions. I can cancel my subscription at any time. (Optional)';

$aLang['success_address_book_entry_deleted'] = 'The selected entry has been deleted successfully.';
$aLang['warning_primary_address_deletion'] = 'The standard postal address can not be deleted.';
$aLang['success_address_book_entry_updated'] = 'Your address book has been updated sucessfully!';
$aLang['error_nonexisting_address_book_entry'] = 'This address book entry is not available.';
$aLang['error_address_book_full'] = 'Your addressbook is full. In order to add new addresses, please erase previous ones first.';


// constants for use in oos_prev_next_display function
$aLang['text_result_page'] = 'Result Pages:';
$aLang['text_display_number_of_products'] = 'Displaying <strong>%d</strong> to <strong>%d</strong> (of <strong>%d</strong> products)';
$aLang['text_display_number_of_orders'] = 'Displaying <strong>%d</strong> to <strong>%d</strong> (of <strong>%d</strong> orders)';
$aLang['text_display_number_of_reviews'] = 'Displaying <strong>%d</strong> to <strong>%d</strong> (of <strong>%d</strong> reviews)';
$aLang['text_display_number_of_products_new'] = 'Displaying <strong>%d</strong> to <strong>%d</strong> (of <strong>%d</strong> new products)';
$aLang['text_display_number_of_specials'] = 'Displaying <strong>%d</strong> to <strong>%d</strong> (of <strong>%d</strong> specials)';
$aLang['text_display_number_of_wishlist'] = 'Displaying <strong>%d</strong> to <strong>%d</strong> (of <strong>%d</strong> products)';

$aLang['prevnext_title_first_page'] = 'First Page';
$aLang['prevnext_title_previous_page'] = 'Previous Page';
$aLang['prevnext_title_next_page'] = 'Next Page';
$aLang['prevnext_title_last_page'] = 'Last Page';
$aLang['prevnext_title_page_no'] = 'Page %d';
$aLang['prevnext_title_prev_set_of_no_page'] = 'Previous Set of %d Pages';
$aLang['prevnext_title_next_set_of_no_page'] = 'Next Set of %d Pages';
$aLang['prevnext_button_first'] = '&lt;&lt;FIRST';
$aLang['prevnext_button_prev'] = '&lt;&lt;&nbsp;Prev';
$aLang['prevnext_button_next'] = 'Next&nbsp;&gt;&gt;';
$aLang['prevnext_button_last'] = 'LAST&gt;&gt;';

$aLang['button_account'] = 'Profil';
$aLang['button_add_address'] = 'Add Address';
$aLang['button_address_book'] = 'Address Book';
$aLang['button_apply_coupon'] = 'apply coupon';
$aLang['button_back'] = 'Back';
$aLang['button_change_address'] = 'Change Address';
$aLang['button_checkout'] = 'Checkout';
$aLang['button_confirm_order'] = 'Confirm Order';
$aLang['button_continue'] = 'Continue';
$aLang['button_continue_shopping'] = 'Continue Shopping';
$aLang['button_delete'] = 'Delete';
$aLang['button_edit_account'] = 'Edit Account';
$aLang['button_history'] = 'Order History';
$aLang['button_login'] = 'Sign In';
$aLang['button_in_cart'] = 'In Cart';
$aLang['button_notifications'] = 'Notifications';
$aLang['button_quick_find'] = 'Quick Find';
$aLang['button_remove_notifications'] = 'Remove Notifications';
$aLang['button_reviews'] = 'Reviews';
$aLang['button_submit_a_review'] = 'Submit a review';
$aLang['button_send_message'] = 'Send Message';
$aLang['button_search'] = 'Search';
$aLang['button_start_shopping'] = 'Start Shopping';
$aLang['button_update'] = 'Update';
$aLang['button_update_cart'] = 'Update Cart';
$aLang['button_write_review'] = 'Write Review';
$aLang['button_add_quick'] = 'Add a Quickie!';
$aLang['image_wishlist_delete'] = 'delete';
$aLang['button_in_wishlist'] = 'Wishlist';
$aLang['button_add_wishlist'] = 'Wishlist';
$aLang['button_redeem_voucher'] = 'Redeem';
$aLang['button_callaction'] = 'Request a quote';
$aLang['button_view'] = 'View';

$aLang['button_register'] = 'Register';
$aLang['button_save_info'] = 'Save Info';

$aLang['icon_button_mail'] = 'E-mail';
$aLang['icon_button_movie'] = 'Movie';
$aLang['icon_button_pdf'] = 'PDF';
$aLang['icon_button_print'] = 'Print';
$aLang['icon_button_zoom'] = 'Zoom';


$aLang['icon_arrow_right'] = 'more';
$aLang['icon_cart'] = 'In Cart';
$aLang['icon_warning'] = 'Warning';

$aLang['text_sort_products'] = 'Sort products ';
$aLang['text_descendingly'] = 'descendingly';
$aLang['text_ascendingly'] = 'ascendingly';
$aLang['text_by'] = ' by ';

$aLang['text_review_by'] = 'by %s';
$aLang['text_review_word_count'] = '%s words';
$aLang['text_review_rating'] = 'Rating:';
$aLang['text_review_date_added'] = 'Date Added:';
$aLang['text_no_reviews'] = 'There are currently no product reviews.';
$aLang['text_no_new_products'] = 'There are currently no products.';
$aLang['text_unknown_tax_rate'] = 'Unknown tax rate';
$aLang['text_required'] = 'Required';


$aLang['warning_install_directory_exists'] = 'Warning: Installation directory exists at: ' . dirname(oos_server_get_var('SCRIPT_FILENAME')) . '/install. Please remove this directory for security reasons.';
$aLang['warning_config_file_writeable'] = 'Warning: I am able to write to the configuration file: ' . dirname(oos_server_get_var('SCRIPT_FILENAME')) . '/includes/configure.php. This is a potential security risk - please set the right user permissions on this file.';
$aLang['warning_download_directory_non_existent'] = 'Warning: The downloadable products directory does not exist: ' . OOS_DOWNLOAD_PATH . '. Downloadable products will not work until this directory is valid.';

$aLang['text_ccval_error_invalid_date'] = 'The expiry date entered for the credit card is invalid.<br>Please check the date and try again.';
$aLang['text_ccval_error_invalid_number'] = 'The credit card number entered is invalid.<br>Please check the number and try again.';
$aLang['text_ccval_error_unknown_card'] = 'The first four digits of the number entered are: %s<br>If that number is correct, we do not accept that type of credit card.<br>If it is wrong, please try again.';

$aLang['voucher_balance'] = 'Voucher Balance';
$aLang['gv_faq'] = 'Gift Voucher FAQ';
$aLang['error_redeemed_amount'] = 'Congratulations, you have redeemed ';
$aLang['error_no_redeem_code'] = 'You did not enter a redeem code.';  
$aLang['error_no_invalid_redeem_gv'] = 'Invalid Gift Voucher Code'; 
$aLang['table_heading_credit'] = 'Credits Available';
$aLang['gv_has_vouchera'] = 'You have funds in your Gift Voucher Account. If you want <br>
                         you can send those funds by';      
$aLang['gv_has_voucherb'] = 'to someone'; 
$aLang['entry_amount_check_error'] = 'You do not have enough funds to send this amount.'; 

$aLang['voucher_redeemed'] = 'Voucher Redeemed';
$aLang['cart_coupon'] = 'Coupon :';
$aLang['cart_coupon_info'] = 'more info';

$aLang['category_payment_details'] = 'You get your money by:';

$aLang['block_ticket_generate'] = 'Open Support Ticket';
$aLang['block_ticket_view'] = 'View Ticket';

$aLang['down_for_maintenance_text'] = 'Down for Maintenance ... Please try back later';
$aLang['down_for_maintenance_no_prices_display'] = 'Down for Maintenance';
$aLang['no_login_no_prices_display'] = 'Prices for dealer only';
$aLang['text_products_base_price'] = 'Base Price';

// Product Qty, List, Rebate Pricing and Savings
$aLang['products_see_qty_discounts'] = 'SEE: QTY DISCOUNTS';
$aLang['products_order_qty_text'] = 'Add Qty: ';
$aLang['products_order_qty_min_text'] = '<br>' . ' Min Qty: ';
$aLang['products_order_qty_min_text_info'] = 'Order Minumum is: ';
$aLang['products_order_qty_min_text_cart'] = 'Order Minimum is: ';
$aLang['products_order_qty_min_text_cart_short'] = ' Min Qty: ';
$aLang['products_order_qty_unit_text'] = ' in Units of: ';
$aLang['products_order_qty_unit_text_info'] = 'Order in Units of: ';
$aLang['products_order_qty_unit_text_cart'] = 'Order in Units of: ';
$aLang['products_order_qty_unit_text_cart_short'] = ' Units: ';
$aLang['error_products_quantity_order_min_text'] = '';
$aLang['error_products_quantity_invalid'] = 'Invalid Qty: ';
$aLang['error_products_quantity_order_units_text'] = '';
$aLang['error_products_units_invalid'] = 'Invalid Units: ';

// File upload ~/includes/classes/oos_upload.php
$aLang['error_destination_does_not_exist'] = 'Error: Destination does not exist.';
$aLang['error_destination_not_writeable'] = 'Error: Destination not writeable.';
$aLang['error_file_not_saved'] = 'Error: File upload not saved.';
$aLang['error_filetype_not_allowed'] = 'Error: File upload type not allowed.';
$aLang['success_file_saved_successfully'] = 'Success: File upload saved successfully.';
$aLang['warning_no_file_uploaded'] = 'Warning: No file uploaded.';
$aLang['warning_file_uploads_disabled'] = 'Warning: File uploads are disabled in the php.ini configuration file.';



$aLang['err404'] = '404 Error Message';
$aLang['err404_page_not_found'] = 'Page Not Found on';
$aLang['err404_sorry'] = 'We\'re sorry. The page you requested';
$aLang['err404_doesntexist'] = 'doesn\'t exist on';
$aLang['err404_mailed'] = '<strong>The details of this error have automatically been mailed to the webmaster.</strong>';
$aLang['err404_commonm'] = '<strong>Common Mistakes</strong>';
$aLang['err404_commonh'] = 'Here are the most common mistakes in accessing';
$aLang['err404_urlend'] = 'URL ends with';
$aLang['err404_allpages'] = 'all pages on';
$aLang['err404_endwith'] = 'end with';
$aLang['err404_uppercase'] = 'Using UPPER CASE CHARACTERS';
$aLang['err404_alllower'] = 'all names are in lower case only';

$aLang['text_info_csname'] = 'Your customer status is : ';
$aLang['text_info_csdiscount'] = 'You have on products a maximum discount of : ';
$aLang['text_info_csotdiscount'] = 'You have a total discount of  : ';
$aLang['text_info_csstaff'] = 'You have acces to ours quantity price discount.';
$aLang['text_info_cspay'] = 'You can not pay using following method : ';
$aLang['text_info_receive_mail_mode'] = 'I want to receive info in : ';
$aLang['text_info_show_price_no'] = 'You can not see price.';
$aLang['text_info_show_price_with_tax_yes'] = 'Price include Tax.';
$aLang['text_info_show_price_with_tax_no'] = 'Price without Tax.';
$aLang['entry_receive_mail_text'] = 'Text only';
$aLang['entry_receive_mail_html'] = 'HTML';
$aLang['entry_receive_mail_pdf'] = 'PDF';

$aLang['table_heading_price_unit'] = 'U.P.Net';
$aLang['table_heading_discount'] = 'Discount';
$aLang['table_heading_ot_discount'] = 'Global Discount';
$aLang['text_info_minimum_amount'] = 'Minimum order before discount';
$aLang['sub_title_ot_discount'] = 'Global Discount:';
$aLang['text_new_customer_introduction_newsletter'] = 'By subscribing to newsletter from ' .  STORE_NAME . ' you will stay informed of all news info.';
$aLang['text_new_customer_ip'] = 'This account has been created by this computer IP : ';
$aLang['text_customer_account_password_security'] = 'For you\'r own security we are not able to know or retrieve this password. If you forgot it, you can request a new one.';

$aLang['text_taxt_incl'] = 'incl. Tax';
$aLang['text_taxt_add'] = 'plus. Tax';
$aLang['tax_info_excl'] = 'exkl. Tax';
$aLang['text_shipping'] = 'excl. <a href="%s"><u>Shipping cost</u></a>.';

$aLang['price'] = 'Preis';
$aLang['price_from'] = 'from';

