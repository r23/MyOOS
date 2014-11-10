<?php
/* ----------------------------------------------------------------------
   $Id: customers.php,v 1.3 2007/06/13 16:39:12 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: customers.php,v 1.12 2002/01/12 18:46:27 hpdl
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

define('HEADING_TITLE', 'Clienti');
define('HEADING_TITLE_SEARCH', 'Cerca:');

define('TABLE_HEADING_FIRSTNAME', 'Nome');
define('TABLE_HEADING_LASTNAME', 'Cognome');
define('TABLE_HEADING_ACCOUNT_CREATED', 'Account Creato');
define('TABLE_HEADING_ACTION', 'Azione');
define('HEADING_TITLE_STATUS', 'Stato:');
define('TEXT_ALL_CUSTOMERS', 'Tutti i clienti');
define('HEADING_TITLE_LOGIN', 'Login');

define('TEXT_INFO_HEADING_STATUS_CUSTOMER', 'Modifica Stato Cliente');
define('TEXT_NO_CUSTOMER_HISTORY', 'Nessun archivio disponibile');
define('TABLE_HEADING_NEW_VALUE', 'Nuovo valore');
define('TABLE_HEADING_OLD_VALUE', 'Vecchio valore');
define('TABLE_HEADING_CUSTOMER_NOTIFIED', 'Cliente notificato');
define('TABLE_HEADING_DATE_ADDED', 'Data di aggiunta');

define('CATEGORY_MAX_ORDER', 'Ordine massimo');
define('ENTRY_MAX_ORDER', 'Limite credito:');

define('ENTRY_VAT_ID_STATUS', 'Vat ID check');
define('ENTRY_VAT_ID_STATUS_YES', 'yes');
define('ENTRY_VAT_ID_STATUS_NO', 'no');

define('TEXT_DATE_ACCOUNT_CREATED', 'Account Creato:');
define('TEXT_DATE_ACCOUNT_LAST_MODIFIED', 'Ultima modifica:');
define('TEXT_INFO_DATE_LAST_LOGON', 'Ultima entrata:');
define('TEXT_INFO_NUMBER_OF_LOGONS', 'Numero di entrate:');
define('TEXT_INFO_COUNTRY', 'Nazione:');
define('TEXT_INFO_NUMBER_OF_REVIEWS', 'Numero di Recensioni:');
define('TEXT_DELETE_INTRO', 'Sicuro di voler cancellare questo Cliente?');
define('TEXT_DELETE_REVIEWS', 'Cancella %s recensione(i)');
define('TEXT_INFO_HEADING_DELETE_CUSTOMER', 'Cancella Cliente');
define('TYPE_BELOW', 'Tipo');
define('PLEASE_SELECT', 'Seleziona');

define('EMAIL_SUBJECT', 'Benvenuto su ' . STORE_NAME);
define('EMAIL_GREET_MR', 'Caro Sig. ');
define('EMAIL_GREET_MS', 'Cara Sig. ');
define('EMAIL_GREET_NONE', 'Caro Sig.');
define('EMAIL_WELCOME', 'Ti diamo il benvenuto su <b>' . STORE_NAME . '</b>.' . "\n\n");
define('EMAIL_TEXT', 'You can now take part in the <b>various services</b> we have to offer you. Some of these services include:' . "\n\n" . '<li><b>Permanent Cart</b> - Any products added to your online cart remain there until you remove them, or check them out.' . "\n" . '<li><b>Address Book</b> - We can now deliver your products to another address other than yours! This is perfect to send birthday gifts direct to the birthday-person themselves.' . "\n" . '<li><b>Order History</b> - View your history of purchases that you have made with us.' . "\n" . '<li><b>Products Reviews</b> - Share your opinions on products with our other customers.' . "\n\n");
define('EMAIL_CONTACT', 'For help with any of our online services, please email the store-owner: ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n\n");
define('EMAIL_WARNING', '<b>Note:</b> This email address was given to us by one of our customers. If you did not signup to be a member, please send a email to ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n");
define('EMAIL_PASSWORD_BODY', 'Your password to \'' . STORE_NAME . '\' is:' . "\n\n" . '   %s' . "\n\n");

define('EMAIL_GV_INCENTIVE_HEADER', 'As part of our welcome to new customers, we have sent you an e-Gift Voucher worth %s');
define('EMAIL_GV_REDEEM', 'The redeem code for is %s, you can enter the redeem code when checking out, after making a purchase');
define('EMAIL_GV_LINK', 'or by following this link ');
define('EMAIL_COUPON_INCENTIVE_HEADER', 'Congratulation, to make your first visit to our online shop a more rewarding experience' . "\n" .
                                        '  below are details of a Discount Coupon created just for you' . "\n\n");
define('EMAIL_COUPON_REDEEM', 'To use the coupon enter the redeem code which is %s during checkout, ' . "\n" .
                               'after making a purchase');

?>
