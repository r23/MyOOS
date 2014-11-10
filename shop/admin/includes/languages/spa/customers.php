<?php
/* ----------------------------------------------------------------------
   $Id: customers.php,v 1.3 2007/06/13 17:02:37 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: customers.php,v 1.9 2002/03/09 20:18:24 dgw_ 
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

define('HEADING_TITLE', 'Clientes');
define('HEADING_TITLE_SEARCH', 'Buscar:');

define('TABLE_HEADING_FIRSTNAME', 'Nombre');
define('TABLE_HEADING_LASTNAME', 'Apellido');
define('TABLE_HEADING_ACCOUNT_CREATED', 'Cuenta Creada');
define('TABLE_HEADING_ACTION', 'Accion');
define('HEADING_TITLE_STATUS', 'Status:');
define('TEXT_ALL_CUSTOMERS', 'All Customers');
define('HEADING_TITLE_LOGIN', 'Login');

define('TEXT_INFO_HEADING_STATUS_CUSTOMER', 'Edit Customer Status');
define('TEXT_NO_CUSTOMER_HISTORY', 'No Customer History Available');
define('TABLE_HEADING_NEW_VALUE', 'New Value');
define('TABLE_HEADING_OLD_VALUE', 'Old Value');
define('TABLE_HEADING_CUSTOMER_NOTIFIED', 'Customer Notified');
define('TABLE_HEADING_DATE_ADDED', 'Date Added');

define('CATEGORY_MAX_ORDER', 'Maximum Order');
define('ENTRY_MAX_ORDER', 'Credit Limit:');

define('ENTRY_VAT_ID_STATUS', 'Vat ID check');
define('ENTRY_VAT_ID_STATUS_YES', 'yes');
define('ENTRY_VAT_ID_STATUS_NO', 'no');

define('TEXT_DATE_ACCOUNT_CREATED', 'Cuenta Creada:');
define('TEXT_DATE_ACCOUNT_LAST_MODIFIED', 'Ultima Modificacion:');
define('TEXT_INFO_DATE_LAST_LOGON', 'Ultima Visita:');
define('TEXT_INFO_NUMBER_OF_LOGONS', 'Numero de visitas:');
define('TEXT_INFO_COUNTRY', 'Pais:');
define('TEXT_INFO_NUMBER_OF_REVIEWS', 'Numero de Comentarios:');
define('TEXT_DELETE_INTRO', 'Seguro que desea eliminar este cliente?');
define('TEXT_DELETE_REVIEWS', 'Eliminar %s comentario(s)');
define('TEXT_INFO_HEADING_DELETE_CUSTOMER', 'Eliminar Cliente');
define('TYPE_BELOW', 'Escriba debajo');
define('PLEASE_SELECT', 'Seleccione');

define('EMAIL_SUBJECT', 'Bienvenido a ' . STORE_NAME);
define('EMAIL_GREET_MR', 'Estimado ');
define('EMAIL_GREET_MS', 'Estimado ')
define('EMAIL_GREET_NONE', 'Estimado ')
define('EMAIL_WELCOME', 'le damos la bienvenida a <b>' . STORE_NAME . '</b>.' . "\n\n");
define('EMAIL_TEXT', 'Ahora puede disfrutar de los <b>servicios</b> que le ofrecemos. Algunos de estos servicios son:' . "\n\n" . '<li><b>Carrito Permanente</b> - Cualquier producto a�dido a su carrito permanecera en el hasta que lo elimine, o hasta que realice la compra.' . "\n" . '<li><b>Libro de Direcciones</b> - Podemos enviar sus productos a otras direcciones aparte de la suya! Esto es perfecto para enviar regalos de cumplea�s directamente a la persona que cumple a�s.' . "\n" . '<li><b>Historia de Pedidos</b> - Vea la relacion de compras que ha realizado con nosotros.' . "\n" . '<li><b>Comentarios</b> - Comparta su opinion sobre los productos con otros clientes.' . "\n\n");
define('EMAIL_CONTACT', 'Para cualquier consulta sobre nuestros servicios, por favor escriba a: ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n\n");
define('EMAIL_WARNING', '<b>Nota:</b> Esta direccion fue suministrada por uno de nuestros clientes. Si usted no se ha suscrito como socio, por favor comuniquelo a ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n");
define('EMAIL_PASSWORD_BODY', 'Su nueva contrase� para \'' . STORE_NAME . '\' es:' . "\n\n" . '   %s' . "\n\n");

define('EMAIL_GV_INCENTIVE_HEADER', 'As part of our welcome to new customers, we have sent you an e-Gift Voucher worth %s');
define('EMAIL_GV_REDEEM', 'The redeem code for is %s, you can enter the redeem code when checking out, after making a purchase');
define('EMAIL_GV_LINK', 'or by following this link ');
define('EMAIL_COUPON_INCENTIVE_HEADER', 'Congratulation, to make your first visit to our online shop a more rewarding experience' . "\n" . 
                                        '  below are details of a Discount Coupon created just for you' . "\n\n");
define('EMAIL_COUPON_REDEEM', 'To use the coupon enter the redeem code which is %s during checkout, ' . "\n" . 
                               'after making a purchase');

?>
