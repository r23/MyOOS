<?php
/* ----------------------------------------------------------------------
   $Id: gv_faq.php,v 1.3 2007/06/12 16:54:23 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: gv_faq.php,v 1.1.2.1 2003/05/15 23:04:32 wilt
   ----------------------------------------------------------------------
   The Exchange Project - Community Made Shopping!
   http://www.theexchangeproject.org

   Gift Voucher System v1.0
   Copyright (c) 2001,2002 Ian C Wilson
   http://www.phesis.org
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

$aLang['navbar_title'] = 'Gutscheine, Fragen und Antworten';
$aLang['heading_title'] = 'Gutscheine, Fragen und Antworten';

$aLang['text_information'] = '
<b>Kauf von Gutscheinen</b><br />
<p>Gutscheine werden wie jedes andere Produkt in unserem Shop behandelt.
  Sie k&ouml;nnen die Gutscheine mit jeder Zahlungsmethode, welche wir in unserem Shop anbieten, bezahlen.
  Sobald die Zahlung erfolgt ist, sehen Sie den Wert des Gutscheins in Ihrem Gutscheinkonto unterhalb
  des Einkaufskorbes. Dort erscheint dann auch ein Link zu einer Seite, von der Sie die Gutscheine an
  jemand anderen per E-Mail senden k&ouml;nnen.</p>

<br /><br />

<b>Versenden von Gutscheinen</b><br/>
Um einen Gutschein zu versenden, m&uuml;ssen Sie auf die entsprechende Seite
  wechseln. Sie finden den Link zu der entsprechenden Seite unter Ihrem Warenkorb in der rechten
  Spalte oben auf jeder Seite, sofern Sie ein Guthaben auf Ihrem Gutscheinkonto haben.
  Wenn Sie einen Gutschein versenden wollen, m&uuml;ssen Sie den Namen der Person und die E-Mail-Adresse
  angeben. Sie k&ouml;nnen auch den Wert, welchen Sie versenden wollen angeben. (Achtung! Sie m&uuml;ssen nicht
  das komplette Guthaben als Gutschein versenden! So k&ouml;nnen Sie den Gutscheinbetrag auf beliebig viele
  Personen aufteilen!) Sie k&ouml;nnen auch eine kurze Nachricht, welche der Mail beigef&uuml;gt wird eingeben.
  Bitte &uuml;berpr&uuml;fen Sie alle Eingaben sorgfältig. Sie haben vor dem endg&uuml;ltigen Versand des Gutscheins
  die M&ouml;glichkeit beliebig viele Änderungen an Ihren Eingaben durchzuf&uuml;hren.</p>
<br /><br />

<b>Einkauf mit einem Gutschein</b><br />
  <p>Sofern ein Guthaben auf Ihrem Gutscheinkonto vorhanden ist, k&ouml;nnen Sie dieses
    f&uuml;r den Kauf beliebiger Produkte aus unserem Shop nutzen. Beim Abschluß der Bestellung erscheint eine
    zusätzliche Abfrage. Wenn Sie diese auswählen wird Ihr Gutschein f&uuml;r die Bestellung verwendet. Bitte
    beachten Sie, dass dennoch eine Zahlungsmethode auswählen m&uuml;ssen, falls dort nicht gen&uuml;gend Guthaben
    f&uuml;r Ihre Bestellung vorhanden ist. Sollte das Guthaben h&ouml;her als Ihre Bestellung ausfallen, bleibt
    der Restbetrag f&uuml;r k&uuml;nftige Bestellungen in unserem Shop erhalten.</p>

<br /><br />

<b>Einl&ouml;sen von Gutscheinen</b><br />
  <p>Wenn Sie einen Gutschein per E-Mail empfangen, enthält die Mail einige
  Informationen. Sie erfahren wer Ihnen den Gutschein gesendet hat. Sofern der Absender eine kurze
  Nachricht beigef&uuml;gt hat, k&ouml;nnen Sie diese ebenfalls lesen. Die E-Mail enthält ebenfalls einen Gutscheincode.
  Wir empfehlen Ihnen die Mail aus Sicherheitsgr&uuml;nden auszudrucken, oder sich zumindest den Gutscheincode
  zu notieren. Sie k&ouml;nnen den Gutschein auf zwei Arten einl&ouml;sen:<br />
  1. Sie klicken auf den Link in der Mail. Sie gelangen daraufhin auf die Gutscheinseite in unserem Shop.
     Dort k&ouml;nnen Sie sich - sofern Sie bereits bei uns Kunde sind - anmelden oder ein neues Kundenkonto
     anlegen. Danach wird der Gutscheincode identifiziert und Ihrem pers&ouml;nlichen Gutscheinkonto gutgeschrieben.
     Sie k&ouml;nnen dieses Guthaben f&uuml;r beliebige Einkäufe in unserem Shop nutzen.
  2. Bei Abschluß der Bestellung erscheint auf der Seite, bei der Sie die gew&uuml;nschte Zahlungsmethode wählen,
     ein Feld in dem Sie den Gutscheincode eingeben k&ouml;nnen. Geben Sie den Code ein und klicken Sie auf
     "Einl&ouml;sen". Der Code wird identifiziert und Ihrem pers&ouml;nlichen Gutscheinkonto gutgeschrieben. Sie k&ouml;nnen
     das Guthaben f&uuml;r beliebige Einkäufe in unserem Shop nutzen.</p>
<br /><br />

<b>Bei Problemen</b><br/>
  <p> Sollten Probleme mit dem Gutscheinsystem auftreten, &uuml;berpr&uuml;fen Sie bitte alle
  Ihre Eingaben sorgfältig! Falls Sie weiterhin Probleme mit unserem Gutscheinsystem haben, kontaktieren Sie
  uns bitte per E-Mail &uuml;ber '. STORE_OWNER_EMAIL_ADDRESS . '. Bitte geben Sie uns dabei soviele Informationen wie
  m&ouml;glich mit an! Vielen Dank!</p>

<br /><br />';


