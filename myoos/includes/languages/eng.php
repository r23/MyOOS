<?php
/**
   ----------------------------------------------------------------------
   $Id: eng.php,v 1.3 2007/06/12 16:57:18 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: english.php,v 1.107 2003/02/17 11:49:25 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */


/**
   ----------------------------------------------------------------------
   If you made a translation, please send to
      lang@oos-shop.de
   the translated file.
   ----------------------------------------------------------------------
 */

 /**
  * look in your $PATH_LOCALE/locale directory for available locales..
  * on RedHat try 'en_US'
  * on FreeBSD try 'en_US.ISO_8859-1'
  * on Windows try 'en', or 'English'
  */
  define('THE_LOCALE', 'en_US');
  define('DATE_FORMAT_SHORT', '%m/%d/%Y');
  define('DATE_FORMAT_LONG', '%A %d %B, %Y');
  define('DATE_FORMAT', 'm/d/Y');
  define('DATE_TIME_FORMAT', DATE_FORMAT_SHORT . ' %H:%M:%S');
  define('DATE_TIME_FORMAT_SHORT', '%H:%M:%S');


 /**
  * Return date in raw format
  * $date should be in format mm/dd/yyyy
  * raw date is in format YYYYMMDD, or DDMMYYYY
  *
  * @param  $date
  * @param  $reverse
  * @return string
  */
function oos_date_raw($date, $reverse = false)
{
    if ($reverse) {
        return substr((string) $date, 3, 2) . substr((string) $date, 0, 2) . substr((string) $date, 6, 4);
    } else {
        return substr((string) $date, 6, 4) . substr((string) $date, 0, 2) . substr((string) $date, 3, 2);
    }
}


// Global entries for the <html> tag
define('LANG', 'en');
define('DOB_FORMAT_STRING', 'mm/dd/yyyy');

$aLang = [
'danger'                => 'Oh snap! You got an error!',
'warning'               => 'Warning!',
'info'                   => 'Registration!',

// theme/system/_header.html
'header_title_create_account'  => 'Create an Account',
'header_title_my_account'      => 'My Account',
'header_title_cart_contents'   => 'Cart Contents',
'header_title_checkout'        => 'Checkout',
'header_title_top'             => 'Home',
'header_title_logoff'          => 'Log Off',
'header_title_login'           => 'Log In',
'header_title_contact'         => 'Kontakt',
'header_title_search'          => 'Suche',
'header_title_whats_new'       => 'What\'s New?',
'header_select_language'       => 'Your Language',
'header_select_currencies'     => 'Currency',
'sub_title_total'              => 'Total:',
'block_heading_specials'       => 'Specials',
'nav_toggle'                   => 'Open or close navigation',

// footer text in includes/oos_footer.php
'footer_text_requests_since'  => 'requests since',

// text for gender
'male'             => 'Male',
'female'           => 'Female',
'diverse'          => 'Diverse',
'male_address'     => 'Mr.',
'female_address'   => 'Ms.',
'diverse_address'  => '',
'email_greet_mr'   => 'Dear Mr. %s,',
'email_greet_ms'   => 'Dear Ms. %s,',
'email_greet_diverse' => 'Hi %s,',
'email_greet_none' => 'Hi,',


// text for date of birth example
'dob_format_string' => 'mm/dd/yyyy',

// search block text in tempalate/your theme/block/search.html
'block_search_text'             => 'Use keywords to find the product you are looking for.',
'block_search_advanced_search'  => 'Advanced Search',
'text_search'                   => 'search...',

// reviews block text in tempalate/your theme/block/reviews.html
'block_reviews_write_review'     => 'Write a review on this product!',
'block_reviews_no_reviews'       => 'There are currently no product reviews',
'block_reviews_text_of_5_stars'  => '%s of 5 Stars!',


// shopping_cart block text in tempalate/your theme/system/_header.html
'block_shopping_cart_empty'   => '0 items',
'sub_title_sub_total'         => 'Sub-Total',

// notifications block text in tempalate/your theme/block/products_notifications.html
'block_notifications_notify'  => 'Notify me of updates to <strong>%s</strong>',
'block_notifications_notify_remove'  => 'Do not notify me of updates to <strong>%s</strong>',

// wishlist
'button_wishlist'        => 'Wishlist',
'block_wishlist'         => 'Wishlist',
'block_wishlist_empty'   => 'You have no items on your Wishlist',

// manufacturer block text in tempalate/your theme/block/
'block_manufacturer_info_homepage'        => '%s Homepage',
'block_manufacturer_info_other_products'  => 'Other products',

// information block text in tempalate/your theme/block/information.html
'block_information_imprint'       => 'Imprint',
'block_information_privacy'       => 'Privacy Notice',
'block_information_conditions'    => 'Conditions of Use',
'block_information_shipping'      => 'Shipping & Returns',
'block_information_gv'            => 'Gift Voucher FAQ',
'block_cookie_settings'              => 'Cookie settings',

// login
'entry_email_address'             => 'E-Mail-Address',
'entry_password'                  => 'Password',
'text_password_info'              => 'Password forgotten?',
'button_login'                    => 'Login',
'login_block_new_customer'        => 'New Customer',
'login_block_account_edit'        => 'Edit Account Info.',
'login_block_account_history'     => 'My Account History',
'login_block_order_history'       => 'My Orders',
'login_block_address_book'        => 'My Address Book',
'login_block_product_notifications'   => 'Product Notifications',
'login_block_my_account'              => 'General Information',
'login_block_logoff'                  => 'Log Off',
'login_block_no_account_yet'         => 'No Account Yet?',
'login_block_book_now'              => 'Book now %s',
'text_password_forgotten'           => 'Lost your password?',
'link_password_forgotten'           => 'Click <u>here</u> to recover.',
'text_please_enter_a_password'        => 'Please enter a password',
'text_please_provide_email_address' => 'Please provide email address',

//offcanvas-cart
'text_clear_cart'                 => 'Clear cart',
'text_item_successfull'            => 'The item was successfully added to the shopping cart.',


// checkout procedure text
'checkout_bar_delivery'             => 'Delivery Information',
'checkout_bar_payment'              => 'Payment Information',
'checkout_bar_confirmation'         => 'Confirmation',
'checkout_bar_finished'             => 'Finished!',

// pull down default text
'pull_down_default'           => 'Please Select',
'type_below'                  => 'Type Below',

//newsletter
'block_newsletter_subscribe'      => 'subscribe to our weekly <strong>newsletter</strong>',
'block_newsletter_placeholder'    => 'Email your email...',
'block_newsletter_unsubscribe'    => 'Unsubscribe',
'error_email_address'             => '<strong>Your e-mail address:</strong> None or invalid input!',
'newsletter_email_info'           => 'Your e-mail adress has been registered in our system.<br />An e-mail with a confirmation link has been send out. Click the link in order to complete registration!',
'newsletter_email_subject'        => 'Your newsletter account',
'newsletter_notice'               => 'You may unsubscribe at any moment. For that purpose, please find our contact info in the legal notice.',
'entry_newsletter_no'             => 'No',
'entry_newsletter_yes'            => 'Yes',
'text_email_active'               => 'Your e-mail address has successfully been registered for the newsletter!',
'text_email_active_error'         => 'An error occured, your e-mail address has not been registered for the newsletter!',
'text_email_del'                  => 'Your e-mail address was deleted successfully from our newsletter-database.',
'text_email_del_error'            => 'An Error occured, your e-mail address has not been removed from our database!',


// footer
'get_in_touch_with_us'             => 'Get in touch with us',
'header_title_service'             => 'Shop service',
'block_service_specials'           => 'Specials',
'block_service_sitemap'            => 'Sitemap',
'block_service_advanced_search'    => 'Advanced Search',
'block_service_reviews'            => 'Reviews',
'block_service_shopping_cart'      => 'Cart Contents',
'block_service_contact'            => 'Contact',

'page_order_history'                  => 'Order History',
'page_products_new'                => 'New Products',
'page_specials'                    => 'Specials',
'page_blog'                          => 'Blog',
'page_phpb3'                          => 'Support Forum',

'review_text'       => 'The \'Review Text\' must have at least ' . REVIEW_TEXT_MIN_LENGTH . ' characters.',
'review_rating'     => 'You must rate the product for your review.',
'review_headline'   => 'The \'Headline\' must have at least 10 characters.',
'form_error'        => '<strong>Sorry!</strong> You need to complete all mandatory (*) fields!',


// javascript messages
'js_error'          => 'Errors have occured during the process of your form!\nPlease make the following corrections:\n\n',
'js_gender'         => '* The \'Gender\' value must be chosen.\n',
'js_first_name'     => '* The \'First Name\' entry must have at least ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' characters.\n',
'js_last_name'      => '* The \'Last Name\' entry must have at least ' . ENTRY_LAST_NAME_MIN_LENGTH . ' characters.\n',
'js_dob'            => '* The \'Date of Birth\' entry must be in the format: xx/xx/xxxx (month/day/year).\n',
'js_email_address'  => '* The \'E-Mail-Address\' entry must have at least ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' characters.\n',
'js_address'        => '* The \'Street Address\' entry must have at least ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' characters.\n',
'js_post_code'      => '* The \'Post Code\' entry must have at least ' . ENTRY_POSTCODE_MIN_LENGTH . ' characters.\n',
'js_city'           => '* The \'City\' entry must have at least ' . ENTRY_CITY_MIN_LENGTH . ' characters.\n',
'js_state'          => '* The \'State\' entry must be selected.\n',
'js_country'        => '* The \'Country\' entry must be selected.\n',
'js_password'       => '* The \'Password\' and \'Confirmation\' entries must match and have at least ' . ENTRY_PASSWORD_MIN_LENGTH . ' characters.\n',

'js_error_no_payment_module_selected' => '* Please select a payment method for your order.\n',
'js_error_submitted'                  => 'This form has already been submitted. Please press Ok and wait for this process to be completed.',

'error_no_payment_module_selected'    => 'Please select a payment method for your order.',

'category_company'      => 'Company Details',
'category_personal'     => 'Your Personal Details',
'category_address'      => 'Your Address',
'category_contact'      => 'Your Contact Information',
'category_options'      => 'Options',
'category_password'     => 'Your Password',
'entry_company'         => 'Company Name',
'entry_company_error'    => '',
'entry_company_text'    => '',
'entry_owner'           => 'Owner',
'entry_owner_error'     => '',
'entry_owner_text'      => '',
'entry_vat_id'           => 'VAT ID',
'entry_vat_id_error'      => 'The chosen VatID is not valid or not proofable at this moment! Please fill in a valid ID or leave the field empty.',
'entry_vat_id_text'       => '* for Germany and EU-Countries only',
'entry_gender'           => 'Gender',
'entry_gender_error'     => 'Please select your Gender.',
'entry_first_name'       => 'First Name',
'entry_first_name_error' => 'Your First Name must contain a minimum of ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' characters.',
'entry_last_name'        => 'Last Name',
'entry_last_name_error'  => 'Your Last Name must contain a minimum of ' . ENTRY_LAST_NAME_MIN_LENGTH . ' characters.',
'entry_date_of_birth'    => 'Date of Birth',
'entry_date_of_birth_error'         => 'Your Date of Birth must be in this format: MM/DD/YYYY (eg 05/21/1970)',
'entry_date_of_birth_text'          => '(eg. 05/21/1970)',
'entry_email_address_error'         => 'Your E-Mail-Address must contain a minimum of ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' characters.',
'entry_email_address_check_error'   => 'Your E-Mail-Address does not appear to be valid - please make any necessary corrections.',
'entry_email_address_error_exists'  => 'Your E-Mail-Address already exists in our records - please log in with the e-mail address or create an account with a different address.',

'entry_street_address'           => 'Street Address',
'entry_street_address_error'     => 'Your Street Address must contain a minimum of ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' characters.',
'entry_post_code'                => 'Post Code',
'entry_post_code_error'          => 'Your Post Code must contain a minimum of ' . ENTRY_POSTCODE_MIN_LENGTH . ' characters.',
'entry_city'                     => 'City',
'entry_city_error'               => 'Your City must contain a minimum of ' . ENTRY_CITY_MIN_LENGTH . ' characters.',
'entry_state'                    => 'State/Province',
'entry_state_error'              => 'Your State must contain a minimum of ' . ENTRY_STATE_MIN_LENGTH . ' characters.',
'entry_state_error_select'       => 'Please select a state from the States pull down menu.',

'entry_country'                  => 'Country',
'entry_country_error'            => 'You must select a country from the Countries pull down menu.',
'entry_telephone_number'         => 'Telephone Number',
'entry_newsletter'               => 'Newsletter',
'entry_newsletter_text'          => '',
'entry_newsletter_yes'           => 'Subscribed',
'entry_newsletter_no'            => 'Unsubscribed',
'entry_newsletter_error'         => '',
'entry_password_confirmation'    => 'Password Confirmation:',
'entry_password_error'           => 'Your Password must contain a minimum of ' . ENTRY_PASSWORD_MIN_LENGTH . ' characters.',
'entry_password_error_not_matching'           => 'The Password Confirmation must match your Password.',

'password_hidden'           => '--HIDDEN--',
'entry_info_text'           => 'require_onced',
'entry_subject'             => 'Subject',

'entry_agree_error'     => 'Please accept our <strong>Terms and Conditions</strong> and <strong>Privacy Policy</strong>!',
'agree'                 => 'By creating an account, you agree to the <a href="%s" target="_blank" rel="noopener"><strong>Terms and Conditions</strong></a> and <a href="%s" target="_blank" rel="noopener"><strong>Privacy Policy</strong></a>.',
'newsletter_agree'      => 'Yes, I wish to receive occasional emails about special offers, new products and exclusive promotions. I can cancel my subscription at any time.',

'success_address_book_entry_deleted'    => 'The selected entry has been deleted successfully.',
'warning_primary_address_deletion'      => 'The standard postal address can not be deleted.',
'success_address_book_entry_updated'    => 'Your address book has been updated sucessfully!',
'error_nonexisting_address_book_entry'  => 'This address book entry is not available.',
'error_address_book_full'              => 'Your addressbook is full. In order to add new addresses, please erase previous ones first.',


// constants for use in oos_prev_next_display function
'text_result_page'                     => 'Result Pages:',
'text_display_number_of_products'      => 'Displaying <strong>%d</strong> to <strong>%d</strong> (of <strong>%d</strong> products)',
'text_display_number_of_orders'        => 'Displaying <strong>%d</strong> to <strong>%d</strong> (of <strong>%d</strong> orders)',
'text_display_number_of_reviews'       => 'Displaying <strong>%d</strong> to <strong>%d</strong> (of <strong>%d</strong> reviews)',
'text_display_number_of_products_new'  => 'Displaying <strong>%d</strong> to <strong>%d</strong> (of <strong>%d</strong> new products)',
'text_display_number_of_specials'      => 'Displaying <strong>%d</strong> to <strong>%d</strong> (of <strong>%d</strong> specials)',
'text_display_number_of_wishlist'      => 'Displaying <strong>%d</strong> to <strong>%d</strong> (of <strong>%d</strong> products)',

'prevnext_title_first_page'            => 'First Page',
'prevnext_title_previous_page'         => 'Previous Page',
'prevnext_title_next_page'             => 'Next Page',
'prevnext_title_last_page'             => 'Last Page',
'prevnext_title_page_no'               => 'Page %d',
'prevnext_title_prev_set_of_no_page'   => 'Previous Set of %d Pages',
'prevnext_title_next_set_of_no_page'   => 'Next Set of %d Pages',
'prevnext_button_first'                => '&lt;&lt;FIRST',
'prevnext_button_prev'                 => '&lt;&lt;&nbsp;Prev',
'prevnext_button_next'                 => 'Next&nbsp;&gt;&gt;',
'prevnext_button_last'                 => 'LAST&gt;&gt;',
'prevnext_slider_previous'             => 'Previous',
'prevnext_slider_next'                 => 'Next',


'button_account'                     => 'Profil',
'button_add_address'                 => 'Add Address',
'button_address_book'                => 'Address Book',
'button_apply_coupon'                => 'apply coupon',
'button_back'                        => 'Back',
'button_buy_it_again'                => 'Buy it Again',
'button_calculate_shipping'             => 'Calculate shipping',
'button_change_address'              => 'Change Address',
'button_checkout'                    => 'Checkout',
'button_clear_cart'                  => 'Clear Cart',
'button_confirm_order'               => 'Order now at the price stated',
'button_continue'                    => 'Continue',
'button_continue_shopping'  => 'Continue Shopping',
'button_delete'             => 'Delete',
'button_edit_account'       => 'Edit Account',
'button_history'            => 'Order History',
'button_login'              => 'Sign In',
'button_in_cart'            => 'In Cart',
'button_notifications'      => 'Notifications',
'button_set_price_alert'    => 'Set price alert',
'button_place_price_alert'  => 'Place price alert',
'button_save_price_alert'   => 'Save price alert',
'button_quick_find'         => 'Quick Find',
'button_remove_notifications'  => 'Remove Notifications',
'button_reviews'               => 'Reviews',
'button_submit_a_review'       => 'Submit a review',
'button_send'                  => 'Send',
'button_send_message'          => 'Send Message',
'button_search'                => 'Start a search',
'button_start_shopping'        => 'Start Shopping',
'button_update'                => 'Update',
'button_update_cart'           => 'Update Cart',
'button_write_review'          => 'Write a Product Review',
'button_write_a_product_review'     => 'Write a Product Review',
'button_write_first_review'    => 'Write the first customer opinion',
'button_add_quick'             => 'Add a Quickie!',
'image_wishlist_delete'        => 'delete',
'button_add_wishlist'          => 'Wishlist',
'button_redeem_voucher'        => 'Redeem',
'button_callaction'            => 'Request a quote',
'button_view'                  => 'View',


'button_register'            => 'Register',
'button_further_than_guest'  => 'Further than Guest',
'button_save_info'           => 'Save Info',

'button_new_2fa'              => 'Set up',
'button_not_now'              => 'Not now',
'button_activate'             => 'activate',
'button_deactivate'           => 'deactivate',


'button_hp_buy'  => 'Add to cart',
'button_hp_more' => 'Show more',


'icon_button_mail'           => 'E-mail',
'icon_button_movie'          => 'Movie',
'icon_button_pdf'            => 'PDF',
'icon_button_print'          => 'Print',
'icon_button_zoom'           => 'Zoom',


'icon_arrow_right'           => 'more',
'icon_cart'                  => 'In Cart',
'icon_warning'               => 'Warning',

'text_sort_products'        => 'Sort products ',
'text_descendingly'         => 'descendingly',
'text_ascendingly'          => 'ascendingly',
'text_by'                   => ' by ',

'text_review_by'           => 'by %s',
'text_review_word_count'   => '%s words',
'text_review_rating'       => 'Rating:',
'text_review_date_added'   => 'Date Added:',
'text_no_reviews'          => 'There are currently no product reviews.',
'text_no_new_products'     => 'There are currently no products.',
'text_unknown_tax_rate'    => 'Unknown tax rate',
'text_required'            => 'Required',
'text_more'                => 'more...',
'text_new'                 => 'NEW',
'text_sale'                => 'SALE',
'text_categories'            => 'CATEGORIES',

'text_no_longer_available'    => 'No longer available',
'text_replacement_product'    => 'There is a replacement product',

'warning_install_directory_exists'  => 'Warning: Installation directory exists at: ' . dirname((string) oos_server_get_var('SCRIPT_FILENAME')) . '/install. Please remove this directory for security reasons.',
'warning_config_file_writeable'     => 'Warning: I am able to write to the configuration file: ' . dirname((string) oos_server_get_var('SCRIPT_FILENAME')) . '/includes/configure.php. This is a potential security risk - please set the right user permissions on this file.',
'warning_download_directory_non_existent'  => 'Warning: The downloadable products directory does not exist: ' . OOS_DOWNLOAD_PATH . '. Downloadable products will not work until this directory is valid.',

'info_login_for_wichlist'             => 'Would you like to save your articles permanently and use all the functions of the notepad? Then please log in and we will save your articles on your notepad in your customer account.',

'text_ccval_error_invalid_date'    => 'The expiry date entered for the credit card is invalid.<br>Please check the date and try again.',
'text_ccval_error_invalid_number'  => 'The credit card number entered is invalid.<br>Please check the number and try again.',
'text_ccval_error_unknown_card'    => 'The first four digits of the number entered are: %s<br>If that number is correct, we do not accept that type of credit card.<br>If it is wrong, please try again.',

'voucher_balance'   => 'Voucher Balance',
'gv_faq'            => 'Gift Voucher FAQ',
'error_redeemed_amount'           => 'Congratulations, you have redeemed ',
'error_no_redeem_code'            => 'You did not enter a redeem code.',
'error_no_invalid_redeem_gv'      => 'Invalid Gift Voucher Code',
'table_heading_credit'            => 'Credits Available',
'gv_has_vouchera'                 => 'You have funds in your Gift Voucher Account. If you want <br>
                         you can send those funds by',
'gv_has_voucherb'                 => 'to someone',
'entry_amount_check_error'        => 'You do not have enough funds to send this amount.',

'voucher_redeemed'               => 'Voucher Redeemed',
'cart_coupon'                    => 'Coupon :',
'cart_coupon_info'               => 'more info',

'category_payment_details'       => 'You get your money by:',


'down_for_maintenance_text'                => 'Down for Maintenance ... Please try back later',
'down_for_maintenance_no_prices_display'   => 'Down for Maintenance',
'no_login_no_prices_display'               => 'Prices for dealer only',
'text_products_base_price'                 => 'Base Price',

// Product Qty, List, Rebate Pricing and Savings
'products_see_qty_discounts'                 => 'SEE: QTY DISCOUNTS',
'products_order_qty_text'                    => 'Add Qty: ',
'products_order_qty_min_text'                => ' Min Qty: ',
'products_order_qty_min_text_info'           => 'Order Minumum is: ',
'products_order_qty_min_text_cart'           => 'Order Minimum is: ',
'products_order_qty_min_text_cart_short'     => ' Min Qty: ',
'products_order_qty_unit_text'               => ' in Units of: ',
'products_order_qty_unit_text_info'           => 'Order in Units of: ',
'products_order_qty_unit_text_cart'           => 'Order in Units of: ',
'products_order_qty_unit_text_cart_short'    => ' Units: ',
'error_products_quantity_order_min_text'     => '',
'error_products_quantity_invalid'            => 'Invalid Qty: ',
'error_products_quantity_order_units_text'    => '',
'error_products_units_invalid'             => 'Invalid Units: ',
'error_product_has_attributes'                => 'This product has variations. Please select the variation you want.',
'error_product_information_obligation'         => 'With this product, you have the option of taking back your old device free of charge. You can find more information here in the product detials.',
'error_product_information_used_goods'         => 'Please confirm that you have received the used goods (B-goods) notes.',



// File upload ~/includes/classes/oos_upload.php
'error_destination_does_not_exist'   => 'Error: Destination does not exist.',
'error_destination_not_writeable'    => 'Error: Destination not writeable.',
'error_file_not_saved'               => 'Error: File upload not saved.',
'error_filetype_not_allowed'         => 'Error: File upload type not allowed.',
'success_file_saved_successfully'    => 'Success: File upload saved successfully.',
'warning_no_file_uploaded'           => 'Warning: No file uploaded.',
'warning_file_uploads_disabled'      => 'Warning: File uploads are disabled in the php.ini configuration file.',



'err404'                  => '404 Error Message',
'err404_page_not_found'   => 'Page Not Found on',
'err404_sorry'            => 'We\'re sorry. The page you requested',
'err404_doesntexist'      => 'doesn\'t exist on',
'err404_mailed'           => '<strong>The details of this error have automatically been mailed to the webmaster.</strong>',
'err404_commonm'          => '<strong>Common Mistakes</strong>',
'err404_commonh'          => 'Here are the most common mistakes in accessing',
'err404_urlend'           => 'URL ends with',
'err404_allpages'         => 'all pages on',
'err404_endwith'          => 'end with',
'err404_uppercase'        => 'Using UPPER CASE CHARACTERS',
'err404_alllower'         => 'all names are in lower case only',

'text_info_csname'        => 'Your customer status is : ',
'text_info_csdiscount'    => 'You have on products a maximum discount of : ',
'text_info_csotdiscount'  => 'You have a total discount of  : ',
'text_info_csstaff'       => 'You have acces to ours quantity price discount.',
'text_info_cspay'         => 'You can not pay using following method : ',
'text_info_receive_mail_mode'  => 'I want to receive info in : ',
'text_info_show_price_no'      => 'You can not see price.',
'text_info_show_price_with_tax_yes'  => 'Price include Tax.',
'text_info_show_price_with_tax_no'   => 'Price without Tax.',
'entry_receive_mail_text'            => 'Text only',
'entry_receive_mail_html'            => 'HTML',
'entry_receive_mail_pdf'             => 'PDF',

'table_heading_price_unit'     => 'U.P.Net',
'table_heading_discount'       => 'Discount',
'table_heading_ot_discount'    => 'Global Discount',
'text_info_minimum_amount'     => 'Minimum order before discount',
'sub_title_ot_discount'        => 'Global Discount:',
'text_new_customer_introduction_newsletter'  => 'By subscribing to newsletter from ' .  STORE_NAME . ' you will stay informed of all news info.',
'text_new_customer_ip'                       => 'This account has been created by this computer IP : ',
'text_customer_account_password_security'    => 'For you\'r own security we are not able to know or retrieve this password. If you forgot it, you can request a new one.',

'text_tax_incl'          => 'Price quotations incl. Tax.',
'tax_incl_available_from'   => 'incl. %s Tax.',
'text_tax_add'           => 'plus. Tax',
'tax_info_excl'           => 'excl. Tax',
'text_shipping'           => 'excl. <a href="%s">and plus service and shipping costs</a>.',
'total_info'              => 'all data in %s, incl. VAT',



'text_excl_tax_plus_shipping'   => 'excl. tax, plus  <a href="%s">shipping</a>',
'text_incl_tax_plus_shipping'   => 'incl. tax, plus  <a href="%s">shipping</a>',


'price'                => 'Price',
'price_from'           => 'from',

'price_reduced_from'   => 'Reduced from',
'price_rrp'            => 'RRP',

'only_until'           => 'Only until %s!',
'text_content'         => 'Content',
'text_base_price'    => 'Base price',


'in_stock'             => 'In Stock',
'out_of_stock'         => 'available again',
'available_from'       => 'available from approx. %s',


'text_info_minimum_order_value' => 'Please note the minimum order value of %s',
'warning_minimum_order_value' => 'The minimum order value of %s has not been reached yet. Therefore, no order is currently possible with this shopping cart.',


];
