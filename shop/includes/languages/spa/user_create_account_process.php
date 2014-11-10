<?php
/* ----------------------------------------------------------------------
   $Id: user_create_account_process.php,v 1.1 2007/06/13 15:54:26 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: create_account_process.php,v 1.11 2002/11/12 00:45:21 dgw_ 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

$aLang['navbar_title_1'] = 'Crear una Cuenta';
$aLang['navbar_title_2'] = 'Proceso';
$aLang['heading_title'] = 'Informacion de Mi Cuenta';

$aLang['email_subject'] = 'Bienvenido a ' . STORE_NAME;
$aLang['email_greet_mr'] = 'Estimado ' . stripslashes($lastname) . ',' . "\n\n";
$aLang['email_greet_ms'] = 'Estimado ' . stripslashes($lastname) . ',' . "\n\n";
$aLang['email_greet_none'] = 'Estimado ' . stripslashes($firstname) . ',' . "\n\n";
$aLang['email_welcome'] = 'le damos la bienvenida a <b>' . STORE_NAME . '</b>.' . "\n\n";
$aLang['email_text'] = 'Ahora puede disfrutar de los <b>servicios</b> que le ofrecemos. Algunos de estos servicios son:' . "\n\n" . '<li><b>Carrito Permanente</b> - Cualquier producto a�dido a su carrito permanecera en el hasta que lo elimine, o hasta que realice la compra.' . "\n" . '<li><b>Libro de Direcciones</b> - Podemos enviar sus productos a otras direcciones aparte de la suya! Esto es perfecto para enviar regalos de cumplea�s directamente a la persona que cumple a�s.' . "\n" . '<li><b>Historia de Pedidos</b> - Vea la relacion de compras que ha realizado con nosotros.' . "\n" . '<li><b>Comentarios</b> - Comparta su opinion sobre los productos con otros clientes.' . "\n\n";
$aLang['email_contact'] = 'Para cualquier consulta sobre nuestros servicios, por favor escriba a: ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n\n";
$aLang['email_warning'] = '<b>Nota:</b> Esta direccion fue suministrada por uno de nuestros clientes. Si usted no se ha suscrito como socio, por favor comuniquelo a ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n";

$aLang['email_gv_incentive_header'] = 'As part of our welcome to new customers, we have sent you an e-Gift Voucher worth %s';
$aLang['email_gv_redeem'] = 'The redeem code for is %s, you can enter the redeem code when checking out, after making a purchase';
$aLang['email_gv_link'] = 'or by following this link ';
$aLang['email_coupon_incentive_header'] = 'Congratulation, to make your first visit to our online shop a more rewarding experience' . "\n" . 
                                        '  below are details of a Discount Coupon created just for you' . "\n\n";
$aLang['email_coupon_redeem'] = 'To use the coupon enter the redeem code which is %s during checkout, ' . "\n" . 
                               'after making a purchase';

$aLang['email_password'] = 'Your password to \'' . STORE_NAME . '\' is:' . "\n\n" . '   %s' . "\n\n";

$aLang['email_disclaimer'] = '--- Disclaimer ------------------------------------------------------------' . "\n\n" .
                            'Your privacy:' . "\n\n" .
                            'We will never sell or trade your personal information. We will not' . "\n" .
                            'reveal your personal information to anyone except if required by lawful' . "\n" .
                            'authority. The only exception to the privacy of your information is' . "\n" .
                            'your name and email being visible to all readers that access your' . "\n" .
                            'contributions on ' . oos_server_get_base_url() . '.' . "\n\n" .
                            'Please note that your connection to ' . oos_server_get_base_url() . ', like your connection' . "\n" .
                            'to many other websites, is not encrypted. Your login and password are' . "\n" .
                            'transmitted in plain text over your internet connection and may be' . "\n" .
                            'readable by malicious users. For this reason, you must not use' . "\n" .
                            'credentials that are identical to any other service you subscribe to.' . "\n" .
                            'To be safe, make a unique password for each of your internet services.' . "\n\n" .
                            'Unsolicited Email:' . "\n\n" .
                            'This email was initiated on ' . strftime(DATE_FORMAT_LONG) . ' by the IP ' . oos_server_get_remote() . "\n" .
                            '(' . oos_server_get_var('REMOTE_HOST') . '). If this IP address was not yours at that time, and' . "\n" .
                            'you wish to persue the abuse, please do not delete this email.' . "\n" .
                            'Instead, kindly ask the responsible webmaster at ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n" .
                            'They can help you in most cases, but if you still feel unsatisfied then,' . "\n" .
                            'you may contact the ISP of the originating network of ' . oos_server_get_var('REMOTE_HOST') . '.' . "\n\n" .
                            'Important: The webmaster of ' . oos_server_get_base_url() . ' is contingently able to control' . "\n" .
                            'any abuses and is generally not resposible for this email.' . "\n\n" .
                            'If you do not wish to subscribe, simply do nothing. Thank you.';

$aLang['owner_email_subject'] = 'New Customer';
$aLang['owner_email_date'] = 'Date:';
$aLang['owner_email_company_info'] = 'Empresa';
$aLang['owner_email_contact'] = 'Contacto';
$aLang['owner_email_options'] = 'Opciones';
$aLang['owner_email_company'] = 'Empresa:';
$aLang['owner_email_owner'] = 'Owner';
$aLang['owner_email_number'] = 'Customer number';
$aLang['owner_email_gender'] = 'Sexo:';
$aLang['owner_email_first_name'] = 'Nombre:';
$aLang['owner_email_last_name'] = 'Apellidos:';
$aLang['owner_email_date_of_birth'] = 'Fecha de Nacimiento:';
$aLang['owner_email_address'] = 'E-Mail:';
$aLang['owner_email_street'] = 'Direccion:';
$aLang['owner_email_suburb'] = 'Suburbio';
$aLang['owner_email_post_code'] = 'Codigo Postal:';
$aLang['owner_email_city'] = 'Poblacion:';
$aLang['owner_email_state'] = 'Provincia/Estado:';
$aLang['owner_email_country'] = 'Pais:';
$aLang['owner_email_telephone_number'] = 'Telefono:';
$aLang['owner_email_fax_number'] = 'Fax:';
$aLang['owner_email_newsletter'] = 'Bolet� de noticias:';
$aLang['owner_email_newsletter_yes'] = 'suscribirse';
$aLang['owner_email_newsletter_no'] = 'no suscribirse';
$aLang['email_separator'] = '------------------------------------------------------';

?>
