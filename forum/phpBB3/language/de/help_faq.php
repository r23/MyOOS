<?php
/**
*
* help_faq [Deutsch — Du]
*
* @package language
* @version $Id: help_faq.php 617 2013-09-29 10:21:18Z pyramide $
* @copyright (c) 2005 phpBB Group; 2006 phpBB.de
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
* Deutsche Übersetzung durch die Übersetzer-Gruppe von phpBB.de:
* siehe docs/AUTHORS und https://www.phpbb.de/go/ubersetzerteam
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
		1 => 'Registrierung und Anmeldung'
	),
	array(
		0 => 'Warum kann ich mich nicht anmelden?',
		1 => 'Dafür gibt es viele mögliche Gründe. Versichere dich zunächst, dass dein Benutzername und dein Passwort richtig sind. Wenn dies der Fall ist, wende dich an einen Administrator, um sicherzugehen, dass du nicht gesperrt wurdest. Es ist ebenfalls möglich, dass ein Konfigurationsproblem mit der Website vorliegt, welches ein Administrator lösen muss.'
	),
	array(
		0 => 'Wozu muss ich mich überhaupt registrieren?',
		1 => 'Eine Registrierung ist nicht unbedingt zwingend. Die Board-Administration dieses Forums entscheidet, ob du registriert sein musst, um Beiträge zu schreiben. Auf jeden Fall erhältst du als registriertes Mitglied zusätzliche Funktionen, die Gäste nicht haben: zum Beispiel Avatarbilder, Private Nachrichten, E-Mail-Versand an andere Mitglieder, Beitritt zu Benutzergruppen und so weiter. Wir empfehlen dir eine Anmeldung, da sie schnell erledigt ist und dir zahlreiche Vorteile bringt.'
	),
	array(
		0 => 'Warum werde ich automatisch abgemeldet?',
		1 => 'Wenn du beim Anmelden das Kontrollkästchen „Mich bei jedem Besuch automatisch anmelden“ nicht auswählst, wirst du nur für eine Sitzung angemeldet. Dies verhindert den Missbrauch deines Benutzerkontos durch einen Dritten. Um angemeldet zu bleiben, kannst du dieses Kästchen beim Anmelden auswählen. Dies ist nicht empfehlenswert, wenn du dich an einem öffentlichen Computer, zum Beispiel in einem Internetcafé, befindest. Wenn diese Option nicht zur Verfügung steht, dann wurde sie vermutlich von der Board-Administration ausgeschaltet.'
	),
	array(
		0 => 'Wie kann ich verhindern, dass mein Benutzername in der Online-Liste auftaucht?',
		1 => 'In deinem persönlichen Bereich findest du in den Einstellungen eine Option „Verbirg meinen Online-Status“. Wenn du diese Option einschaltest, können nur Administratoren, Moderatoren und du selbst deinen Online-Status sehen. Du wirst dann als unsichtbarer Besucher gezählt.'
	),
	array(
		0 => 'Ich habe mein Passwort vergessen!',
		1 => 'Das ist nicht schlimm! Wir können dir zwar dein altes Passwort nicht wieder mitteilen, du kannst es jedoch zurücksetzen. Dies machst du, indem du auf der Anmelde-Seite auf „Ich habe mein Passwort vergessen“ klickst und den Anweisungen folgst. So solltest du dich schnell wieder anmelden können.'
	),
	array(
		0 => 'Ich habe mich registriert, kann mich aber nicht anmelden!',
		1 => 'Überprüfe zuerst, ob du den richtigen Benutzernamen und das richtige Passwort eingegeben hast. Wenn diese stimmen, dann gibt es zwei Möglichkeiten. Wenn <a href="faq.php#f07">COPPA</a> aktiviert ist und du angegeben hast, dass du unter 13 Jahre alt bist, musst du bzw. einer deiner Eltern oder deiner Erziehungsberechtigten den Anweisungen folgen, die du erhalten hast. Wenn dies nicht der Fall ist, muss dein Benutzerkonto vielleicht aktiviert werden. Bei einigen Boards müssen alle neu angemeldeten Mitglieder erst freigeschaltet werden – entweder musst du dies selbst erledigen oder ein Administrator. Bei der Registrierung wurde dir mitgeteilt, ob eine Aktivierung nötig ist oder nicht. Wenn du eine E-Mail erhalten hast, folge den dort enthaltenen Anweisungen. Ansonsten prüfe, ob du deine E-Mail-Adresse korrekt eingegeben hast oder die E-Mail von einem Spam-Filter blockiert wurde. Wenn du dir sicher bist, dass deine E-Mail-Adresse korrekt eingegeben wurde, dann kontaktiere einen Administrator.'
	),
	array(
		0 => 'Ich habe mich vor einiger Zeit registriert, kann mich aber nicht mehr anmelden?!',
		1 => 'Es kann sein, dass ein Administrator dein Benutzerkonto aus verschieden Gründen deaktiviert oder gelöscht hat. Außerdem löschen viele Boards regelmäßig Benutzer, die für längere Zeit keine Beiträge geschrieben haben, um die Datenbankgröße zu verringern. Registriere dich einfach erneut und nimm aktiv an den Diskussionen teil!'
	),
	array(
		0 => 'Was ist COPPA?',
		1 => 'COPPA, ausgeschrieben Child Online Privacy and Protection Act of 1998 (deutsch: Gesetz zum Schutz der Privatsphäre von Kindern im Internet von 1998) ist ein Gesetz in den USA, welches festlegt, dass Websites, die möglicherweise persönliche Daten von Kindern unter 13 Jahren erheben, hierzu die Zustimmung der Eltern beziehungsweise des oder der Erziehungsberechtigten benötigen. Wenn du dir unsicher bist, ob dies auf dich oder die Website, auf der du dich zu registrieren versuchst, zutrifft, ziehe einen rechtlichen Beistand zu Rate. Bitte beachte, dass das phpBB-Team keine Rechtsberatung anbieten kann und nicht die Anlaufstelle für Rechtsangelegenheiten jeglicher Art ist; außer solchen, die weiter unten behandelt werden.'
	),
	array(
		0 => 'Warum kann ich mich nicht registrieren?',
		1 => 'Es kann sein, dass deine IP-Adresse oder der Benutzername, mit dem du dich anmelden möchtest, von der Board-Administration gesperrt wurde. Die Registrierung könnte außerdem komplett ausgeschaltet sein, damit sich keine neuen Benutzer mehr anmelden können. Um Hilfe zu erhalten, wende dich an die Board-Administration.'
	),
	array(
		0 => 'Wozu ist die „Alle Cookies des Boards löschen“-Funktion?',
		1 => '„Alle Cookies des Boards löschen“ löscht die Cookies, die phpBB erstellt hat und die dafür sorgen, dass du im Forum angemeldet bleibst. Außerdem ermöglichen sie einige Funktionen, wie beispielsweise den „Gelesen“-Status – sofern von der Administration aktiviert. Wenn du Probleme bei der An- oder Abmeldung hast, kann es helfen, wenn du die Cookies des Boards löscht.'
	),
	array(
		0 => '--',
		1 => 'Benutzerpräferenzen und -einstellungen'
	),
	array(
		0 => 'Wie kann ich meine Einstellungen ändern?',
		1 => 'Wenn du dich registriert hast, werden alle deine Einstellungen in der Datenbank des Boards gespeichert. Um diese zu ändern, gehe in den „Persönlichen Bereich“; der Link dazu wird meist oben auf der Seite angezeigt. Dort kannst du alle deine Einstellungen ändern.'
	),
	array(
		0 => 'Die Forenuhr geht falsch!',
		1 => 'Möglicherweise entspricht die angezeigte Zeit nicht deiner eigenen Zeitzone. In diesem Fall solltest du im „Persönlichen Bereich“ die für dich passende Zeitzone (Mitteleuropäische Zeit, ...) festlegen. Die Zeitzone kann dabei nur von registrierten Benutzern geändert werden. Wenn du noch nicht registriert bist, ist dies ein guter Grund, dies jetzt zu tun.'
	),
	array(
		0 => 'Ich habe die Zeitzone eingestellt, aber die Forenuhr geht immer noch falsch!',
		1 => 'Wenn du dir sicher bist, dass du die Zeitzone und die Sommerzeit richtig eingestellt hast und die Zeit trotzdem noch falsch ist, geht die Uhr des Servers vermutlich falsch. Kontaktiere einen Administrator, damit er das Problem beheben kann.'
		),
	array(
		0 => 'Meine Sprache steht auf diesem Board nicht zur Auswahl!',
		1 => 'Meist hat die Board-Administration entweder deine Sprache nicht installiert oder niemand hat das Forum bislang in deine Sprache übersetzt. Frage ggf. einen Administrator, ob er das Sprachpaket, das du benötigst, installieren kann. Falls es noch nicht existiert, würden wir uns freuen, wenn du es übersetzen würdest. Weitere Informationen dazu können auf der Website der phpBB Group gefunden werden (siehe Link am Ende jeder Seite).'
	),
	array(
		0 => 'Wie kann ich ein Bild bei meinem Benutzernamen anzeigen?',
		1 => 'In der Beitragsansicht können zwei Bilder bei deinem Benutzernamen stehen. Eines dieser Bilder ist meist mit deinem Rang verknüpft: Oft sind dies Sterne, Kästchen oder Punkte, die deine Beitragszahl oder deinen Status im Forum angeben. Das andere, meist größere Bild, ist auch als „Avatar“ bezeichnet. Es handelt sich hierbei in der Regel um ein persönliches Bild, welches von Benutzer zu Benutzer unterschiedlich ist. Die Board-Administration kann bestimmen, ob und wie die Benutzer Avatare benutzen können. Wenn du keinen Avatar benutzen darfst, solltest du die Board-Administration nach den Gründen dafür fragen.'
	),
	array(
		0 => 'Was ist mein Rang und wie kann ich ihn ändern?',
		1 => 'Ränge, die unter deinem Benutzernamen stehen, zeigen an, wie viele Beiträge du bislang erstellt hast oder identifizieren bestimmte Benutzer wie Moderatoren und Administratoren. Normalerweise kannst du den Wortlaut eines Ranges nicht direkt ändern, da sie von der Board-Administration festgelegt wurden. Bitte schreibe keine sinnlosen Beiträge, nur um deinen Rang zu erhöhen — die meisten Boards dulden dieses Verhalten nicht und ein Moderator oder Administrator wird deinen Rang unter Umständen einfach wieder zurücksetzen.'
	),
	array(
		0 => 'Wenn ich bei einem Benutzer auf den E-Mail-Link klicke, werde ich aufgefordert, mich anzumelden.',
		1 => 'Nur registrierte Benutzer dürfen die foreninterne E-Mail-Funktion für Nachrichten an andere Benutzer nutzen, falls diese von der Board-Administration freigeschaltet wurde. Diese Maßnahme soll den Missbrauch dieses Systems durch Gäste verhindern.'
	),
	array(
		0 => '--',
		1 => 'Beiträge schreiben'
	),
	array(
		0 => 'Wie schreibe ich ein Thema?',
		1 => 'Um eine neues Thema in einem Forum zu eröffnen, klicke auf das entsprechende Symbol, entweder in der Foren- oder der Beitragsansicht. Es könnte sein, dass eine Registrierung erforderlich ist, bevor du einen Beitrag schreiben kannst. Deine Berechtigungen sind jeweils am Ende der Foren- und der Beitragsansicht aufgelistet. Z.&nbsp;B. „Du darfst neue Themen erstellen“, „Du darfst an Abstimmungen in diesem Forum teilnehmen“ usw.'
	),
	array(
		0 => 'Wie kann ich einen Beitrag bearbeiten oder löschen?',
		1 => 'Wenn du nicht Administrator oder Moderator bist, kannst du nur deine eigenen Beiträge bearbeiten oder löschen. Du kannst einen Beitrag bearbeiten, indem du das „Ändere Beitrag“-Symbol für den entsprechenden Beitrag anklickst; eventuell ist dies nur für einen begrenzten Zeitraum nach seiner Erstellung möglich. Wenn bereits jemand auf deinen Beitrag geantwortet hat, wird dein Beitrag in der Themenansicht als überarbeitet gekennzeichnet. Es wird sowohl die Anzahl als auch der letzte Zeitpunkt der Bearbeitungen angezeigt. Dieser Hinweis erscheint nicht, wenn noch niemand auf deinen Beitrag geantwortet hat oder wenn ein Administrator oder Moderator deinen Beitrag überarbeitet hat. Diese können jedoch, falls sie es für nötig halten, eine Notiz hinterlassen, warum dein Beitrag überarbeitet wurde. Bitte beachte, dass normale Benutzer einen Beitrag nicht löschen können, wenn bereits jemand darauf geantwortet hat.'
	),
	array(
		0 => 'Wie kann ich meinem Beitrag eine Signatur anfügen?',
		1 => 'Um eine Signatur an deinen Beitrag anzufügen, musst du zunächst eine solche in den Einstellungen in deinem persönlichen Bereich entwerfen. Nachdem du die Signatur erstellt und gespeichert hast, kannst du in jedem Beitrag das Kästchen „Signatur anhängen“ aktivieren. Du kannst eine Signatur auch hinzufügen, indem du in deinem persönlichen Bereich das standardmäßige Anhängen deiner Signatur aktivierst. Wenn du einen einzelnen Beitrag dennoch ohne Signatur verfassen möchtest, so kannst du dort einfach das Kontrollkästchen „Signatur anhängen“ wieder deaktivieren.'
	),
	array(
		0 => 'Wie kann ich eine Umfrage erstellen?',
		1 => 'Wenn du ein neues Thema eröffnest oder den ersten Beitrag eines Themas bearbeitest, findest du ein Register „Umfrage erstellen“ unterhalb des Formulars zur Beitragserstellung. Solltest du diesen Bereich nicht sehen können, so hast du wahrscheinlich nicht die Berechtigung, Umfragen zu erstellen. Du solltest einen Titel und mindestens zwei Antwortmöglichkeiten in die entsprechenden Felder eingeben und dabei sicherstellen, dass jede Antwortmöglichkeit in einer eigenen Zeile steht. Du kannst auch unter „Auswahlmöglichkeiten pro Benutzer“ festlegen, wie viele Optionen ein Benutzer auswählen kann, welches Zeitlimit für die Umfrage gilt (0 bedeutet dabei eine zeitlich unbegrenzte Umfrage) und schließlich, ob die Benutzer ihre Stimme ändern können.'
	),
	array(
		0 => 'Wieso kann ich nicht mehr Antwortmöglichkeiten erstellen?',
		1 => 'Die maximal zulässige Zahl von Antwortmöglichkeiten wird durch die Board-Administration festgelegt. Wenn du glaubst, mehr Antwortmöglichkeiten als zugelassen zu benötigen, kontaktiere einen Administrator.'
	),
	array(
		0 => 'Wie bearbeite oder lösche ich eine Umfrage?',
		1 => 'Wie bei den Beiträgen können Umfragen nur vom ursprünglichen Verfasser, einem Moderator oder einem Administrator bearbeitet werden. Um eine Umfrage zu bearbeiten, ändere den ersten Beitrag des Themas; dieser ist immer mit der Umfrage verknüpft. Wenn niemand eine Stimme abgegeben hat, dann können Benutzer die Umfrage löschen oder die Umfrageoption bearbeiten. Sollte allerdings schon ein Benutzer abgestimmt haben, so kann die Umfrage nur noch von Moderatoren oder Administratoren geändert oder gelöscht werden. Dadurch soll die Manipulation von laufenden Umfragen verhindert werden.'
	),
	array(
		0 => 'Warum kann ich auf bestimmte Foren nicht zugreifen?',
		1 => 'Manche Foren können bestimmten Benutzern oder Gruppen vorbehalten sein. Um diese einzusehen, Beiträge zu lesen, zu schreiben oder andere Vorgänge durchzuführen, brauchst du möglicherweise besondere Berechtigungen. Frage einen Moderator oder Administrator nach entsprechenden Berechtigungen.'
	),
	array(
		0 => 'Weshalb kann ich keine Dateianhänge anfügen?',
		1 => 'Rechte für Dateianhänge können für Foren, Gruppen und einzelne Benutzer vergeben werden. Die Board-Administration hat es möglicherweise nicht erlaubt, Dateianhänge in dem Forum anzufügen, in dem du deinen Beitrag verfassen möchtest, oder nur bestimmte Gruppen dürfen Dateien hochladen. Du kannst einen Administrator kontaktieren, falls du dir nicht sicher bist, wieso du keine Dateianhänge anfügen kannst.'
	),
	array(
		0 => 'Weshalb wurde ich verwarnt?',
		1 => 'In jedem Board gibt es eigene Regeln, die meistens von der Administration festgelegt werden. Wenn du gegen eine dieser Regeln verstoßen hast, kann sie dir eine Verwarnung erteilen. Bitte beachte, dass dies die Entscheidung der Administration dieses Boards ist und die phpBB Group nichts mit dieser Verwarnung zu tun hat. Kontaktiere einen Administrator, sofern du die nicht sicher bist, wieso du verwarnt wurdest.'
	),
	array(
		0 => 'Wie kann ich Beiträge den Moderatoren melden?',
		1 => 'Wenn ein Administrator die entsprechenden Berechtigungen vergeben hat, siehst du eine Schaltfläche in der Nähe des Beitrags, um diesen zu melden. Du wirst dann durch die weiteren Schritte geführt.'
	),
	array(
		0 => 'Was bewirkt die „Speichern“-Schaltfläche beim Schreiben eines Beitrags?',
		1 => 'Hiermit kannst du die geschriebene Entwürfe speichern und zu einem späteren Zeitpunkt vervollständigen und absenden. Den gesicherten Beitrag kannst du mit der Funktion „Gespeicherte Entwürfe verwalten“ in deinem persönlichen Bereich erneut laden.'
	),
	array(
		0 => 'Warum muss mein Beitrag erst freigegeben werden?',
		1 => 'Die Board-Administration kann entschieden haben, dass in dem Forum, in dem du einen Beitrag erstellt hast, die Beiträge zuerst geprüft werden müssen. Es ist auch möglich, dass die Administration dich zu einer Gruppe von Benutzern hinzugefügt hat, bei denen sie die Beiträge erst begutachten möchte, bevor sie auf der Seite sichtbar werden. Bitte kontaktiere die Board-Administration, wenn du weitere Informationen dazu benötigst.'
	),
	array(
		0 => 'Wie markiere ich ein Thema als neu?',
		1 => 'Durch Klicken des „Thema als neu markieren“-Links in der Beitragsansicht kannst du das Thema wieder ganz nach oben auf die erste Seite des Forums holen. Wenn du den entsprechenden Link nicht siehst, dann ist die Funktion möglicherweise deaktiviert oder seit der letzten Markierung ist nicht genügend Zeit vergangen. Es ist auch möglich, das Thema nach oben zu holen, indem du einfach eine Antwort darauf schreibst. Stelle jedoch sicher, dass du die Regeln dieses Boards beachtest! Es wird meist nicht gerne gesehen, wenn ohne triftigen Grund auf alte oder abgeschlossene Themen geantwortet wird.'
	),
	array(
		0 => '--',
		1 => 'Textformatierung und Thementypen'
	),
	array(
		0 => 'Was ist BBCode?',
		1 => 'BBCode ist eine spezielle Umsetzung von HTML, die dir weitreichende Formatierungsmöglichkeiten für deinen Text gibt. Die Rechte zur Verwendung von BBCode werden durch die Board-Administration vergeben, können jedoch auch durch dich für jeden einzelnen Beitrag deaktiviert werden. BBCode ist ähnlich wie HTML aufgebaut, jedoch werden Tags von eckigen („[“ und „]“) statt spitzen („&lt;“ und „&gt;“) Klammern eingeschlossen. Weitere Informationen zu BBCode findest du auf einer speziellen Hilfe-Seite, die von der Seite zur Beitragserstellung aus zugänglich ist.'
	),
	array(
		0 => 'Kann ich HTML benutzen?',
		1 => 'Nein, es ist nicht möglich, HTML-Code in Beiträgen zu verwenden. Die meisten Formatierungsmöglichkeiten, die HTML bietet, können über BBCode erreicht werden.'
	),
	array(
		0 => 'Was sind Smilies?',
		1 => 'Smilies sind kleine Bilder, die benutzt werden können, um ein Gefühl auszudrücken. Für jeden Smilie gibt es einen kurzen Code, z.&nbsp;B. bedeutet :) fröhlich und :( traurig. Die Liste aller Smilies kannst du beim Verfassen eines Beitrags sehen. Versuche bitte trotzdem, Smilies nicht zu häufig zu benutzen, sie können einen Beitrag schnell unlesbar machen und ein Moderator könnte deshalb deinen Beitrag entsprechend überarbeiten oder gar komplett löschen. Die Board-Administration kann auch die Anzahl der Smilies begrenzen, die du in einem Beitrag benutzen kannst.'
	),
	array(
		0 => 'Kann ich Bilder in meine Beiträge einfügen?',
		1 => 'Ja, Bilder können in deinem Beitrag angezeigt werden. Wenn die Administration Dateianhänge erlaubt hat, kannst du das Bild auch direkt hochladen. Ansonsten musst du zu einem Bild verlinken, das auf einem öffentlich zugänglichen Server liegt, z.&nbsp;B. http://www.domain.tld/mein-bild.gif. Du kannst weder Bilder verlinken, die sich auf deinem eigenen PC befinden (außer es ist ein öffentlich zugänglicher Server), noch zu Bildern, die nur nach einer Anmeldung verfügbar sind, z.&nbsp;B. Hotmail- oder Yahoo-Mailboxen, mit einem Passwort geschützte Seiten usw. Um das Bild anzuzeigen, benutze den BBCode-Tag „[img]“.'
	),
	array(
		0 => 'Was sind globale Bekanntmachungen?',
		1 => 'Globale Bekanntmachungen beinhalten wichtige Informationen, deshalb solltest du sie so bald wie möglich lesen. Globale Bekanntmachungen erscheinen ganz oben in jedem Forum und ebenfalls in deinem persönlichen Bereich. Ob du eine globale Bekanntmachung schreiben kannst oder nicht, hängt von den durch die Board-Administration vergebenen Berechtigungen ab.'
	),
	array(
		0 => 'Was sind Bekanntmachungen?',
		1 => 'Bekanntmachungen beinhalten meist wichtige Informationen über den Bereich, den du gerade liest. Du solltest sie stets lesen. Bekanntmachungen erscheinen oben auf jeder Seite des Forums, in dem sie erstellt wurden. Wie bei globalen Bekanntmachungen hängt es von deinen Befugnissen ab, ob du Bekanntmachungen erstellen kannst oder nicht; die Befugnisse stellt die Board-Administration ein.'
	),
	array(
		0 => 'Was sind wichtige Themen?',
		1 => 'Wichtige Themen eines Forums erscheinen unter den Ankündigungen und sind nur auf der ersten Seite zu sehen. Sie haben meist einen wichtigen Inhalt, weswegen du sie lesen solltest. Wie bei den Bekanntmachungen hängt es von deinen Befugnissen ab, ob du wichtige Themen erstellen kannst oder nicht; die Befugnisse stellt die Board-Administration ein.'
	),
	array(
		0 => 'Was sind geschlossene Themen?',
		1 => 'Geschlossene Themen sind Themen, in denen nicht mehr geantwortet werden kann und bei denen eine laufende Umfrage, falls vorhanden, beendet wurde. Themen können aus vielen Gründen durch einen Moderator oder Administrator gesperrt werden. Eventuell hast du auch die Möglichkeit, deine eigenen Themen zu schließen, sofern dies durch die Board-Administration erlaubt wurde.'
	),
	array(
		0 => 'Was sind Themen-Symbole?',
		1 => 'Themen-Symbole sind vom Autor ausgewählte Bilder, welche mit einem Thema in Verbindung stehen können, um dessen Inhalt kennzeichnen zu können. Die Möglichkeit, Themen-Symbole zu verwenden, hängt von deinen Berechtigungen ab, die die Board-Administration gesetzt hat.'
	),
	// This block will switch the FAQ-Questions to the second template column
	array(
		0 => '--',
		1 => '--'
	),
	array(
		0 => '--',
		1 => 'Benutzer-Stufen und Gruppen'
	),
	array(
		0 => 'Was sind Administratoren?',
		1 => 'Administratoren haben die umfassendsten Rechte im Forum. Sie können jede Art von Aktion im Forum ausführen; z.&nbsp;B. Berechtigungen setzen, Mitglieder sperren, Benutzergruppen erstellen, Moderationsrechte vergeben usw. Die Rechte, die ein Administrator hat, sind allerdings davon abhängig, welche Rechte ihnen ein Gründer des Forums oder ein anderer Administrator erteilt hat. Administratoren können auch volle Moderatorenbefugnisse haben, wenn ihnen das entsprechende Recht erteilt wurde.'
	),
	array(
		0 => 'Was sind Moderatoren?',
		1 => 'Die Aufgabe der Moderatoren ist es, das Geschehen im Forum zu beobachten. Sie haben das Recht, in ihrem Bereich Beiträge zu ändern und zu löschen und Themen zu schließen, zu öffnen, zu verschieben und zu teilen. Üblicherweise verhindern Moderatoren, dass Mitglieder „offtopic“, d.&nbsp;h. etwas nicht zum Thema Passendes, oder Beleidigendes bzw. Angreifendes schreiben.'
	),
	array(
		0 => 'Was sind Benutzergruppen?',
		1 => 'Benutzergruppen sind Gruppen von Mitgliedern, die die Mitglieder des Boards in für die Board-Administration verwaltbare Einheiten aufteilt. Jedes Mitglied kann mehreren Gruppen angehören und jeder Gruppe können Berechtigungen zugeteilt werden. Dies erleichtert es den Administratoren, Berechtigungen für mehrere Benutzer auf einmal zu ändern und sie zum Beispiel zu Moderatoren eines Bereichs zu machen oder ihnen Zugriff zu einem nichtöffentlichen Forum zu geben.'
	),
	array(
		0 => 'Wo finde ich die Benutzergruppen und wie trete ich ihnen bei?',
		1 => 'Du findest die Benutzergruppen unter „Benutzergruppen“ im persönlichen Bereich. Wenn du einer beitreten möchtest, kannst du dies mit der entsprechenden Schaltfläche machen. Nicht alle Gruppen sind allgemein offen. Einige erfordern erst eine Freischaltung, andere können geschlossen sein und weitere sogar versteckt. Wenn die Gruppe offen ist, kannst du ihr einfach durch die entsprechende Funktion beitreten; verlangt die Gruppe eine Freischaltung, so kannst du dich für sie bewerben. Ein Gruppenleiter muss daraufhin deinen Antrag annehmen. Er könnte fragen, warum du in die Gruppe aufgenommen werden möchtest. Bitte belästige keinen Gruppenleiter, wenn er dich ablehnt, er wird einen Grund dafür haben.'
	),
	array(
		0 => 'Wie werde ich Gruppenleiter?',
		1 => 'Der Leiter einer Gruppe wird normalerweise durch die Board-Administration festgelegt, wenn die Gruppe erstellt wird. Wenn du eine eigene Benutzergruppe erstellen möchtest, dann solltest du einen Administrator kontaktieren.'
	),
	array(
		0 => 'Weshalb werden verschiedene Benutzergruppen farbig dargestellt?',
		1 => 'Es ist der Board-Administration möglich, den Benutzergruppen verschiedene Farben zuzuteilen, sodass deren Mitglieder leichter zu identifizieren sind.'
	),
	array(
		0 => 'Was ist eine Hauptgruppe?',
		1 => 'Wenn du Mitglied in mehr als einer Benutzergruppe bist, dient die Hauptgruppe dazu, deine Gruppenfarbe sowie den Gruppenrang, der bei dir standardmäßig angezeigt wird, festzulegen. Ein Administrator kann dir die Berechtigung geben, deine Hauptgruppe im persönlichen Bereich selbst festzulegen.'
	),
	array(
		0 => 'Was bedeutet der „Das Team“-Link auf der Startseite?',
		1 => 'Auf dieser Seite findest du eine Auflistung des Forenteams, einschließlich der Administratoren, der Moderatoren. Du findest hier auch weitere Informationen wie die Foren, die diese im Einzelnen moderieren.'
	),
	array(
		0 => '--',
		1 => 'Private Nachrichten'
	),
	array(
		0 => 'Ich kann keine Privaten Nachrichten verschicken!',
		1 => 'Hierfür kann es drei Gründe geben: Entweder bist du nicht registriert und / oder nicht angemeldet, oder die Board-Administration hat Private Nachrichten für das komplette Forum ausgeschaltet. Außerdem könnte es sein, dass der Administrator dir das Recht, Private Nachrichten zu verschicken, entzogen hat. Kontaktiere einen Administrator, um weitere Informationen zu erhalten.'
	),
	array(
		0 => 'Ich bekomme ständig unerwünschte Private Nachrichten!',
		1 => 'Du kannst unterbinden, dass ein Mitglied dir Private Nachrichten sendet, indem du in deinem persönlichen Bereich eine entsprechende Regel erstellst. Falls du belästigende Nachrichten von jemandem erhältst, so kannst du dies auch einem Administrator melden. Dieser kann dem betreffenden Mitglied dann verbieten, Private Nachrichten zu versenden.'
	),
	array(
		0 => 'Ich habe eine Spam-E-Mail von einem Mitglied dieses Forums erhalten!',
		1 => 'Es tut uns leid, das zu hören. Das E-Mail-Formular dieses Forums hat einige Sicherheitsvorkehrungen, die Benutzer, die solche Nachrichten senden, identifizieren sollen. Du solltest einem Administrator die komplette E-Mail, die du bekommen hast, weiterleiten. Dabei ist es ganz wichtig, die Kopfzeilen (Headers) mitzuschicken. Diese enthalten Details über den Benutzer, der die E-Mail verschickt hat. Der Administrator kann dann entsprechend reagieren.'
	),
	array(
		0 => '--',
		1 => 'Freunde und ignorierte Mitglieder'
	),
	array(
		0 => 'Wozu benötige ich die Listen der Freunde und ignorierten Mitglieder?',
		1 => 'Du kannst diese Listen benutzen, um andere Mitglieder des Boards zu verwalten. Mitglieder, die du deiner Freundesliste hinzufügst, werden in deinem persönlichen Bereich für den schnellen Zugriff aufgelistet. Du siehst dort deren Onlinestatus und kannst ihnen schnell eine Private Nachricht senden. Abhängig von dem Style, den du verwendest, können Beiträge deiner Freunde auch hervorgehoben sein. Wenn du einen Benutzer ignorierst, dann siehst du seine Beiträge standardmäßig nicht.'
	),
	array(
		0 => 'Wie kann ich Mitglieder zur Liste der Freunde oder zur Liste der ignorierten Mitglieder hinzufügen oder diese wieder aus den Listen entfernen?',
		1 => 'Du kannst Benutzer auf zwei Arten auf diese Listen setzen: In jedem Benutzerprofil siehst du zwei Links: einen zum Hinzufügen zur Liste der Freunde und einen zum Ignorieren des Benutzers. Außerdem kannst du im persönlichen Bereich direkt Benutzer zu den Listen hinzufügen, indem du deren Benutzernamen eingibst. An gleicher Stelle kannst du sie auch wieder von den Listen entfernen.'
	),
	array(
		0 => '--',
		1 => 'Die Foren durchsuchen'
	),
	array(
		0 => 'Wie kann ich ein Forum oder mehrere Foren durchsuchen?',
		1 => 'Du kannst die Foren durchsuchen, indem du einen Suchbegriff in die Suchbox eingibst, die du in der Foren-Übersicht, der Foren- oder Themenansicht findest. Erweiterte Suchmöglichkeiten erhältst du, indem du den „Erweiterte Suche“-Link anklickst, der von jeder Seite des Forums aus verfügbar ist.'
	),
	array(
		0 => 'Weshalb erhalte ich bei der Suche keine Ergebnisse?',
		1 => 'Deine Suche war möglicherweise zu allgemein gehalten und enthielt zu viele gängige Wörter, welche von phpBB3 nicht indiziert werden. Stelle eine spezifischere Anfrage und benutze die Optionen, die dir die erweiterte Suche bietet. Außerdem ist es natürlich auch möglich, dass dein(e) Suchbegriff(e) hier nirgends im Forum verwendet wurden. Prüfe ggf. die Rechtschreibung der Begriffe!'
	),
	array(
		0 => 'Warum bekomme ich bei der Suche eine leere Seite?',
		1 => 'Deine Suche lieferte zu viele Ergebnisse, somit konnte der Webserver sie nicht verarbeiten. Benutze die erweiterte Suche und gib spezifischere Suchbegriffe ein oder beschränke die Suche auf verschiedene Unterforen.'
	),
	array(
		0 => 'Wie kann ich nach Mitgliedern suchen?',
		1 => 'Gehe zur „Mitglieder“-Seite und klicke auf „Nach einem Mitglied suchen“.'
	),
	array(
		0 => 'Wie kann ich meine eigenen Beiträge und Themen finden?',
		1 => 'Deine eigenen Beiträge kannst du dir anzeigen lassen, indem du auf den Link „Eigene Beiträge“ in der Foren-Übersicht klickst. Alternativ kannst du auch „Beiträge des Mitglieds anzeigen“ in deinem persönlichen Bereich oder auf deiner eigenen Profilseite verwenden. Benutze die erweiterte Suche, um nach von dir erstellen Themen zu suchen. Trage dort die entsprechenden Optionen in die Suchmaske ein.'
	),
	array(
		0 => '--',
		1 => 'Benachrichtigungen und Lesezeichen'
	),
	array(
		0 => 'Was ist der Unterschied zwischen einem Lesezeichen und der Beobachtung eines Themas oder Forums?',
		1 => 'Lesezeichen in phpBB3 sind ähnlich der Lesezeichen oder Favoriten deines Browsers. Du wirst nicht über Änderungen informiert, kannst aber später das Thema schnell erneut aufrufen. Wenn du ein Thema oder Forum beobachtest, wirst du hingegen informiert, wenn es neue Beiträge oder Themen gibt. Die Benachrichtigung erfolgt mit der von dir gewählten Benachrichtigungs-Methode.'
	),
	array(
		0 => 'Wie kann ich ein Forum oder ein Thema beobachten?',
		1 => 'Um ein Forum zu beobachten, findest du im Forum einen Link „Forum beobachten“. Wenn du ein Thema beobachten willst, kannst du entweder die Option „Mich per E-Mail benachrichtigen, sobald eine Antwort geschrieben wurde“ beim Verfassen eines Beitrages zu diesem Thema aktivieren oder den Link „Thema beobachten“ innerhalb des Themas verwenden.'
	),
	array(
		0 => 'Wie deaktiviere ich meine Benachrichtigungen?',
		1 => 'Wenn du mehrere Benachrichtigungen deaktivieren willst, so kannst du dies im persönlichen Bereich unter „Einstieg“ – „Benachrichtigen verwalten“ machen.'
	),
	array(
		0 => '--',
		1 => 'Dateianhänge'
	),
	array(
		0 => 'Welche Dateianhänge sind in diesem Forum zulässig?',
		1 => 'Die Board-Administration kann bestimmte Dateitypen zulassen oder verbieten. Falls du dir nicht sicher bist, welche Dateitypen du hochladen kannst und du Unterstützung benötigst, wende dich bitte an die Board-Administration.'
	),
	array(
		0 => 'Kann ich eine Übersicht all meiner Dateianhänge erhalten?',
		1 => 'Um eine Liste all deiner Dateianhänge zu erhalten, gehe in den persönlichen Bereich. Dort findest du unter „Einstieg“ einen Punkt „Dateianhänge verwalten“, über den du eine Liste deiner Dateianhänge erhalten und diese verwalten kannst.'
	),
	array(
		0 => '--',
		1 => 'phpBB3 betreffende Fragen'
	),
	array(
		0 => 'Wer hat diese Forensoftware entwickelt?',
		1 => 'Diese Software (in ihrer unmodifizierten Fassung) wurde von der <a href="https://www.phpbb.com/">phpBB Group</a> entwickelt und veröffentlicht. Sie ist urheberrechtlich geschützt. Sie wurde unter der GNU General Public License veröffentlicht und kann frei vertrieben werden. Weitere Details findest du auf der Seite der phpBB Group. Eine deutschsprachige Anlaufstelle ist unter <a href="https://www.phpbb.de/">phpBB.de</a> zu finden.'
	),
	array(
		0 => 'Warum ist Funktion x oder y nicht enthalten?',
		1 => 'Diese Software wurde von der phpBB Group geschrieben. Wenn du denkst, dass eine Funktion implementiert werden sollte, dann besuche <a href="https://www.phpbb.com/ideas/">phpBB Ideas</a>, wo du deine Stimme für bestehende Vorschläge abgeben oder neue Funktionen vorschlagen kannst.'
	),
	array(
		0 => 'An wen soll ich mich wenden, falls es Beschwerden oder juristische Anfragen zu diesem Forum gibt?',
		1 => 'Jeder Administrator, der auf der „Das Team“-Seite aufgeführt ist, ist ein geeigneter Kontakt für deine Beschwerde. Wenn du so keine Antwort erhältst, solltest du den Besitzer der Domain kontaktieren (führe dazu eine <a href="http://www.google.com/search?q=whois">„WHOIS“-Abfrage</a> durch) oder — falls diese Seite bei einem kostenlosen Webhoster wie z.&nbsp;B. Yahoo!, free.fr, funpic.de usw. liegt — den Support oder den Abuse-Kontakt des betreffenden Dienstes. Bitte beachte, dass die phpBB Group und phpBB.de <strong>absolut keinen Einfluss</strong> auf die Benutzung oder den oder die Benutzer der Forensoftware haben und dafür in keiner Weise zur Verantwortung herangezogen werden können. Kontaktiere daher nie die phpBB Group oder phpBB.de in Zusammenhang mit jeglichen juristischen Fragen (Unterlassungserklärungen, Haftungsfragen usw.), die <strong>sich nicht direkt</strong> auf die Website phpbb.com oder die phpBB-Software selbst beziehen. Falls du der phpBB Group E-Mails schreibst, die die <strong>Softwarenutzung durch Dritte</strong> betreffen, so wirst du, wenn überhaupt, höchstens eine knappe Antwort erhalten.'
	)
);

?>