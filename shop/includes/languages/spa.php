<?php
/* ----------------------------------------------------------------------
   $Id: spa.php,v 1.3 2007/06/12 16:57:18 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: espanol.php,v 1.99 2003/02/17 11:49:25 hpdl
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
  * on RedHat try 'es_ES'
  * on FreeBSD try 'es_ES.ISO_8859-1'
  * on Windows try 'sp', or 'Spanish'
  */
  @setlocale(LC_TIME, 'es_ES');
  define('DATE_FORMAT_SHORT', '%d.%m.%Y');  // this is used for strftime()
  define('DATE_FORMAT_LONG', '%A %d %B, %Y'); // this is used for strftime()
  define('DATE_FORMAT', 'd/m/Y');  // this is used for date()
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
      return substr($date, 0, 2) . substr($date, 3, 2) . substr($date, 6, 4);
    } else {
      return substr($date, 6, 4) . substr($date, 3, 2) . substr($date, 0, 2);
    }
  }

// if USE_DEFAULT_LANGUAGE_CURRENCY is true, use the following currency, instead of the applications default currency (used when changing language)
define('LANGUAGE_CURRENCY', 'EUR');

// Global entries for the <html> tag
define('HTML_PARAMS','dir="LTR" lang="es"');
define('XML_PARAMS','xml:lang="es" lang="es"');


// charset for web pages and emails
define('CHARSET', 'iso-8859-15');

//text in oos_temp/templates/oos/system/user_navigation.html
$aLang['header_title_create_account'] = 'Crear Cuenta';
$aLang['header_title_my_account'] = 'Mi Cuenta';
$aLang['header_title_cart_contents'] = 'Ver Cesta';
$aLang['header_title_checkout'] = 'Realizar Pedido';
$aLang['header_title_top'] = 'Inicio';
$aLang['header_title_catalog'] = 'Catalogo';
$aLang['header_title_logoff'] = 'Salir';
$aLang['header_title_login'] = 'Entrar';
$aLang['header_title_whats_new'] = 'Novedades';

$aLang['block_heading_specials'] = 'Ofertas';

// footer text in includes/oos_footer.php
$aLang['footer_text_requests_since'] = 'peticiones desde';

// text for gender
$aLang['male'] = 'Varon';
$aLang['female'] = 'Mujer';
$aLang['male_address'] = 'Sr.';
$aLang['female_address'] = 'Sra.';

// text for date of birth example
define('DOB_FORMAT_STRING', 'mm/dd/yyyy');
$aLang['dob_format_string'] = 'dd/mm/aaaa';

// search block text in tempalate/your theme/block/search.html
$aLang['block_search_text'] = 'Use palabras clave para encontrar el producto que busca.';
$aLang['block_search_advanced_search'] = 'Búsqueda Avanzada';
$aLang['text_search'] = 'search...';

// reviews box text in includes/boxes/reviews.php
$aLang['block_reviews_write_review'] = 'Escriba un comentario para este producto';
$aLang['block_reviews_no_reviews'] = 'En este momento, no hay ningun comentario';
$aLang['block_reviews_text_of_5_stars'] = '%s de 5 Estrellas!';

// shopping_cart box text in includes/boxes/shopping_cart.php
$aLang['block_shopping_cart_empty'] = '0 productos';

// notifications box text in includes/boxes/products_notifications.php
$aLang['block_notifications_notify'] = 'Notifiqueme de cambios a <b>%s</b>';
$aLang['block_notifications_notify_remove'] = 'No me notifique de cambios a <b>%s</b>';

// wishlist box text in includes/boxes/wishlist.php
$aLang['block_wishlist_empty'] = 'You have no items on your Wishlist';

// manufacturer box text
$aLang['block_manufacturer_info_homepage'] = 'Pagina de %s';
$aLang['block_manufacturer_info_other_products'] = 'Otros productos';

$aLang['block_add_product_id_text'] = 'Enter the model of the product you wish to add to your shopping cart.';

// information box text in includes/block/information.php
$aLang['block_information_imprint'] = 'Imprint';
$aLang['block_information_privacy'] = 'Confidencialidad';
$aLang['block_information_conditions'] = 'Condiciones de uso';
$aLang['block_information_shipping'] = 'Envios/Devoluciones';
$aLang['block_information_contact'] = 'Contactenos';
$aLang['block_information_v_card'] = 'vCard';
$aLang['block_information_mapquest'] = 'Map This Location';
$aLang['block_skype_me'] = 'Skype Me';
$aLang['block_information_gv'] = 'Gift Voucher FAQ';
$aLang['block_information_gallery'] = 'Gallery';


// service.php
$aLang['block_service_links'] = 'Web Links';
$aLang['block_service_newsfeed'] = 'RDS/RSS Newsfeed';
$aLang['block_service_gv'] = 'Redeem Gift Voucher';
$aLang['block_service_sitemap'] = 'Sitemap';

//login
$aLang['entry_email_address'] = 'Direccion E-Mail:';
$aLang['entry_password'] = 'Contraseña:';
$aLang['text_password_info'] = '¿Ha olvidado su contraseña?';
$aLang['login_block_new_customer'] = 'Nuevo Cliente';
$aLang['image_button_login'] = 'Login';
$aLang['login_block_account_edit'] = 'Edit Account Info.';
$aLang['login_block_account_history'] = 'Account History';
$aLang['login_block_address_book'] = 'My Address Book';
$aLang['login_block_product_notifications'] = 'Product Notifications';
$aLang['login_block_my_account'] = 'General Information';
$aLang['login_block_logoff'] = 'Log Off';
$aLang['login_entry_remember_me'] = 'AotoLogon';

// tell a friend box text in includes/boxes/tell_a_friend.php
$aLang['block_tell_a_friend_text'] = 'Envia esta pagina a un amigo con un comentario.';

// checkout procedure text
$aLang['checkout_bar_delivery'] = 'entrega';
$aLang['checkout_bar_payment'] = 'pago';
$aLang['checkout_bar_confirmation'] = 'confirmación';
$aLang['checkout_bar_finished'] = 'finalizado!';

// pull down default text
$aLang['pull_down_default'] = 'Seleccione';
$aLang['type_below'] = 'Escriba Debajo';

//newsletter
$aLang['block_newsletters_subscribe'] = 'Subscribe';
$aLang['block_newsletters_unsubscribe'] = 'Unsubscribe';

//myworld
$aLang['text_date_account_created'] = 'Account Created:';
$aLang['text_yourstore'] = 'Your Participation';
$aLang['edit_yourimage'] = 'Your Image';

// javascript messages
$aLang['js_error'] = 'Hay errores en su formulario!\nPor favor, haga las siguiente correciones:\n\n';

$aLang['js_review_text'] = '* Su \'Comentario\' debe tener al menos ' . REVIEW_TEXT_MIN_LENGTH . ' letras.\n';
$aLang['js_review_rating'] = '* Debe evaluar el producto sobre el que opina.\n';

$aLang['js_gender'] = '* Debe indicar su \'Sexo\'.\n';
$aLang['js_first_name'] = '* Su \'Nombre\' debe tener al menos ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' letras.\n';
$aLang['js_last_name'] = '* Sus \'Apellidos\' deben tener al menos ' . ENTRY_LAST_NAME_MIN_LENGTH . ' letras.\n';
$aLang['js_dob'] = '* La \'Fecha de nacimiento\' debe tener el formato: xx/xx/xxxx (dia/mes/año).\n';
$aLang['js_email_address'] = '* Su \'E-Mail\' debe tener al menos ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' letras.\n';
$aLang['js_address'] = '* Su \'Direccion\' debe tener al menos ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' letras.\n';
$aLang['js_post_code'] = '* Su \'Codigo Postal\' debe tener al menos ' . ENTRY_POSTCODE_MIN_LENGTH . ' letras.\n';
$aLang['js_city'] = '* La \'Poblacion\' debe tener al menos ' . ENTRY_CITY_MIN_LENGTH . ' letras.\n';
$aLang['js_state'] = '* Debe indicar la \'Provincia\'.\n';
$aLang['js_country'] = '* Debe seleccionar su \'Pais\'.\n';
$aLang['js_telephone'] = '* El \'Telefono\' debe tener al menos ' . ENTRY_TELEPHONE_MIN_LENGTH . ' letras.\n';
$aLang['js_password'] = '* La \'Contraseña\' y la \'Confirmación\' deben ser iguales y tener al menos ' . ENTRY_PASSWORD_MIN_LENGTH . ' letras.\n';

$aLang['js_error_no_payment_module_selected'] = '* Por favor seleccione un método de pago para su pedido.\n';
$aLang['js_error_submitted'] = 'Ya ha enviado el formulario. Pulse Aceptar y espere a que termine el proceso.';

$aLang['error_no_payment_module_selected'] = 'Por favor seleccione un método de pago para su pedido.';
$aLang['error_conditions_not_accepted'] = 'Si usted no acepta nuestras condiciones de uso, no podemos procesar su orden!';

$aLang['category_company'] = 'Empresa';
$aLang['category_personal'] = 'Personal';
$aLang['category_address'] = 'Direccion';
$aLang['category_contact'] = 'Contacto';
$aLang['category_options'] = 'Opciones';
$aLang['category_password'] = 'Contraseña';
$aLang['entry_company'] = 'Empresa:';
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
$aLang['entry_gender'] = 'Sexo:';
$aLang['entry_gender_error'] = '&nbsp;<small><font color="#AABBDD">obligatorio</font></small>';
$aLang['entry_first_name'] = 'Nombre:';
$aLang['entry_first_name_error'] = '&nbsp;<small><font color="#FF0000">min ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' letras</font></small>';
$aLang['entry_last_name'] = 'Apellidos:';
$aLang['entry_last_name_error'] = '&nbsp;<small><font color="#FF0000">min ' . ENTRY_LAST_NAME_MIN_LENGTH . ' letras</font></small>';
$aLang['entry_date_of_birth'] = 'Fecha de Nacimiento:';
$aLang['entry_date_of_birth_error'] = '&nbsp;<small><font color="#FF0000">(p.ej. 21/05/1970)</font></small>';
$aLang['entry_date_of_birth_text'] = '(eg. 05/21/1970)';
$aLang['entry_email_address'] = 'E-Mail:';
$aLang['entry_email_address_error'] = '&nbsp;<small><font color="#FF0000">min ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' letras</font></small>';
$aLang['entry_email_address_check_error'] = '&nbsp;<small><font color="#FF0000">Su Email no parece correcto!</font></small>';
$aLang['entry_email_address_error_exists'] = '&nbsp;<small><font color="#FF0000">email ya existe!</font></small>';
$aLang['entry_email_address_text'] = '&nbsp;<small><font color="#AABBDD">obligatorio</font></small>';

$aLang['entry_street_address'] = 'Direccion:';
$aLang['entry_street_address_error'] = '&nbsp;<small><font color="#FF0000">min ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' letras</font></small>';
$aLang['entry_street_address_text'] = '&nbsp;<small><font color="#AABBDD">obligatorio</font></small>';
$aLang['entry_suburb'] = 'Suburbio';
$aLang['entry_suburb_error'] = '';
$aLang['entry_suburb_text'] = '';
$aLang['entry_post_code'] = 'Codigo Postal:';
$aLang['entry_post_code_error'] = '&nbsp;<small><font color="#FF0000">min ' . ENTRY_POSTCODE_MIN_LENGTH . ' letras</font></small>';
$aLang['entry_post_code_text'] = '&nbsp;<small><font color="#AABBDD">obligatorio</font></small>';
$aLang['entry_city'] = 'Poblacion:';
$aLang['entry_city_error'] = '&nbsp;<small><font color="#FF0000">min ' . ENTRY_CITY_MIN_LENGTH . ' chars</font></small>';
$aLang['entry_city_text'] = '&nbsp;<small><font color="#AABBDD">obligatorio</font></small>';
$aLang['entry_state'] = 'Provincia/Estado:';
$aLang['entry_state_error'] = '&nbsp;<small><font color="#FF0000">obligatorio</font></small>';
$aLang['entry_state_text'] = '&nbsp;<small><font color="#AABBDD">obligatorio</font></small>';
$aLang['entry_country'] = 'Pais:';
$aLang['entry_country_error'] = '';
$aLang['entry_country_text'] = '&nbsp;<small><font color="#AABBDD">obligatorio</font></small>';
$aLang['entry_telephone_number'] = 'Telefono:';
$aLang['entry_telephone_number_error'] = '&nbsp;<small><font color="#FF0000">min ' . ENTRY_TELEPHONE_MIN_LENGTH . ' letras</font></small>';
$aLang['entry_telephone_number_text'] = '&nbsp;<small><font color="#AABBDD">obligatorio</font></small>';
$aLang['entry_fax_number'] = 'Fax:';
$aLang['entry_fax_number_error'] = '';
$aLang['entry_fax_number_text'] = '';
$aLang['entry_newsletter'] = 'Boletín de noticias:';
$aLang['entry_newsletter_text'] = '';
$aLang['entry_newsletter_yes'] = 'suscribirse';
$aLang['entry_newsletter_no'] = 'no suscribirse';
$aLang['entry_newsletter_error'] = '';
$aLang['entry_password'] = 'Contraseña:';
$aLang['entry_password_confirmation'] = 'Confirme Contraseña:';
$aLang['entry_password_confirmation_text'] = '&nbsp;<small><font color="#AABBDD">obligatorio</font></small>';
$aLang['entry_password_error'] = '&nbsp;<small><font color="#FF0000">min ' . ENTRY_PASSWORD_MIN_LENGTH . ' letras</font></small>';
$aLang['entry_password_text'] = '&nbsp;<small><font color="#AABBDD">obligatorio</font></small>';
$aLang['password_hidden'] = '--OCULTO--';
$aLang['entry_info_text'] = 'require_onced';



// constants for use in oos_prev_next_display function
$aLang['text_result_page'] = 'Paginas de Resultados:';
$aLang['text_display_number_of_products'] = 'Viendo del <b>%d</b> al <b>%d</b> (de <b>%d</b> productos)';
$aLang['text_display_number_of_orders'] = 'Viendo del <b>%d</b> al <b>%d</b> (de <b>%d</b> pedidos)';
$aLang['text_display_number_of_reviews'] = 'Viendo del <b>%d</b> al <b>%d</b> (de <b>%d</b> comentarios)';
$aLang['text_display_number_of_products_new'] = 'Viendo del <b>%d</b> al <b>%d</b> (de <b>%d</b> productos nuevos)';
$aLang['text_display_number_of_specials'] = 'Viendo del<b>%d</b> al <b>%d</b> (de <b>%d</b> Ofertas)';
$aLang['text_display_number_of_wishlist'] = 'Viendo del <b>%d</b> al <b>%d</b> (de <b>%d</b> productos)';

$aLang['prevnext_title_first_page'] = 'Principio';
$aLang['prevnext_title_previous_page'] = 'Anterior';
$aLang['prevnext_title_next_page'] = 'Siguiente';
$aLang['prevnext_title_last_page'] = 'Final';
$aLang['prevnext_title_page_no'] = 'Pagina %d';
$aLang['prevnext_title_prev_set_of_no_page'] = 'Anteriores %d Paginas';
$aLang['prevnext_title_next_set_of_no_page'] = 'Siguientes %d Paginas';
$aLang['prevnext_button_first'] = '&lt;&lt;PRINCIPIO';
$aLang['prevnext_button_prev'] = '&lt;&lt;&nbsp;Anterior';
$aLang['prevnext_button_next'] = 'Siguiente&nbsp;&gt;&gt;';
$aLang['prevnext_button_last'] = 'FINAL&gt;&gt;';

$aLang['image_button_add_address'] = 'Añadir Dirección';
$aLang['image_button_address_book'] = 'Direcciones';
$aLang['image_button_back'] = 'Atrás';
$aLang['image_button_change_address'] = 'Cambiar Dirección';
$aLang['image_button_checkout'] = 'Realizar Pedido';
$aLang['image_button_confirm_order'] = 'Confirmar Pedido';
$aLang['image_button_continue'] = 'Continuar';
$aLang['image_button_continue_shopping'] = 'Seguir Comprando';
$aLang['image_button_delete'] = 'Eliminar';
$aLang['image_button_edit_account'] = 'Editar Cuenta';
$aLang['image_button_history'] = 'Historial de Pedidos';
$aLang['image_button_login'] = 'Entrar';
$aLang['image_button_in_cart'] = 'Añadir a la Cesta';
$aLang['image_button_notifications'] = 'Notificaciones';
$aLang['image_button_quick_find'] = 'Busqueda Rápida';
$aLang['image_button_remove_notifications'] = 'Eliminar Notificaciones';
$aLang['image_button_reviews'] = 'Comentarios';
$aLang['image_button_search'] = 'Search';
$aLang['image_button_tell_a_friend'] = 'Díselo a un Amigo';
$aLang['image_button_update'] = 'Actualizar';
$aLang['image_button_update_cart'] = 'Actualizar Cesta';
$aLang['image_button_write_review'] = 'Escribir Comentario';
$aLang['image_button_add_quick'] = 'Add a Quickie!';
$aLang['image_wishlist_delete'] = 'delete';
$aLang['image_button_in_wishlist'] = 'Wishlist';
$aLang['image_button_add_wishlist'] = 'Wishlist';
$aLang['image_button_redeem_voucher'] = 'Redeem';
$aLang['image_button_hp_buy'] = 'Añadir a la Cesta';
$aLang['image_button_hp_more'] = 'más';

$aLang['icon_button_mail'] = 'E-mail';
$aLang['icon_button_movie'] = 'Movie';
$aLang['icon_button_pdf'] = 'PDF';
$aLang['icon_button_print'] = 'Print';

$aLang['icon_arrow_right'] = 'más';
$aLang['icon_cart'] = 'En Cesta';
$aLang['icon_warning'] = 'Advertencia';

$aLang['text_greeting_personal'] = 'Bienvenido de nuevo <span class="greetUser">%s!</span> &iquest;Le gustaria ver que <a href="%s"><u>nuevos productos</u></a> hay disponibles?';
$aLang['text_greeting_guest'] = 'Bienvenido <span class="greetUser">Invitado!</span> &iquest;Le gustaria <a href="%s"><u>entrar en su cuenta</u></a> o preferiria <a href="%s"><u>crear una cuenta nueva</u></a>?';

$aLang['text_sort_products'] = 'Ordenar Productos ';
$aLang['text_descendingly'] = 'Descendentemente';
$aLang['text_ascendingly'] = 'Ascendentemente';
$aLang['text_by'] = ' por ';

$aLang['text_review_by'] = 'por %s';
$aLang['text_review_word_count'] = '%s palabras';
$aLang['text_review_rating'] = 'Evaluacion:';
$aLang['text_review_date_added'] = 'Fecha Alta:';
$aLang['text_no_reviews'] = 'En este momento, no hay ningun comentario.';
$aLang['text_no_new_products'] = 'Ahora mismo no hay novedades.';
$aLang['text_unknown_tax_rate'] = 'Impuesto desconocido';
$aLang['text_required'] = 'Obligatorio';
$aLang['error_oos_mail'] = '<small>OOS ERROR:</small> No he podido enviar el email con el servidor SMTP especificado. Configura tu servidor SMTP en la seccion adecuada del fichero php.ini.';

$aLang['warning_install_directory_exists'] = 'Advertencia: El directorio de instalacion existe en: ' . dirname(oos_server_get_var('SCRIPT_FILENAME')) . '/install. Por razones de seguridad, elimine este directorio completamente.';
$aLang['warning_config_file_writeable'] = 'Advertencia: Puedo escribir en el fichero de configuracion: ' . dirname(oos_server_get_var('SCRIPT_FILENAME')) . '/includes/configure.php. En determinadas circunstancias esto puede suponer un riesgo - por favor corriga los permisos de este fichero.';
$aLang['warning_session_auto_start'] = 'Advertencia: session.auto_start esta activado - desactive esta caracteristica en el fichero php.ini and reinicie el servidor web.';
$aLang['warning_download_directory_non_existent'] = 'Advertencia: El directorio para productos descargables no existe: ' . OOS_DOWNLOAD_PATH . '. Los productos descargables no funcionaran hasta que no se corriga este error.';
$aLang['warning_session_directory_non_existent'] = 'Advertencia: El directorio para guardar datos de sesi&oacute;n no existe: ' . oos_session_save_path() . '. Las sesiones no funcionar&aacute;n hasta que no se corriga este error.';
$aLang['warning_session_directory_not_writeable'] = 'Avertencia: No puedo escribir en el directorio para datos de sesi&oacute;n: ' . oos_session_save_path() . '. Las sesiones no funcionar&aacute;n hasta que no se corriga este error.';

$aLang['text_ccval_error_invalid_date'] = 'La fecha de caducidad de la tarjeta de credito es incorrecta.<br>Compruebe la fecha e intentelo de nuevo.';
$aLang['text_ccval_error_invalid_number'] = 'El numero de la tarjeta de credito es incorrecto.<br>Compruebe el numero e intentelo de nuevo.';
$aLang['text_ccval_error_unknown_card'] = 'Los primeros cuatro digitos de su tarjeta son: %s<br>Si este numero es correcto, no aceptamos este tipo de tarjetas.<br>Si es incorrecto, intentelo de nuevo.';

$aLang['voucher_balance'] = 'Voucher Balance';
$aLang['gv_faq'] = 'Gift Voucher FAQ';
$aLang['error_reedeemed_amount'] = 'Congratulations, you have redeemed ';
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
$aLang['block_affiliate_contact'] = 'Contactenos';
$aLang['block_affiliate_faq'] = 'Affiliate Program FAQ';
$aLang['block_affiliate_login'] = 'Affiliate Log In';
$aLang['block_affiliate_logout'] = 'Affiliate Log Out';

$aLang['entry_affiliate_payment_details'] = 'Payable to:';
$aLang['entry_affiliate_accept_agb'] = 'Check here to indicate that you have read and agree to the <a target="_new" href="' . oos_href_link($aModules['affiliate'], $aFilename['affiliate_terms'], '', 'SSL') . '">Associates Terms & Conditions</a>.';
$aLang['entry_affiliate_agb_error'] = ' &nbsp;<small><font color="#FF0000">You must accept our Associates Terms & Conditions</font></small>';
$aLang['entry_affiliate_payment_check'] = 'Check Payee Name:';
$aLang['entry_affiliate_payment_check_text'] = '';
$aLang['entry_affiliate_payment_check_error'] = '&nbsp;<small><font color="#FF0000">obligatorio</font></small>';
$aLang['entry_affiliate_payment_paypal'] = 'PayPal Account Email:';
$aLang['entry_affiliate_payment_paypal_text'] = '';
$aLang['entry_affiliate_payment_paypal_error'] = '&nbsp;<small><font color="#FF0000">obligatorio</font></small>';
$aLang['entry_affiliate_payment_bank_name'] = 'Bank Name:';
$aLang['entry_affiliate_payment_bank_name_text'] = '';
$aLang['entry_affiliate_payment_bank_name_error'] = '&nbsp;<small><font color="#FF0000">obligatorio</font></small>';
$aLang['entry_affiliate_payment_bank_account_name'] = 'Account Name:';
$aLang['entry_affiliate_payment_bank_account_name_text'] = '';
$aLang['entry_affiliate_payment_bank_account_name_error'] = '&nbsp;<small><font color="#FF0000">obligatorio</font></small>';
$aLang['entry_affiliate_payment_bank_account_number'] = 'Account Number:';
$aLang['entry_affiliate_payment_bank_account_number_text'] = '';
$aLang['entry_affiliate_payment_bank_account_number_error'] = '&nbsp;<small><font color="#FF0000">obligatorio</font></small>';
$aLang['entry_affiliate_payment_bank_branch_number'] = 'ABA/BSB number (branch number):';
$aLang['entry_affiliate_payment_bank_branch_number_text'] = '';
$aLang['entry_affiliate_payment_bank_branch_number_error'] = '&nbsp;<small><font color="#FF0000">obligatorio</font></small>';
$aLang['entry_affiliate_payment_bank_swift_code'] = 'SWIFT Code:';
$aLang['entry_affiliate_payment_bank_swift_code_text'] = '';
$aLang['entry_affiliate_payment_bank_swift_code_error'] = '&nbsp;<small><font color="#FF0000">obligatorio</font></small>';
$aLang['entry_affiliate_company'] = 'Empresa';
$aLang['entry_affiliate_company_text'] = '';
$aLang['entry_affiliate_company_error'] = '&nbsp;<small><font color="#FF0000">obligatorio</font></small>';
$aLang['entry_affiliate_company_taxid'] = 'VAT-Id.:';
$aLang['entry_affiliate_company_taxid_text'] = '';
$aLang['entry_affiliate_company_taxid_error'] = '&nbsp;<small><font color="#FF0000">obligatorio</font></small>';
$aLang['entry_affiliate_homepage'] = 'Homepage';

$aLang['entry_affiliate_homepage_text'] = '&nbsp;<small><font color="#AABBDD">obligatorio (http://)</font></small>';
$aLang['entry_affiliate_homepage_error'] = '&nbsp;<small><font color="#FF0000">obligatorio (http://)</font></small>';

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

/**
 * english_elari_cs.php,v 1.1 2003/01/08 10:53:03 elarifr 
 * For customers_status v3.x / Catalog Part
 */
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
$aLang['good_morning'] = '&iexcl; Buenos días!';
$aLang['good_afternoon'] = '&iexcl; Buenas tardes!';
$aLang['good_evening'] = '&iexcl; Buenas noches!';


$aLang['text_taxt_incl'] = 'incl. Tax';
$aLang['text_taxt_add'] = 'plus. Tax';
$aLang['tax_info_excl'] = 'exkl. Tax';
$aLang['text_shipping'] = 'excl. <a href="%s"><u>Shipping cost</u></a>.';

$aLang['price'] = 'Preis';
$aLang['price_from'] = 'from';
$aLang['price_info'] = 'Alle Preise pro St&uuml;ck in &euro; inkl. der gesetzlichen Mehrwertsteuer, zzgl. <a href="' . oos_href_link($aModules['info'], $aFilename['information'], 'information_id=1') . '">Versandkostenpauschale</a>.';
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