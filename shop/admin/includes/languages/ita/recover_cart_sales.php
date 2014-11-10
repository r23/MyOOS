<?php
/*
  Recover Cart Sales v2.11 ENGLISH Language File

  Recover Cart Sales contribution: JM Ivler (c)
  Copyright (c) 2003-2005 JM Ivler / Ideas From the Deep / OSCommerce
  http://www.oscommerce.com

  Released under the GNU General Public License

  Modifed by Aalst (recover_cart_sales.php,v 1.2 .. 1.36)
  aalst@aalst.com

  Modifed by willross (recover_cart_sales.php,v 1.4)
  reply@qwest.net
  - don't forget to flush the 'scart' db table every so often

  Modifed by Lane (stats_recover_cart_sales.php,v 1.4d .. 2.00)
  lane@ifd.com www.osc-modsquad.com / www.ifd.com
*/

/* ----------------------------------------------------------------------
   If you made a translation, please send to
      lang@oos-shop.de
   the translated file.
   ---------------------------------------------------------------------- */

define('MESSAGE_STACK_CUSTOMER_ID', 'Carrello per Id-Cliente ');
define('MESSAGE_STACK_DELETE_SUCCESS', ' cancellato con successo');
define('HEADING_TITLE', 'Recupero Carrello v2.11');
define('HEADING_EMAIL_SENT', 'Rapporto E-mail Spedite');
define('EMAIL_TEXT_LOGIN', 'Autentica col tuo account qui:');
define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('EMAIL_TEXT_SUBJECT', 'Richiesta da '.  STORE_NAME );
define('EMAIL_TEXT_SALUTATION', 'Caro ' );
define('EMAIL_TEXT_NEWCUST_INTRO', "\n\n" . 'Grazie per esserti fermato su ' . STORE_NAME .
                                   ' ed averci considerato per i tuoi acquisti. ');
define('EMAIL_TEXT_CURCUST_INTRO', "\n\n" . 'Ti vogliamo ringraziare per aver acquistato da ' .
                                   STORE_NAME . ' nel passato. ');
define('EMAIL_TEXT_BODY_HEADER',
  'Abbiamo notato che durante una visita al nostro negozio hai inserito ' .
  'i seguenti prodotti nel tuo carrello, ma non hai completato ' .
  'l\'acquisto.' . "\n\n" .
  'Contenuto Carrello:' . "\n\n"
  );

define('EMAIL_TEXT_BODY_FOOTER',
  'Siamo interessati, per migliorare la gestione e dare un servizio migliore ai nostri clienti, ' .
  'nel sapere quale sia stata la decisione che ti ha spinto ad abbandorare il carrello. ' .
  'Se sei così gentile da farci sapere i motivi ' .
  'lo aprezzeremo molto.  ' .
  'Come già scritto, stiamo cercando di capire quali possano essere i problemi per risolverli ' .
  'ed aiutare i nostri clienti ad avere un servizio migliore su '. STORE_NAME ."\n\n".
  'NOTA:'."\n".'Se pensi di aver completato l\'acquisto e che il pacco spedito sia in ritardo ' .
  ', questa e-mail allora ti indica che il tuo ordine NON è stao completato ' .
  'e non ti è stato addebitato niente! ' .
  'Se sei interessato puoi nuovamente visitare il negozio e completare il tuo ordine.'."\n\n".
  'Chiediamo le nostre scuse nell\'eventualità tu abbia già completato l\'ordine, ' .
  'in genere cerchiamo di evitare questi messaggi, ma a volte  ' .
  'è difficile per noi capire cosa succede esattamente in situazioni del genere.'."\n\n".
  'Nuovamente, ti ringraziamo per l\'aiuto e la disponibilità nella lettura di questa e-mail, e per gli eventuali consigli atti a migliorare ' .
  '' . STORE_NAME .  " website.\n\nCordiali saluti,\n\n"
  );

define('DAYS_FIELD_PREFIX', 'Visto ultimamente ');
define('DAYS_FIELD_POSTFIX', ' giorni ');
define('DAYS_FIELD_BUTTON', 'vai');
define('TABLE_HEADING_DATE', 'DATA');
define('TABLE_HEADING_CONTACT', 'CONTATTATO');
define('TABLE_HEADING_CUSTOMER', 'NOME CLIENTE');
define('TABLE_HEADING_EMAIL', 'E-MAIL');
define('TABLE_HEADING_PHONE', 'TELEFONO');
define('TABLE_HEADING_MODEL', 'PRODOTTO');
define('TABLE_HEADING_DESCRIPTION', 'DESCRIZIONE');
define('TABLE_HEADING_QUANTY', 'QTY');
define('TABLE_HEADING_PRICE', 'PREZZO');
define('TABLE_HEADING_TOTAL', 'TOTALE');
define('TABLE_GRAND_TOTAL', 'TOTALE: ');
define('TABLE_CART_TOTAL', 'Totale Carello: ');
define('TEXT_CURRENT_CUSTOMER', 'CLIENTE');
define('TEXT_SEND_EMAIL', 'Spedisci E-mail');
define('TEXT_RETURN', '[Clicca qui per tornare]');
define('TEXT_NOT_CONTACTED', 'Non contattato');
define('PSMSG', 'Messaggio PS addizionale: ');

?>
