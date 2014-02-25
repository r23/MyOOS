<?php
/* ----------------------------------------------------------------------
   $Id: eng.php 453 2013-06-28 16:03:28Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
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


//text in oos_temp/templates/your theme/system/user_navigation.html
$aLang['header_title_create_account'] = 'Create an Account';
$aLang['header_title_my_account'] = 'My Account';
$aLang['header_title_cart_contents'] = 'Cart Contents';
$aLang['header_title_checkout'] = 'Checkout';
$aLang['header_title_top'] = 'Top';
$aLang['header_title_catalog'] = 'Catalog';
$aLang['header_title_logoff'] = 'Log Off';
$aLang['header_title_login'] = 'Log In';
$aLang['header_title_whats_new'] = 'What\'s New?';

$aLang['block_heading_specials'] = 'Specials';

// footer text in includes/oos_footer.php
$aLang['footer_text_requests_since'] = 'requests since';

// text for gender
$aLang['male'] = 'Male';
$aLang['female'] = 'Female';
$aLang['male_address'] = 'Mr.';
$aLang['female_address'] = 'Ms.';

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

// shopping_cart block text in tempalate/your theme/block/shopping_cart.html
$aLang['block_shopping_cart_empty'] = '0 items';

// notifications block text in tempalate/your theme/block/products_notifications.html
$aLang['block_notifications_notify'] = 'Notify me of updates to <b>%s</b>';
$aLang['block_notifications_notify_remove'] = 'Do not notify me of updates to <b>%s</b>';

// wishlist block text in tempalate/your theme/block/wishlist.html
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
$aLang['entry_email_address'] = 'E-Mail Address:';
$aLang['entry_password'] = 'Password:';
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

// tell a friend block text in tempalate/your theme/block/tell_a_friend.html
$aLang['block_tell_a_friend_text'] = 'Tell someone you know about this product.';

// checkout procedure text
$aLang['checkout_bar_delivery'] = 'Delivery Information';
$aLang['checkout_bar_payment'] = 'Payment Information';
$aLang['checkout_bar_confirmation'] = 'Confirmation';
$aLang['checkout_bar_finished'] = 'Finished!';

// pull down default text
$aLang['pull_down_default'] = 'Please Select';
$aLang['type_below'] = 'Type Below';

//newsletter
$aLang['block_newsletters_subscribe'] = 'Subscribe';
$aLang['block_newsletters_unsubscribe'] = 'Unsubscribe';

//myworld
$aLang['text_date_account_created'] = 'Account Created:';
$aLang['text_yourstore'] = 'Your Participation';
$aLang['edit_yourimage'] = 'Your Image';

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

// javascript messages
$aLang['js_error'] = 'Errors have occured during the process of your form!\nPlease make the following corrections:\n\n';

$aLang['js_review_text'] = '* The \'Review Text\' must have at least ' . REVIEW_TEXT_MIN_LENGTH . ' characters.\n';
$aLang['js_review_rating'] = '* You must rate the product for your review.\n';

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
$aLang['js_telephone'] = '* The \'Telephone Number\' entry must have at least ' . ENTRY_TELEPHONE_MIN_LENGTH . ' characters.\n';
$aLang['js_password'] = '* The \'Password\' and \'Confirmation\' entries must match and have at least ' . ENTRY_PASSWORD_MIN_LENGTH . ' characters.\n';

$aLang['js_error_no_payment_module_selected'] = '* Please select a payment method for your order.\n';
$aLang['js_error_submitted'] = 'This form has already been submitted. Please press Ok and wait for this process to be completed.';

$aLang['error_no_payment_module_selected'] = 'Please select a payment method for your order.';
$aLang['error_conditions_not_accepted'] = 'If you do not accept our conditions, we cannot process your order!';

$aLang['category_company'] = 'Company Details';
$aLang['category_personal'] = 'Your Personal Details';
$aLang['category_address'] = 'Your Address';
$aLang['category_contact'] = 'Your Contact Information';
$aLang['category_options'] = 'Options';
$aLang['category_password'] = 'Your Password';
$aLang['entry_company'] = 'Company Name:';
$aLang['entry_company_error'] = '';
$aLang['entry_company_text'] = '';
$aLang['entry_owner'] = 'Owner';
$aLang['entry_owner_error'] = '';
$aLang['entry_owner_text'] = '';
$aLang['entry_vat_id'] = 'VAT ID';
$aLang['entry_vat_id_error'] = 'The chosen VatID is not valid or not proofable at this moment! Please fill in a valid ID or leave the field empty.';
$aLang['entry_vat_id_text'] = '* for Germany and EU-Countries only';
$aLang['entry_number'] = 'Customer number';
$aLang['entry_number_error'] = '';
$aLang['entry_number_text'] = '';
$aLang['entry_gender'] = 'Gender:';
$aLang['entry_gender_error'] = '&nbsp;<small><font color="#AABBDD">require_onced</font></small>';
$aLang['entry_first_name'] = 'First Name:';
$aLang['entry_first_name_error'] = '&nbsp;<small><font color="#FF0000">min ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' chars</font></small>';
$aLang['entry_first_name_text'] = '&nbsp;<small><font color="#AABBDD">require_onced</font></small>';
$aLang['entry_last_name'] = 'Last Name:';
$aLang['entry_last_name_error'] = '&nbsp;<small><font color="#FF0000">min ' . ENTRY_LAST_NAME_MIN_LENGTH . ' chars</font></small>';
$aLang['entry_date_of_birth'] = 'Date of Birth:';
$aLang['entry_date_of_birth_error'] = '&nbsp;<small><font color="#FF0000">(eg. 05/21/1970)</font></small>';
$aLang['entry_date_of_birth_text'] = '(eg. 05/21/1970)';
$aLang['entry_email_address'] = 'E-Mail Address:';
$aLang['entry_email_address_error'] = '&nbsp;<small><font color="#FF0000">min ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' chars</font></small>';
$aLang['entry_email_address_check_error'] = '&nbsp;<small><font color="#FF0000">Your email address doesn\'t appear to be valid!</font></small>';
$aLang['entry_email_address_error_exists'] = '&nbsp;<small><font color="#FF0000">email address already exists!</font></small>';

$aLang['entry_street_address'] = 'Street Address:';
$aLang['entry_street_address_error'] = '&nbsp;<small><font color="#FF0000">min ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' chars</font></small>';
$aLang['entry_suburb'] = 'Suburb:';
$aLang['entry_suburb_error'] = '';
$aLang['entry_suburb_text'] = '';
$aLang['entry_post_code'] = 'Post Code:';
$aLang['entry_post_code_error'] = '&nbsp;<small><font color="#FF0000">min ' . ENTRY_POSTCODE_MIN_LENGTH . ' chars</font></small>';
$aLang['entry_city'] = 'City:';
$aLang['entry_city_error'] = '&nbsp;<small><font color="#FF0000">min ' . ENTRY_CITY_MIN_LENGTH . ' chars</font></small>';
$aLang['entry_state'] = 'State/Province:';
$aLang['entry_state_error'] = '&nbsp;<small><font color="#FF0000">require_onced</font></small>';
$aLang['entry_country'] = 'Country:';
$aLang['entry_country_error'] = '';
$aLang['entry_country_text'] = '&nbsp;<small><font color="#AABBDD">require_onced</font></small>';
$aLang['entry_telephone_number'] = 'Telephone Number:';
$aLang['entry_telephone_number_error'] = '&nbsp;<small><font color="#FF0000">min ' . ENTRY_TELEPHONE_MIN_LENGTH . ' chars</font></small>';
$aLang['entry_fax_number'] = 'Fax Number:';
$aLang['entry_fax_number_error'] = '';
$aLang['entry_fax_number_text'] = '';
$aLang['entry_newsletter'] = 'Newsletter:';
$aLang['entry_newsletter_text'] = '';
$aLang['entry_newsletter_yes'] = 'Subscribed';
$aLang['entry_newsletter_no'] = 'Unsubscribed';
$aLang['entry_newsletter_error'] = '';
$aLang['entry_password'] = 'Password:';
$aLang['entry_password_confirmation'] = 'Password Confirmation:';
$aLang['entry_password_confirmation_text'] = '&nbsp;<small><font color="#AABBDD">require_onced</font></small>';
$aLang['entry_password_error'] = '&nbsp;<small><font color="#FF0000">min ' . ENTRY_PASSWORD_MIN_LENGTH . ' chars</font></small>';
$aLang['password_hidden'] = '--HIDDEN--';
$aLang['entry_info_text'] = 'require_onced';


// constants for use in oos_prev_next_display function
$aLang['text_result_page'] = 'Result Pages:';
$aLang['text_display_number_of_products'] = 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> products)';
$aLang['text_display_number_of_orders'] = 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> orders)';
$aLang['text_display_number_of_reviews'] = 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> reviews)';
$aLang['text_display_number_of_products_new'] = 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> new products)';
$aLang['text_display_number_of_specials'] = 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> specials)';
$aLang['text_display_number_of_wishlist'] = 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> products)';

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

$aLang['button_add_address'] = 'Add Address';
$aLang['button_address_book'] = 'Address Book';
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
$aLang['button_search'] = 'Search';
$aLang['button_tell_a_friend'] = 'Tell a Friend';
$aLang['button_update'] = 'Update';
$aLang['button_update_cart'] = 'Update Cart';
$aLang['button_write_review'] = 'Write Review';
$aLang['button_add_quick'] = 'Add a Quickie!';
$aLang['image_wishlist_delete'] = 'delete';
$aLang['button_in_wishlist'] = 'Wishlist';
$aLang['button_add_wishlist'] = 'Wishlist';
$aLang['button_redeem_voucher'] = 'Redeem';
$aLang['button_callaction'] = 'Request a quote';

$aLang['icon_button_mail'] = 'E-mail';
$aLang['icon_button_movie'] = 'Movie';
$aLang['icon_button_pdf'] = 'PDF';
$aLang['icon_button_print'] = 'Print';
$aLang['icon_button_zoom'] = 'Zoom';


$aLang['icon_arrow_right'] = 'more';
$aLang['icon_cart'] = 'In Cart';
$aLang['icon_warning'] = 'Warning';

$aLang['text_greeting_personal'] = 'Welcome back <span class="greetUser">%s!</span> Would you like to see which <a href="%s"><u>new products</u></a> are available to purchase?';
$aLang['text_greeting_guest'] = 'Welcome <span class="greetUser">Guest!</span> Would you like to <a href="%s"><u>log yourself in</u></a>? Or would you prefer to <a href="%s"><u>create an account</u></a>?';

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
$aLang['error_oos_mail'] = '<small>OOS ERROR:</small> Cannot send the email through the specified SMTP server. Please check your php.ini setting and correct the SMTP server if necessary.';

$aLang['warning_install_directory_exists'] = 'Warning: Installation directory exists at: ' . dirname(oos_server_get_var('SCRIPT_FILENAME')) . '/install. Please remove this directory for security reasons.';
$aLang['warning_config_file_writeable'] = 'Warning: I am able to write to the configuration file: ' . dirname(oos_server_get_var('SCRIPT_FILENAME')) . '/includes/configure.php. This is a potential security risk - please set the right user permissions on this file.';
$aLang['warning_session_auto_start'] = 'Warning: session.auto_start is enabled - please disable this php feature in php.ini and restart the web server.';
$aLang['warning_download_directory_non_existent'] = 'Warning: The downloadable products directory does not exist: ' . OOS_DOWNLOAD_PATH . '. Downloadable products will not work until this directory is valid.';
$aLang['warning_session_directory_non_existent'] = 'Warning: The sessions directory does not exist: ' . oos_session_save_path() . '. Sessions will not work until this directory is created.';
$aLang['warning_session_directory_not_writeable'] = 'Warning: I am not able to write to the sessions directory: ' . oos_session_save_path() . '. Sessions will not work until the right user permissions are set.';

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
$aLang['gv_send_to_friend'] = 'Send Gift Voucher';

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


// 404 Email Error Report
$aLang['error404_email_subject'] = '404 Error Report';
$aLang['error404_email_header'] = '404 Error Message';
$aLang['error404_email_text'] = 'A 404 error was encountered by';
$aLang['error404_email_date'] = 'Date:';
$aLang['error404_email_uri'] = 'The URI which generated the error is:';
$aLang['error404_email_ref'] = 'The referring page was:';

$aLang['err404'] = '404 Error Message';
$aLang['err404_page_not_found'] = 'Page Not Found on';
$aLang['err404_sorry'] = 'We\'re sorry. The page you requested';
$aLang['err404_doesntexist'] = 'doesn\'t exist on';
$aLang['err404_mailed'] = '<b>The details of this error have automatically been mailed to the webmaster.</b>';
$aLang['err404_commonm'] = '<b>Common Mistakes</b>';
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
$aLang['text_login_need_upgrade_csnewsletter'] = '<font color="#ff0000"><b>NOTE:</b></font>You have already subscribed to an account for &quot;Newsletter &quot;. You need to upgade this account to be able to buy.';

// use TimeBasedGreeting
$aLang['good_morning'] = 'Good morning!';
$aLang['good_afternoon'] = 'Good afternoon!';
$aLang['good_evening'] = 'Good evening!';

$aLang['text_taxt_incl'] = 'incl. Tax';
$aLang['text_taxt_add'] = 'plus. Tax';
$aLang['tax_info_excl'] = 'exkl. Tax';
$aLang['text_shipping'] = 'excl. <a href="%s"><u>Shipping cost</u></a>.';

$aLang['price'] = 'Preis';
$aLang['price_from'] = 'from';
$aLang['price_info'] = 'Alle Preise pro Stück in &euro; inkl. der gesetzlichen Mehrwertsteuer, zzgl. <a href="' . oos_href_link($aContents['information'], 'information_id=1') . '">Versandkostenpauschale</a>.';


