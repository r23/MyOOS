<?php
/* ----------------------------------------------------------------------
   $Id: rus.php,v 1.3 2007/06/12 16:57:18 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
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
  * on RedHat try 'ru_RU'
  * on FreeBSD try 'ru_RU.CP1251'
  * on Windows try 'en', or 'English'
  */
  @setlocale(LC_TIME, 'ru_RU.CP1251');
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
  function oos_date_raw($date, $reverse = false) {
    if ($reverse) {
      return substr($date, 3, 2) . substr($date, 0, 2) . substr($date, 6, 4);
    } else {
      return substr($date, 6, 4) . substr($date, 0, 2) . substr($date, 3, 2);
    }
  }

// if USE_DEFAULT_LANGUAGE_CURRENCY is true, use the following currency, instead of the applications default currency (used when changing language)
define('LANGUAGE_CURRENCY', 'USD');

// Global entries for the <html> tag
define('HTML_PARAMS','dir="LTR" lang="ru"');

// charset for web pages and emails
define('CHARSET', 'windows-1251');

//text in oos_temp/templates/oos/system/user_navigation.html
$aLang['header_title_create_account'] = 'Регистрация';
$aLang['header_title_my_account'] = 'Мои Данные';
$aLang['header_title_cart_contents'] = 'Корзина Содержит';
$aLang['header_title_checkout'] = 'Оформить Заказ';
$aLang['header_title_top'] = 'Магазин';
$aLang['header_title_catalog'] = 'Каталог';
$aLang['header_title_logoff'] = 'Выход';
$aLang['header_title_login'] = 'Вход';
$aLang['header_title_whats_new'] = 'What\'s New?';

$aLang['block_heading_specials'] = 'Скидки';

// footer text in includes/oos_footer.php
$aLang['footer_text_requests_since'] = 'посетили магазин с';

// text for gender
$aLang['male'] = 'Мужской';
$aLang['female'] = 'Женский';
$aLang['male_address'] = 'Г-н';
$aLang['female_address'] = 'Г-жа';

// text for date of birth example
define('DOB_FORMAT_STRING', 'mm/dd/yyyy');
$aLang['dob_format_string'] = 'mm/dd/yyyy';


// search block text in tempalate/your theme/block/search.html
$aLang['block_search_text'] = 'Введите ключевое слово для поиска.';
$aLang['block_search_advanced_search'] = 'Расширенный Поиск';
$aLang['text_search'] = 'search...';

// reviews block text in tempalate/your theme/block/reviews.html
$aLang['block_reviews_write_review'] = 'Напишите Ваше мнение о товаре!';
$aLang['block_reviews_no_reviews'] = 'К настоящему времени нет ни одного отзыва';
$aLang['block_reviews_text_of_5_stars'] = '%s из 5 Баллов!';

// shopping_cart block text in tempalate/your theme/block/shopping_cart.html
$aLang['block_shopping_cart_empty'] = 'Корзина Пуста';

// notifications block text in tempalate/your theme/block/products_notifications.php
$aLang['block_notifications_notify'] = 'Уведомляйте меня об изменениях <b>%s</b>';

$aLang['block_notifications_notify_remove'] = 'Не уведомляйте меня об изменениях <b>%s</b>';


// wishlist block text in tempalate/your theme/block/wishlist.html
$aLang['block_wishlist_empty'] = 'You have no items on your Wishlist';

// manufacturer box text
$aLang['block_manufacturer_info_homepage'] = 'Сайт %s';
$aLang['block_manufacturer_info_other_products'] = 'Другие товары';


$aLang['block_add_product_id_text'] = 'Введите артикул товара, который Вы хотите добавить в корзину.';

// information block text in tempalate/your theme/block/information.html
$aLang['block_information_imprint'] = 'Imprint';
$aLang['block_information_privacy'] = 'Безопасность';
$aLang['block_information_conditions'] = 'Условия и Гарантии';
$aLang['block_information_shipping'] = 'Доставка и Возврат';
$aLang['block_information_contact'] = 'Свяжитесь с нами';
$aLang['block_information_v_card'] = 'vCard';
$aLang['block_information_gv'] = 'Gift Voucher FAQ';

//service
$aLang['block_service_links'] = 'Ссылки';
$aLang['block_service_newsfeed'] = 'RDS/RSS Лента';
$aLang['block_service_gv'] = 'Gift Voucher FAQ';
$aLang['block_service_sitemap'] = 'Карта сайта';

// login
$aLang['entry_email_address'] = 'E-Mail Адрес:';
$aLang['entry_password'] = 'Пароль:';

$aLang['text_password_info'] = 'Забыли пароль?';
$aLang['image_button_login'] = 'Войти';
$aLang['login_block_new_customer'] = 'New Customer';
$aLang['login_block_account_edit'] = 'Edit Account Info.';
$aLang['login_block_account_history'] = 'Account History';
$aLang['login_block_address_book'] = 'My Address Book';
$aLang['login_block_product_notifications'] = 'Product Notifications';
$aLang['login_block_my_account'] = 'General Information';
$aLang['login_block_logoff'] = 'Выход';
$aLang['login_entry_remember_me'] = 'Авто Вход';

// tell a friend block text in tempalate/your theme/block/tell_a_friend.html
$aLang['block_tell_a_friend_text'] = 'Сообщите своим знакомым об этом товаре.';

// checkout procedure text
$aLang['checkout_bar_delivery'] = 'Информация о Доставке';
$aLang['checkout_bar_payment'] = 'Информация об Оплате';
$aLang['checkout_bar_confirmation'] = 'Подтверждение';
$aLang['checkout_bar_finished'] = 'Заказ Оформлен!';

// pull down default text
$aLang['pull_down_default'] = 'Выберите из:';
$aLang['type_below'] = 'Type Below';

//newsletter
$aLang['block_newsletters_subscribe'] = 'Подписаться';
$aLang['block_newsletters_unsubscribe'] = 'Не подписываться';

//myworld
$aLang['text_date_account_created'] = 'Account Created:';
$aLang['text_yourstore'] = 'Your Participation';
$aLang['edit_yourimage'] = 'Your Image';

// javascript messages
$aLang['js_error'] = 'Вы допустили ошибки при заполнении формы!\nИсправьте пожалуйста:\n\n';

$aLang['js_review_text'] = '* Поле \'Текст Отзыва\' должно содержать не менее ' . REVIEW_TEXT_MIN_LENGTH . ' символов.\n';
$aLang['js_review_rating'] = '* Вы должны оценить товар для оставления о нём отзыва.\n';


$aLang['js_gender'] = '* Выберите значение для поля \'Пол\'.\n';
$aLang['js_first_name'] = '* Поле \'Имя\' должно содержать не менее ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' символов.\n';
$aLang['js_last_name'] = '* Поле \'Фамилия\' должно содержать не менее ' . ENTRY_LAST_NAME_MIN_LENGTH . ' символов.\n';

$aLang['js_dob'] = '* Поле \'Дата Рождения\' должно быть в формате: xx/xx/xxxx (месяц/день/год).\n';
$aLang['js_email_address'] = '* Поле \'E-Mail Адрес\' должно содержать не менее ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' символов.\n';

$aLang['js_address'] = '* Поле \'Адрес\' должно содержать не менее ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' символов.\n';

$aLang['js_post_code'] = '* Поле \'Индекс\' должно содержать не менее ' . ENTRY_POSTCODE_MIN_LENGTH . ' символов.\n';

$aLang['js_city'] = '* Поле \'Город\' должно содержать не менее ' . ENTRY_CITY_MIN_LENGTH . ' символов.\n';

$aLang['js_state'] = '* Выберите значение для поля \'Область\'.\n';
$aLang['js_country'] = '* Выберите значение для поля \'Country\'.\n';
$aLang['js_telephone'] = '* Поле \'Телефон\' должно содержать не менее ' . ENTRY_TELEPHONE_MIN_LENGTH . ' символов.\n';

$aLang['js_password'] = '* Поля \'Пароль\' и \'Подтвердите Пароль\' должны совпадать и содержать не менее ' . ENTRY_PASSWORD_MIN_LENGTH . ' символов.\n';

$aLang['js_error_no_payment_module_selected'] = '* Выберите метод оплаты для оформления Вашего заказа.\n';
$aLang['js_error_submitted'] = 'This form has already been submitted. Please press Ok and wait for this process to be completed.';

$aLang['error_no_payment_module_selected'] = 'Выберите метод оплаты для оформления Вашего заказа.';
$aLang['error_conditions_not_accepted'] = 'Если Вы не согласитесь с нашими правилами/условиями, мы не сможем обработать Ваш заказ!';


$aLang['category_company'] = 'Company Details';
$aLang['category_personal'] = 'Your Personal Details';
$aLang['category_address'] = 'Your Address';
$aLang['category_contact'] = 'Your Contact Information';
$aLang['category_options'] = 'Options';
$aLang['category_password'] = 'Your Password';
$aLang['entry_company'] = 'Название Компании:';
$aLang['entry_company_error'] = '';
$aLang['entry_company_text'] = '';
$aLang['entry_owner'] = 'Owner';
$aLang['entry_owner_error'] = '';
$aLang['entry_owner_text'] = '';
$aLang['entry_number'] = 'Customer number';
$aLang['entry_number_error'] = '';
$aLang['entry_number_text'] = '';
$aLang['entry_gender'] = 'Пол:';
$aLang['entry_gender_error'] = '&nbsp;<small><font color="#AABBDD">require_onced</font></small>';
$aLang['entry_first_name'] = 'Имя:';
$aLang['entry_first_name_error'] = '&nbsp;<small><font color="#FF0000">min ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' chars</font></small>';
$aLang['entry_first_name_text'] = '&nbsp;<small><font color="#AABBDD">require_onced</font></small>';
$aLang['entry_last_name'] = 'Фамилия:';
$aLang['entry_last_name_error'] = '&nbsp;<small><font color="#FF0000">min ' . ENTRY_LAST_NAME_MIN_LENGTH . ' chars</font></small>';
$aLang['entry_date_of_birth'] = 'Дата Рождения:';
$aLang['entry_date_of_birth_error'] = '&nbsp;<small><font color="#FF0000">(eg. 05/21/1970)</font></small>';
$aLang['entry_date_of_birth_text'] = '(eg. 05/21/1970)';
$aLang['entry_email_address'] = 'E-Mail Адрес:';
$aLang['entry_email_address_error'] = '&nbsp;<small><font color="#FF0000">min ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' chars</font></small>';
$aLang['entry_email_address_check_error'] = '&nbsp;<small><font color="#FF0000">Your email address doesn\'t appear to be valid!</font></small>';
$aLang['entry_email_address_error_exists'] = '&nbsp;<small><font color="#FF0000">email address already exists!</font></small>';

$aLang['entry_street_address'] = 'Адрес:';
$aLang['entry_street_address_error'] = '&nbsp;<small><font color="#FF0000">min ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' chars</font></small>';
$aLang['entry_suburb'] = 'Район:';
$aLang['entry_suburb_error'] = '';
$aLang['entry_suburb_text'] = '';
$aLang['entry_post_code'] = 'Индекс:';
$aLang['entry_post_code_error'] = '&nbsp;<small><font color="#FF0000">min ' . ENTRY_POSTCODE_MIN_LENGTH . ' chars</font></small>';
$aLang['entry_city'] = 'Город:';
$aLang['entry_city_error'] = '&nbsp;<small><font color="#FF0000">min ' . ENTRY_CITY_MIN_LENGTH . ' chars</font></small>';
$aLang['entry_state'] = 'Область:';
$aLang['entry_state_error'] = '&nbsp;<small><font color="#FF0000">require_onced</font></small>';
$aLang['entry_country'] = 'Страна:';
$aLang['entry_country_error'] = '';
$aLang['entry_country_text'] = '&nbsp;<small><font color="#AABBDD">require_onced</font></small>';
$aLang['entry_telephone_number'] = 'Телефон:';
$aLang['entry_telephone_number_error'] = '&nbsp;<small><font color="#FF0000">min ' . ENTRY_TELEPHONE_MIN_LENGTH . ' chars</font></small>';
$aLang['entry_fax_number'] = 'Факс:';
$aLang['entry_fax_number_error'] = '';
$aLang['entry_fax_number_text'] = '';
$aLang['entry_newsletter'] = 'Новости Магазина:';
$aLang['entry_newsletter_text'] = '';
$aLang['entry_newsletter_yes'] = 'Подписаться';
$aLang['entry_newsletter_no'] = 'Не Подписываться';
$aLang['entry_newsletter_error'] = '';
$aLang['entry_password'] = 'Пароль:';
$aLang['entry_password_confirmation'] = 'Подтвердите Пароль:';
$aLang['entry_password_confirmation_text'] = '&nbsp;<small><font color="#AABBDD">require_onced</font></small>';
$aLang['entry_password_error'] = '&nbsp;<small><font color="#FF0000">мин ' . ENTRY_PASSWORD_MIN_LENGTH . ' символов</font></small>';
$aLang['password_hidden'] = '--СКРЫТО--';
$aLang['entry_info_text'] = 'require_onced';


// constants for use in oos_prev_next_display function
$aLang['text_result_page'] = 'Result Pages:';
$aLang['text_display_number_of_products'] = 'Отображено <b>%d</b> - <b>%d</b> (из <b>%d</b> товаров)';

$aLang['text_display_number_of_orders'] = 'Отображено <b>%d</b> - <b>%d</b> (из <b>%d</b> заказов)';
$aLang['text_display_number_of_reviews'] = 'Отображено <b>%d</b> - <b>%d</b> (из <b>%d</b> отзывов)';
$aLang['text_display_number_of_products_new'] = 'Отображено <b>%d</b> - <b>%d</b> (из <b>%d</b> новинок)';
$aLang['text_display_number_of_specials'] = 'Отображено <b>%d</b> - <b>%d</b> (из <b>%d</b> специальных предложений)';
$aLang['text_display_number_of_wishlist'] = 'Отображено <b>%d</b> - <b>%d</b> (из <b>%d</b> товаров)';

$aLang['prevnext_title_first_page'] = 'Первая Страница';
$aLang['prevnext_title_previous_page'] = 'Предыдущая Страница';
$aLang['prevnext_title_next_page'] = 'Следующая Страница';
$aLang['prevnext_title_last_page'] = 'Последняя Страница';
$aLang['prevnext_title_page_no'] = 'Страница %d';
$aLang['prevnext_title_prev_set_of_no_page'] = 'Предыдущие %d Страниц';
$aLang['prevnext_title_next_set_of_no_page'] = 'Следующие %d Страниц';
$aLang['prevnext_button_first'] = '&lt;&lt;ПЕРВАЯ';
$aLang['prevnext_button_prev'] = '&lt;&lt;&nbsp;Предыдущая';
$aLang['prevnext_button_next'] = 'Следующая&nbsp;&gt;&gt;';
$aLang['prevnext_button_last'] = 'ПОСЛЕДНЯЯ&gt;&gt;';

$aLang['image_button_add_address'] = 'Добавить Адрес';
$aLang['image_button_address_book'] = 'Адресная Книга';

$aLang['image_button_back'] = 'Назад';
$aLang['image_button_change_address'] = 'Изменить Адрес';
$aLang['image_button_checkout'] = 'Оформить Заказ';
$aLang['image_button_confirm_order'] = 'Подтвердить Заказ';
$aLang['image_button_continue'] = 'Продолжить';
$aLang['image_button_continue_shopping'] = 'Вернуться в Каталог';
$aLang['image_button_delete'] = 'Удалить';
$aLang['image_button_edit_account'] = 'Редактировать Учётные Данные';
$aLang['image_button_history'] = 'История Заказов';
$aLang['image_button_login'] = 'Войти';
$aLang['image_button_in_cart'] = 'Добавить в Корзину';
$aLang['image_button_notifications'] = 'Уведомления';
$aLang['image_button_quick_find'] = 'Быстрый Поиск';
$aLang['image_button_remove_notifications'] = 'Удалить Уведомления';
$aLang['image_button_reviews'] = 'Отзывы';
$aLang['image_button_search'] = 'Искать';
$aLang['image_button_tell_a_friend'] = 'Рассказать Другу';
$aLang['image_button_update'] = 'Обновить';
$aLang['image_button_update_cart'] = 'Пересчитать';
$aLang['image_button_write_review'] = 'Написать Отзыв';
$aLang['image_button_add_quick'] = 'Быстрое Добавление!';
$aLang['image_wishlist_delete'] = 'удалить';
$aLang['image_button_in_wishlist'] = 'Wishlist';

$aLang['image_button_add_wishlist'] = 'Wishlist';

$aLang['image_button_redeem_voucher'] = 'Redeem';

$aLang['icon_button_mail'] = 'E-mail';
$aLang['icon_button_pdf'] = 'PDF';
$aLang['icon_button_print'] = 'Print';
$aLang['icon_button_zoom'] = 'Zoom';


$aLang['icon_arrow_right'] = 'more';
$aLang['icon_cart'] = 'In Cart';
$aLang['icon_warning'] = 'Warning';

$aLang['text_greeting_personal'] = 'Welcome back <span class="greetUser">%s!</span> Would you like to see which <a href="%s"><u>new products</u></a> are available to purchase?';
$aLang['text_greeting_guest'] = 'Welcome <span class="greetUser">Guest!</span> Would you like to <a href="%s"><u>log yourself in</u></a>? Or would you prefer to <a href="%s"><u>create an account</u></a>?';

$aLang['text_sort_products'] = 'Сортировка товаров ';
$aLang['text_descendingly'] = 'убыванию';
$aLang['text_ascendingly'] = 'возрастанию';
$aLang['text_by'] = ' по ';

$aLang['text_review_by'] = 'по %s';
$aLang['text_review_word_count'] = '%s words';
$aLang['text_review_rating'] = 'Рейтинг:';
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

$aLang['block_affiliate_info'] = 'Affiliate Information';
$aLang['block_affiliate_summary'] = 'Affiliate Summary';
$aLang['block_affiliate_account'] = 'Edit Affiliate Account';
$aLang['block_affiliate_clickrate'] = 'Clickthrough Report';
$aLang['block_affiliate_payment'] = 'Payment Report';
$aLang['block_affiliate_sales'] = 'Sales Report';
$aLang['block_affiliate_banners'] = 'Affiliate Banners';
$aLang['block_affiliate_contact'] = 'Contact Us';
$aLang['block_affiliate_faq'] = 'Affiliate Program FAQ';
$aLang['block_affiliate_login'] = 'Affiliate Log In';
$aLang['block_affiliate_logout'] = 'Affiliate Log Out';

$aLang['entry_affiliate_payment_details'] = 'Payable to:';
$aLang['entry_affiliate_accept_agb'] = 'Check here to indicate that you have read and agree to the <a target="_new" href="' . oos_href_link($aModules['affiliate'], $aFilename['affiliate_terms'], '', 'SSL') . '">Associates Terms & Conditions</a>.';
$aLang['entry_affiliate_agb_error'] = ' &nbsp;<small><font color="#FF0000">You must accept our Associates Terms & Conditions</font></small>';
$aLang['entry_affiliate_payment_check'] = 'Check Payee Name:';
$aLang['entry_affiliate_payment_check_text'] = '';
$aLang['entry_affiliate_payment_check_error'] = '&nbsp;<small><font color="#FF0000">require_onced</font></small>';
$aLang['entry_affiliate_payment_paypal'] = 'PayPal Account Email:';
$aLang['entry_affiliate_payment_paypal_text'] = '';
$aLang['entry_affiliate_payment_paypal_error'] = '&nbsp;<small><font color="#FF0000">require_onced</font></small>';
$aLang['entry_affiliate_payment_bank_name'] = 'Bank Name:';
$aLang['entry_affiliate_payment_bank_name_text'] = '';
$aLang['entry_affiliate_payment_bank_name_error'] = '&nbsp;<small><font color="#FF0000">require_onced</font></small>';
$aLang['entry_affiliate_payment_bank_account_name'] = 'Account Name:';
$aLang['entry_affiliate_payment_bank_account_name_text'] = '';
$aLang['entry_affiliate_payment_bank_account_name_error'] = '&nbsp;<small><font color="#FF0000">require_onced</font></small>';
$aLang['entry_affiliate_payment_bank_account_number'] = 'Account Number:';
$aLang['entry_affiliate_payment_bank_account_number_text'] = '';
$aLang['entry_affiliate_payment_bank_account_number_error'] = '&nbsp;<small><font color="#FF0000">require_onced</font></small>';
$aLang['entry_affiliate_payment_bank_branch_number'] = 'ABA/BSB number (branch number):';
$aLang['entry_affiliate_payment_bank_branch_number_text'] = '';
$aLang['entry_affiliate_payment_bank_branch_number_error'] = '&nbsp;<small><font color="#FF0000">require_onced</font></small>';
$aLang['entry_affiliate_payment_bank_swift_code'] = 'SWIFT Code:';
$aLang['entry_affiliate_payment_bank_swift_code_text'] = '';
$aLang['entry_affiliate_payment_bank_swift_code_error'] = '&nbsp;<small><font color="#FF0000">require_onced</font></small>';
$aLang['entry_affiliate_company'] = 'Company';
$aLang['entry_affiliate_company_text'] = '';
$aLang['entry_affiliate_company_error'] = '&nbsp;<small><font color="#FF0000">require_onced</font></small>';
$aLang['entry_affiliate_company_taxid'] = 'VAT-Id.:';
$aLang['entry_affiliate_company_taxid_text'] = '';
$aLang['entry_affiliate_company_taxid_error'] = '&nbsp;<small><font color="#FF0000">require_onced</font></small>';
$aLang['entry_affiliate_homepage'] = 'Homepage';

$aLang['entry_affiliate_homepage_text'] = '&nbsp;<small><font color="#AABBDD">require_onced (http://)</font></small>';
$aLang['entry_affiliate_homepage_error'] = '&nbsp;<small><font color="#FF0000">require_onced (http://)</font></small>';

$aLang['category_payment_details'] = 'You get your money by:';

$aLang['block_ticket_generate'] = 'Open Support Ticket';
$aLang['block_ticket_view'] = 'View Ticket';

$aLang['down_for_maintenance_text'] = 'Down for Maintenance ... Please try back later'; // Message to display when down for maintenance
$aLang['down_for_maintenance_no_prices_display'] = 'Down for Maintenance'; // Display something for prices could be text or an image
$aLang['no_login_no_prices_display'] = 'Prices for dealer only';
$aLang['text_products_base_price'] = 'Base Price';

// Product Qty, List, Rebate Pricing and Savings
$aLang['products_see_qty_discounts'] = 'SEE: QTY DISCOUNTS';
$aLang['products_order_qty_text'] = 'Add Qty: ';
$aLang['products_order_qty_min_text'] = '<br>' . ' Min Qty: ';
$aLang['products_order_qty_min_text_info'] = 'Order Minumum is: '; // order_detail.php
$aLang['products_order_qty_min_text_cart'] = 'Order Minimum is: '; // order_detail.php
$aLang['products_order_qty_min_text_cart_short'] = ' Min Qty: '; // order_detail.php
$aLang['products_order_qty_unit_text'] = ' in Units of: ';
$aLang['products_order_qty_unit_text_info'] = 'Order in Units of: '; // order_detail.php
$aLang['products_order_qty_unit_text_cart'] = 'Order in Units of: '; // order_detail.php
$aLang['products_order_qty_unit_text_cart_short'] = ' Units: '; // order_detail.php
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

$aLang['text_taxt_incl'] = 'inkl. gesetzl. MwSt.';
$aLang['text_taxt_add'] = 'plus. Tax';
$aLang['tax_info_excl'] = 'exkl. Tax';
$aLang['text_shipping'] = 'zzgl. <a href="%s"><u>Почтовые расходы</u></a>.';


$aLang['price'] = 'Preis';
$aLang['price_info'] = 'Alle Preise pro St&uuml;ck in &euro; inkl. der gesetzlichen Mehrwertsteuer, zzgl. <a href="' . oos_href_link($aModules['info'], $aFilename['information'], 'information_id=1') . '">Versandkostenpauschale</a> von nur 3,95 &euro; pro Bestellung.';
$aLang['support_info'] = 'Haben Sie noch Fragen? Sie erreichen uns &uuml;ber unser <a href="' . oos_href_link($aModules['ticket'], $aFilename['ticket_create']) . '">Kontaktformular</a>.';


/*
  The following copyright announcement can only be
  appropriately modified or removed if the layout of
  the site theme has been modified to distinguish
  itself from the default osCommerce-copyrighted
  theme.

  For more information please read the following
  Frequently Asked Questions entry on the osCommerce
  support site:

  http://www.oscommerce.com/community.php/faq,26/q,50

  Please leave this comment intact together with the
  following copyright announcement.
*/
define('FOOTER_TEXT_BODY', 'Copyright &copy; 2003 <a href="http://www.oscommerce.com" target="_blank">osCommerce</a><br>Powered by <a href="http://www.oscommerce.com" target="_blank">osCommerce</a>');
?>