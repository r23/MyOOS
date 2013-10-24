<?php
/**
*
* help_bbcode [Deutsch — Sie]
*
* @package language
* @version $Id: help_bbcode.php 464 2010-06-15 14:47:22Z tuxman $
* @copyright (c) 2005 phpBB Group; 2006 phpBB.de
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
* Deutsche Übersetzung durch die Übersetzer-Gruppe von phpBB.de:
* siehe docs/AUTHORS und http://www.phpbb.de/go/ubersetzerteam
*
*/

/**
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine

$help = array(
	array(
		0 => '--',
		1 => 'Einführung'
	),
	array(
		0 => 'Was ist BBCode?',
		1 => 'BBCode ist eine spezielle Umsetzung von HTML. Ob Sie BBCode in Ihren Beiträgen auf diesem Board verwenden können, wird durch die Board-Administration festgelegt. Sofern Sie es verwenden dürfen, können Sie es auch im Beitrags-Formular für diesen Beitrag deaktivieren. BBCode ist ähnlich wie HTML aufgebaut, Tags werden von eckigen Klammern („[“ und „]“) statt spitzen („&lt;“ und „&gt;“) eingeschlossen und erlauben eine bessere Kontrolle, was und wie etwas angezeigt wird. Je nach verwendetem Template werden Sie über dem Bereich für den Nachrichtentext Schaltflächen finden, die Ihnen die Verwendung von BBCode vereinfachen werden. Aber selbst dann kann die folgende Anleitung für Sie hilfreich sein.'
	),
	array(
		0 => '--',
		1 => 'Formatierung von Text'
	),
	array(
		0 => 'Wie fetter, kursiver und unterstrichener Text erstellt wird',
		1 => 'BBCode enthält Tags, die Ihnen eine schnelle Formatierung Ihres Textes ermöglichen. Dies wird folgendermaßen gemacht: <ul><li>Um einen Text Fett zu machen, schließen Sie ihn in <strong>[b][/b]</strong> ein. So wird z.&nbsp;B.<br /><br /><strong>[b]</strong>Hallo<strong>[/b]</strong><br /><br />zu <strong>Hallo</strong></li><li>Zum Unterstreichen von Text benutzen Sie <strong>[u][/u]</strong>. So wird z.&nbsp;B. aus:<br /><br /><strong>[u]</strong>Guten Morgen<strong>[/u]</strong><br /><br /><span style="text-decoration: underline">Guten Morgen</span></li><li>Um Text kursiv zu stellen, verwenden Sie <strong>[i][/i]</strong>. So wird z.&nbsp;B.<br /><br />Das ist <strong>[i]</strong>großartig!<strong>[/i]</strong><br /><br />zu Das ist <em>großartig!</em></li></ul>'
	),
	array(
		0 => 'Wie die Textfarbe oder -größe geändert wird',
		1 => 'Um die Farbe oder die Größe Ihres Textes zu ändern, können die folgenden Tags genutzt werden. Beachten Sie dabei bitte, dass es von dem Browser und dem System des Betrachters abhängig ist, wie die Darstellung des Textes erfolgt: <ul><li>Die Farbe des Textes wird geändert, in dem er in <strong>[color=][/color]</strong> eingeschlossen wird. Sie können entweder eine den Browsern bekannte Farbe wie z.&nbsp;B. red, blue, yellow, etc. angeben oder einen Farbwert aus drei zweistelligen Hexadezimalwerten wie #FFFFFF, #000000 verwenden. Um beispielsweise roten Text zu erstellen:<br /><br /><strong>[color=red]</strong>Hallo!<strong>[/color]</strong><br /><br />oder<br /><br /><strong>[color=#FF0000]</strong>Hallo!<strong>[/color]</strong><br /><br />Beides ergibt <span style="color:red">Hallo!</span>.</li><li>Die Textgröße wird in ähnlicher Weise mit <strong>[size=][/size]</strong> geändert. Dieser Tag ist abhängig von dem Template, das der Benutzer ausgewählt hat, aber die empfohlene Angabe ist ein numerischer Wert, der die Textgröße in Prozent angibt – beginnend standardmäßig mit 20 (sehr klein) und endend mit 200 (sehr groß). Zum Beispiel:<br /><br /><strong>[size=30]</strong>KLEIN<strong>[/size]</strong><br /><br />wird gewöhnlich <span style="font-size:30%;">KLEIN</span> sein,<br /><br />während:<br /><br /><strong>[size=200]</strong>GROSS!<strong>[/size]</strong><br /><br /><span style="font-size:200%;">GROSS!</span> sein wird</li></ul>'
	),
	array(
		0 => 'Kann ich Tags zur Formatierung kombinieren?',
		1 => 'Natürlich können Sie das. Um z.&nbsp;B. die Aufmerksamkeit eines anderen zu erhalten, können Sie<br /><br /><strong>[size=200][color=red][b]</strong>SCHAU MICH AN!<strong>[/b][/color][/size]</strong><br /><br />schreiben, was als <span style="color:red;font-size:200%;"><strong>SCHAU MICH AN!</strong></span> ausgegeben wird.<br /><br />Wir empfehlen jedoch nicht, viel Text derart zu formatieren! Beachten Sie, dass Sie als Autor dafür verantwortlich sind, dass die Tags richtig geschlossen werden. Zum Beispiel ist das hier falsch:<br /><br /><strong>[b][u]</strong>Das ist falsch<strong>[/b][/u]</strong>'
	),
	array(
		0 => '--',
		1 => 'Zitieren und Ausgabe von Text mit fester Weite'
	),
	array(
		0 => 'Text in Antworten zitieren',
		1 => 'Es gibt zwei Arten, Text zu zitieren: mit Quelle und ohne.<ul><li>Wenn Sie die „Mit Zitat antworten“-Funktion zur Antwort auf einen Beitrag verwenden, werden Sie feststellen, dass der alte Beitragstext von <strong>[quote=&quot;&quot;][/quote]</strong> umschlossen zum Nachrichtentext hinzugefügt wird. Dies erlaubt Ihnen unter Angabe einer Referenz zu einer Person oder zu etwas anderem von Ihnen gewähltem zu zitieren. Um z.&nbsp;B. einen Text von Herrn Klecks zu zitieren, sollten Sie Folgendes eingeben:<br /><br /><strong>[quote=&quot;Herr Klecks&quot;]</strong>Der Text von Herrn Klecks würde hier stehen<strong>[/quote]</strong><br /><br />Bei der Ausgabe wird dem Text automatisch „Herr Klecks hat geschrieben:“ vorangestellt. Beachten Sie, dass Sie den Namen in Anführungszeichen (&quot;&quot;) einschließen <strong>müssen</strong>, sie sind nicht optional.</li><li>Die zweite Methode erlaubt Ihnen, etwas ohne Quellangabe zu zitieren. Dazu müssen Sie den Text in <strong>[quote][/quote]</strong> einschließen. Wenn Sie die Nachricht anschauen, wird der Text in einem Zitat-Block angezeigt.</li></ul>'
	),
	array(
		0 => 'Programmcode oder Daten mit fester Weite ausgeben',
		1 => 'Wenn Sie einen Auszug eines Programmcodes oder etwas anderes, das eine feste Textweite wie eine Courier-Schrift benötigt, eingeben möchten, sollten Sie den Text in <strong>[code][/code]</strong> einschließen, z.&nbsp;B.<br /><br /><strong>[code]</strong>echo &quot;Das ist ein Stück Programmcode&quot;;<strong>[/code]</strong><br /><br />Alle Formatierungen, die innerhalb von <strong>[code][/code]</strong> genutzt werden, bleiben erhalten, wenn der Text betrachtet wird. Die Syntaxhervorhebung für PHP kann mit <strong>[code=php][/code]</strong> aktiviert werden; dies ist immer dann zu empfehlen, wenn PHP-Code gepostet wird, da so die Lesbarkeit verbessert wird.'
	),
	array(
		0 => '--',
		1 => 'Listen erstellen'
	),
	array(
		0 => 'Eine unsortierte Liste erstellen',
		1 => 'BBCode unterstützt zwei Arten von Listen: unsortierte und sortierte. Sie sind im Wesentlichen identisch zu ihren HTML-Entsprechungen. Eine unsortierte Liste gibt die Elemente Ihrer Liste hintereinander durch einen Aufzählungspunkt gekennzeichnet aus. Verwenden Sie <strong>[list][/list]</strong>, um eine unsortierte Liste zu erstellen und beginnen Sie jeden Aufzählungspunkt mit <strong>[*]</strong>. Um zum Beispiel Ihre Lieblingsfarben aufzulisten, verwenden Sie:<br /><br /><strong>[list]</strong><br /><strong>[*]</strong>Rot<br /><strong>[*]</strong>Blau<br /><strong>[*]</strong>Gelb<br /><strong>[/list]</strong><br /><br />Dies würde folgende Liste ergeben:<ul><li>Rot</li><li>Blau</li><li>Gelb</li></ul>'
	),
	array(
		0 => 'Eine sortierte Liste erstellen',
		1 => 'Die zweite Listenart, die sortierte Liste, erlaubt Ihnen, festzulegen, was den Punkten vorangestellt wird. Um eine sortierte Liste zu erstellen, verwenden Sie <strong>[list=1][/list]</strong> für eine nummerierte oder alternativ <strong>[list=a][/list]</strong> für eine alphabetische Liste. Wie bei der unsortierten Liste wird jeder Punkt durch <strong>[*]</strong> festgelegt. Zum Beispiel:<br /><br /><strong>[list=1]</strong><br /><strong>[*]</strong>Gehe einkaufen<br /><strong>[*]</strong>Kaufe einen neuen Computer<br /><strong>[*]</strong>Verfluche den Computer, wenn er abstürzt<br /><strong>[/list]</strong><br /><br />würde ergeben:<ol style="list-style-type: decimal;"><li>Gehe einkaufen</li><li>Kaufe einen neuen Computer</li><li>Verfluche den Computer, wenn er abstürzt</li></ol>Eine alphabetische Liste würden Sie hingegen wie folgt erstellen:<br /><br /><strong>[list=a]</strong><br /><strong>[*]</strong>Die erste mögliche Antwort<br /><strong>[*]</strong>Die zweite mögliche Antwort<br /><strong>[*]</strong>Die dritte mögliche Antwort<br /><strong>[/list]</strong><br /><br />ergibt<ol style="list-style-type: lower-alpha"><li>Die erste mögliche Antwort</li><li>Die zweite mögliche Antwort</li><li>Die dritte mögliche Antwort</li></ol>'
	),
	// This block will switch the FAQ-Questions to the second template column
	array(
		0 => '--',
		1 => '--'
	),
	array(
		0 => '--',
		1 => 'Links erstellen'
	),
	array(
		0 => 'Links auf eine andere Website',
		1 => 'phpBBs BBCode unterstützt mehrere Wege, um URIs (Uniform Resource Indicators), auch als URLs oder Links bekannt, zu erstellen.<ul><li>Der erste davon ist der <strong>[url=][/url]</strong>-Tag; alles, was Sie nach dem =-Zeichen angeben, wird als Link für den Inhalt des Tags verwendet. Um beispielsweise auf phpBB.de zu linken, können Sie folgenden Code verwenden:<br /><br /><strong>[url=http://www.phpbb.de/]</strong>Besuche phpBB.de!<strong>[/url]</strong><br /><br />Dies würde folgenden Link erstellen: <a href="http://www.phpbb.de/">Besuche phpBB.de!</a> Die Seite wird abhängig von den Einstellungen des Browsers des Benutzers im gleichen oder in einem neuen Fenster geöffnet.</li><li>Wenn Sie möchten, dass die URL selbst als Beschreibung angezeigt wird, können Sie dies wie folgt erreichen:<br /><br /><strong>[url]</strong>http://www.phpbb.de/<strong>[/url]</strong><br /><br />Dies würde folgenden Link erstellen: <a href="http://www.phpbb.de/">http://www.phpbb.de/</a></li><li>phpBB hat zusätzlich eine Funktion, um Links automatisch zu erkennen. Diese Funktion wandelt jede syntaktisch korrekte URL in einen Link um, ohne dass Sie einen Tag oder sogar das führende http:// angeben müssen. Wenn Sie zum Beispiel in Ihrem Text www.phpbb.de schreiben, so wird dies automatisch zu <a href="http://www.phpbb.de/">www.phpbb.de</a>, sobald Sie die Nachricht betrachten.</li><li>Die selbe Funktionalität steht auch bei E-Mail-Adressen zur Verfügung. Sie können entweder eine Adresse explizit kennzeichnen; z.&nbsp;B.:<br /><br /><strong>[email]</strong>niemand@domain.tld<strong>[/email]</strong><br /><br />Dies ergibt <a href="mailto:niemand@domain.tld">niemand@domain.tld</a>. Oder Sie können direkt niemand@domain.tld in Ihrer Nachricht verwenden, was automatisch umgewandelt wird, wenn Sie die Nachricht anschauen.</li></ul>Wie bei allen BBCode-Tags können Sie URLs all den anderen Tags wie <strong>[img][/img]</strong> (siehe nächsten Eintrag), <strong>[b][/b]</strong> usw. hinterlegen. Wie bei den Formatierungs-Tags liegt es an Ihnen, die richtige Reihenfolge beim Öffnen und beim Schließen einzuhalten. So ist zum Beispiel:<br /><br /><strong>[url=http://www.google.de/][img]</strong>http://www.google.com/intl/de_de/images/logo.gif<strong>[/url][/img]</strong><br /><br /><span style="text-decoration: underline">nicht</span> richtig und kann dazu führen, dass Ihr Beitrag gelöscht wird.'
	),
	array(
		0 => '--',
		1 => 'Bilder in Beiträgen anzeigen'
	),
	array(
		0 => 'Ein Bild zu einem Beitrag hinzufügen',
		1 => 'phpBBs BBCode hat einen Tag, um Bilder in einem Beitrag einzufügen. Dabei müssen zwei wichtige Punkte beachtet werden: erstens mögen es viele Benutzer nicht, wenn viele Bilder in einem Beitrag enthalten sind und zweitens muss das Bild, das Sie anzeigen wollen, bereits im Internet verfügbar sein (es darf also nicht nur auf Ihrem Computer liegen, sofern Sie ihn nicht als Webserver betreiben!). Um ein Bild anzuzeigen, müssen Sie die URL, die auf das Bild verweist, mit dem <strong>[img][/img]</strong>-Tag umschließen. Zum Beispiel:<br /><br /><strong>[img]</strong>http://www.google.com/intl/de_de/images/logo.gif<strong>[/img]</strong><br /><br />Wie schon im Abschnitt über Links erwähnt, können Sie ein Bild auch innerhalb des <strong>[url][/url]</strong>-Tags verwenden:<br /><br /><strong>[url=http://www.google.de/][img]</strong>http://www.google.com/intl/de_de/images/logo.gif<strong>[/img][/url]</strong><br /><br />ergibt:<br /><br /><a href="http://www.google.de/"><img src="http://www.google.com/intl/de_de/images/logo.gif" alt="" /></a>'
	),
	array(
		0 => 'Dateien zu einem Beitrag hinzufügen',
		1 => 'Dateianhänge können nun mit dem neuen <strong>[attachment=][/attachment]</strong>-Tag an jeder Stelle eines Beitrags eingefügt werden, sofern Dateianhänge durch die Board-Administration zugelassen wurden und Sie ausreichende Rechte zur Erstellung eines Dateianhangs haben. Innerhalb des Formulars zur Beitragserstellung finden Sie eine Auswahlliste (oder eine Schaltfläche), um Dateianhänge innerhalb des Beitrags zu platzieren.'
	),
	array(
		0 => '--',
		1 => 'Andere Punkte'
	),
	array(
		0 => 'Kann ich meine eigenen Tags hinzufügen?',
		1 => 'Wenn Sie ein Administrator mit entsprechenden Rechten auf diesem Board sind, können Sie im Bereich für benutzerdefinierte BBCode-Tags weitere Tags definieren.'
	)
);

?>