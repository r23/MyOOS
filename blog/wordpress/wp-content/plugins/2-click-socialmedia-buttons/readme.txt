=== 2 Click Social Media Buttons ===
Contributors: ppfeufer
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=DC2AEJD2J66RE
Tags: twitter, facebook, googleplus, button, flattr, social, privacy, xing, pinterest, t3n, linkedin, twoclick
Requires at least: 3.6
Tested up to: 4.0
Stable tag: 1.6.4
License: GPLv3

Facebook-Like/Empfehlen, Twitter, Flattr, Xing, Pinterest, t3n, LinkedIn und Googleplus dem deutschen Datenschutz entsprechend in WordPress.

== Description ==

Fügt die Buttons für Facebook-Like (Empfehlen), Twitter, Flattr, Xing, Pinterest, t3n, LinkedIn und Googleplus dem deutschen Datenschutz entsprechend in euer WordPress ein.
Dies wird leider durch immer verwirrendere Datenschutzbestimmungen notwendig. Das Plugin ist eine WordPress-Adaption der Lösung von heise.de wie in ihrem Artikel [2 Klicks für mehr Datenschutz](http://www.heise.de/ct/artikel/2-Klicks-fuer-mehr-Datenschutz-1333879.html "2 Klicks für mehr Datenschutz auf heise online") beschrieben.

**Features**

* Einfache Installation.
* Einstellungen speicherbar.
* Dein Twittername als @-reply im Tweettext.
* Description für Pinterest wählbar
* Position der Buttons wählbar (vor oder nach dem Artikel).
* Wählbar welcher Button angezeigt werden soll.
* Wählbar ob es dem Besucher möglich sein soll, die Buttons permanent anzeigen zu lassen.
* Anzeige auf den Artikelseiten (default, nicht änderbar).
* Optionale Anzeige auf CMS-Seiten. (Über die "Exclude Pages" Funktion können gewählte Seiten von der Einbindung ausgenommen werden.)
* Optionale Anzeige im Artikelindex.
* Optionale Anzeige in Archiven. (Jahresarchiv, Monatsarchiv, Tagesarchiv)
* Optionale Anzeige in der Kategorieansicht.
* Optionale Anzeige in den Suchergebnissen.
* Optionale Anzeige als Sidebarwidget in Einzelartikeln und -seiten.
* Optionale Anzeige in passwortgeschützten Artikeln.
* Optionale Anzeige in private Artikeln.
* Optionales Abschalten der OpenGraph-Metatags möglich.
* Facebook Like/Recommend Button
* Twitter Button
* Google+ Button
* Flattr Button
* Xing Button
* Pinterest Button
* t3n Button
* LinkedIn Button
* Eigene Hinweistexte möglich.
* Eigener Infolink möglich.
* Ausführliche F.A.Q.-Seite. (hauptsächlich in deutsch)
* Custom Post Type Handling

== Installation ==

Nutze dafür einfach dein Dashboard

**Installation via Wordpress**

1. Gehe ins Menü 'Plugins' -> 'Install' und suche nach '2 Click Social Media Buttons'
1. Klicke hier auf 'install'

**Manuelle Installation**

1. Lade das Verzeichnis `2-click-socialmedia-buttons` in Dein `/wp-content/plugins/`-Verzeichnis Deines WordPress.
1. Aktiviere das Plugin.

== Screenshots ==

1. Buttons unter dem Text.
2. Hinweis bei Mouseover.
3. Einstellungsmenü der Buttons.

== Changelog ==

= 1.6.4 =
* *26. Juli 2014*
* Hoffentlich ein kleines Problem mit Twitter behoben, was bei langen Titelüberschriften dazu führen konnte, dass die Buttons nicht mehr angezeigt wurden, wenn Umlaute im Titel waren. Bei meinen Tests konnte ich nichts weiter feststellen.
* Übergabe von Variablenwerten gesäubert. Danke an [Dominik Neubauer](https://www.it-tuv.com/) für die Meldung.
* Getestet für WordPress 4.0

= 1.6.3 =
* *21. Januar 2014*
* Altlasten aus PhP4 Zeiten entsorgt.

= 1.6.2 =
* *02. November 2013*
* Getestet auf WordPress 3.7, WordPerss 3.7.1 und WordPress 3.8-alpha

= 1.6.1 =
* *12. August 2013*
* **Fix:** Permanente Aktivierung des Facebookbuttons geht wieder. [http://wordpress.org/support/topic/facebook-button-perm_on-broken-fix-included](http://wordpress.org/support/topic/facebook-button-perm_on-broken-fix-included)

= 1.6 =
* *03. August 2013*
* **CSS Fix:** Klassen der Buttons angepasst, so dass es weniger zu Verwirrungen mit anderen Plugins kommen sollte.
* jQuery fix für jQuery 1.10 (Kommt mit WordPress 3.6).
* Testlauf für WordPress 3.6.

= 1.5 =
* *02. Januar 2013*
* **Fix:** Failover für falsche Linguacodes *(Beispiel: en_EN => en_GB)*
* **Fix:** CSS - Hintergrundbilder werden nun bei aktivierten buttons nicht mehr angezeigt.
* **Fix:** CSS - Workaround für die Buttons im Backend eingefügt. (WordPress > 3.5)
* **Fix:** CSS - Infoboxen werden nur noch angezeigt, wenn man wirklich über dem Dummybutton oder dem Schalter ist.
* **Fix:** Meldungen zu "undefined index" im aktivierten Debugmodus im Backend behoben.
* **Fix:** Sämtliches HTML aus den Überschriften herausgefiltert. Es scheint einige zu geben die meinen da muss unbedingt HTML mit rein, was zu Problemem führen kann.
* **Neu:** Falls der Failover nicht greit, kann die Sprache der Buttons auch in den Einstellungen nun geändert werden. (Zu finden unter "Buttons")
* **Neu:** HTTPS für externe JavaScripte und Dienste.
* **Change:** Priorität des Filters `the_content` auf 12 geändert, so dass dieser später abgefeuert wird und sich nicht mit den Standardprios (10) streitet.
* **Change:** Mindestvoraussetzung auf **WordPress 3.4** geändert. Bei der Überarbeitung des JavaScriptes habe ich einige veraltete Methoden entfernt und durch ihre aktuellen Pendants ersetzt.
* ** *Bitte nur updaten, wenn ihr mindestens WordPress 3.4 und jQuery 1.7 nutzt!* **

= 1.4.1 =
* *23. August 2012*
* **Fix:** Beschreibung zum Deaktivieren des Infobuttons angepasst. Da stand noch die Falsche Beschreibung :-)

= 1.4 =
* *21. August 2012*
* **Neu:** Infobutton per Option schaltbar *(Standard: aktiviert // Zu finden unter "Sonstiges")*
* **Neu:** utm_source, utm_medium und utm_term zur Kampagnenverfolgung hinzugefügt für bessere Reichweitenanalysen.
* ** *Bitte nach dem Update die Einstellungen durchgehen und speichern* **

= 1.3 =
* *16. August 2012*
* **Fix:** Optionen bereinigt. *(Dies bereinigt hoffentlich auch einige Probleme mit der Kampagnenverfolgung.)*
* **Neu:** Übernahme von $_GET-Optionen *(http://deineseite.de/permalink/**?foo=bar**)* in den Permalinks wählbar. *(Standard: deaktiviert // Zu finden unter "Sonstiges")*

= 1.2.2 =
* *15. August 2012*
* **Fix:** Sollte kein Permalink verwendet werden, wird nun auch wieder der richtige Kurzlink zum Artikel ermittelt.
* **Neu:** Option unter *Sonstiges* > Kampagnen Verfolgung. Hiermit kann die Verfolgung eines Artikels als Kampagne in den Analytics-Tool wie Piwik und Google Analytics abgeschalten werden. Diese Option war mit dem Update auf 1.2 per default aktiviert, ist nun jedoch schaltbar.

= 1.2.1 =
* *14. August 2012*
* **Fix:** Dauerhafte Aktivierung funktioniert wieder.

= 1.2 =
* *13. August 2012*
* **Neu:** Dummybilder zu einem CSS-Sprite zusammengefasst.
* **Neu:** Tracking für Piwik und Google Analytics hinzugefügt. *(Funktioniert leider nur richtig bei Google+, Twitter und Xing. Die anderen filtern es teilweise oder gar komplett raus)*
* **Fix:** Einstellungsseite - Dropdown für Facebookbutton zeigt wieder die richtige Auswahl an. (Like/Recommend)
* CSS für Sprite angepasst
* Deutsche Spachdatei angepasst
* Plugin mit jQuery 1.8 getestet. *Ist ja am 09. August veröffentlicht worden und ich wette einige werden es wohl nutzen wollen.*

= 1.1 =
* *07. August 2012*
* **Fix:** Für die OpenGraph-Tags wird nun das komplette Bild hergenommen und nicht nur das verkleinerte Artikelbild.
* **Fix:** Pinterest Beschreibung.
* **Fix:** Flattr Excerpt.
* **Fix:** Felder für Infotexte sind nun wirklich Textfelder :-)
* **Fix:** CSS Klassen umbenannt um Wechselwirkungen mit anderen Plugins zu umgehen. Beispiel: `li.facebook` => `li.twoclick-facebook` und `li.twitter` => `li.twoclick-twitter` und so weiter. Bitte bei Verwendung von eigenem CSS dort anpassen.
* **Neu:** Seite mit Debuginformationen zu den Einstellungen hinzugefügt. Diese können mir durchaus bei der Beseitigung von Problemen nützlich sein.
* **Neu:** Exclude Custom Post Types. Es können nun bestimmte Custom Post Type von der Anzeige ausgenommen werden.

= 1.0.1 =
* *01. August 2012*
* **Fix:** CSS-Fix - Bei einigen Themes kann es vorkommen, dass um die aktivierten Buttons ein Rahmen gezogen wurde. Nun nicht mehr.
* **Fix:** Infotexte für t3n und LinkedIn werden nun übernommen. (Danke an [Franz](http://www.noobtech.at/) für die Meldung)

= 1.0 =
* *01. August 2012*
* **Change:** Kompletter "Rewrite" des Plugins.
* **Change:** Code aufgeräumt.
* **Change:** Klassen erstellt.
* **Change:** WordPress Settings-API wird nun genutzt.
* **Change:** Optionsseite überarbeitet.
* **Change:** Plugindateien, die ausführbaren Code enthalten (.php) vor direktem Zugriff geschützt.
* **Change:** Einbindung der OpenGraph-Tags überarbeitet.
* **Neu:** OpenGraph-Tags: Nun via Option abschaltbar. Dies verhindert dass diese eventuell mehrfach eingebunden werden, wenn dies schon durch das Theme oder andere Plugins geschieht.
* **Neu:** OpenGraph-Tags: Kompatibilität mit [wpSEO](http://wpseo.de/) und [All in One SEO Pack](http://wordpress.org/extend/plugins/all-in-one-seo-pack/) bei Titel und Beschreibung. Sollte eines dieser beiden SEO-Plugins installiert sein, so wird der Titel und die Beschreibung hergenommen, die duch das Plugin beim Erstellen oder Editieren des Artikels angegeben werden .
* **Neu:** OpenGraph-Tags: Locale hinzugefügt.
* **Neu:** Validierung der Optionen eingebaut.
* **Neu:** Exclude Pages - Hiermit können CMS-Seiten bestimmt werden, auf denen die Buttons nicht zu sehen sein sollen.
* **Neu:** F.A.Q.-Seite *(deutsch)* - Die häufigsten Fragen und ihre Antworten.
* **Neu:** Changelogseite - Das komplette Changelog des Plugins einsehbar.
* **Neu:** Spendenseite - Wäre nett, wenn da mal einer draufschaut :-)
* **Neu:** Anzeige in passwortgeschützten Artikeln zuschaltbar.
* **Neu:** Anzeige in privaten Artikeln zuschaltbar.
* **Neu:** Eigenes CSS.
* **Neu:** t3n Button.
* **Neu:** LinkedIn Button.
* **Neu:** Bei Erstinstallation werden nun einige Optionen als Default gesetzt. Buttons müssen weiterhin manuell aktiviert werden.
* **Neu:** Introtext - Dies ist ein kleiner Text, welcher über die Buttons gesetzt werden kann.
* **Fix:** CSS-Fix: Pointer ist nur noch überm Dummy-Button und beim Umschalter zu sehen, nicht mehr über die volle Breite.
* **Fix:** CSS-Fix: Für IE8. Bei einigen Themes konnte es im IE8 vorkommen, dass die Dummybilder nicht richtig angezeigt wurden.
* **Fix:** Doppeltes Laden der CSS-Datei unterbunden.
* **Fix:** Trigger für das Aktivieren in der Artikelübersicht überarbeitet. Es werden nun nicht mehr alle Buttons eines Netzwerkes in der Artikelübersicht umgeschalten, sondern wirklich nur der, auf dem auf geklickt wurde.
* **Update:** Deutsche Übersetzung.
* **Bitte die Einstellungen prüfen und speichern**

= 0.35.2 =
* *14. Juni 2012*
* **Fix:** jQuery wurde nicht in Zusammenhang mit WordPress 3.4 und TwentyTen 1.4 geladen. - fixed.

= 0.35.1 =
* *04. Juni 2012*
* **Fix:** Im komprimierten JavaScript ist statt &amp;quot; bei den alt-Attributen der Dummy-Buttons ein ", das führt leider zu ungültigem HTML und falschen alt-Attributen. Nun ist &amp;quot; auch im komrpimierten JS vorhanden. Danke an Michael für den Hinweis.

= 0.35 =
* *18. April 2012*
* **Neu:** Filter - `twoclick-css` - für CSS hinzugefügt, zur besseren Anpassung in eigenen Themes. (gewünscht von [Caspar](http://blog.ppfeufer.de/wordpress-plugin-2-click-social-media-buttons/comment-page-8/#comment-28654))
* **Fix:** für das Plugin 'wp-Typography' eingebaut, da dieses unter gewissen Umständen den Titel Formatiert und es somit zu Fehlern kommen konnte. (Danke an [Malte](http://www.malteskitchen.de/) für die Meldung)

= 0.34 =
* *18. April 2012*
* **!! SICHERHEITSUPDATE !!**
* **Fix:** Sicherheitslücke in der Verarbeitung der Buttons von Xing und Pinterest geschlossen. Danke an das WordPress-Team für die Meldung.

= 0.33 =
* *03. April 2012*
* **Fix:** Margin des Dummybutton entfernt, welcher in einigen Themes auftaucht.
* **Fix:** Padding des Dummybutton entfernt, welcher in einigen Themes auftaucht.
* **Fix:** Border des Dummybutton entfernt, welcher in einigen Themes auftaucht.

= 0.32.2 =
* *03. April 2012*
* **Fix:** Debugausgaben entfernt :-)

= 0.32.1 =
* *03. April 2012*
* **Fix:** Facebook Like/Gefällt mir ist nun wieder einstellbar.

= 0.32 =
* *02. April 2012*
* **Neu:** Pinterest hinzugefügt. (Danke an [Kai](http://kkoepke.de/) fürs betatesten)
* **Update:** Erkennung der Sprache für Dummyimages verbessert. Namenskonvention für Dummyimages eingeführt, so dass diese je nach verwendeter Sprache geladen werden. Fallback: Englisch
* **Update:** Imageupload für Artikelbildfeld hinzugefügt. Dieses Bild wird für Facebok, Google+ und Pinterest verwendet, wenn der Artikel / die Seite weder ein eigenes Artikelbild noch Bilder im Content hat.
* **Hinweis:** Nach dem Update bitte die Einstellungen prüfen und speichern.

= 0.31.3 =
* *28. März 2012*
* **Fix:** Ersetzung der Standardtexte durch die in den Plugineinstellungen gegebenen.
* **Fix:** Infolink ist wieder Info und auch Link. (Danke an [Oliver B.](http://www.fob-marketing.de/))
* Bei dieser Gelegenheit auch gleich mal wieder den Quelltext etwas aufgeräumt :-)

= 0.31.2 =
* *27. März 2012*
* **Fix:** Post ID im Loop wurde nicht korrekt übergeben.
* **Fix:** Permalink im Loop wurde nicht korrekt übergeben.
* Sorry für die kleinen Pannen :-)

= 0.31.1 =
* *27. März 2012*
* bump

= 0.31 =
* *27. März 2012*
* **Fix:** Array mit Optionen wird via jSon an das jQuery übergeben.
* **Fix:** Formatierung des Tweettextes. Sonderzeichen werden nun nicht mehr als Entität ausgegeben. (Also – statt & #8211; und so weiter.)

= 0.30 =
* *22. März 2012*
* **Fix:** Buttongrößen sind nun im Imagetag mit enthalten.
* **Neu:** Deutscher Dummy für Twitter (Danke an [Felix](http://www.felix-griewald.de/)).
* **Update:** Google+ Dummy an das neue Design des Google+ Buttons angepasst (Nochmals danke an [Felix](http://www.felix-griewald.de/)).
* **Test:** Sonderzeichen im Artikeltitel. Funktioniert und gibt keine Probleme bei meinen Tests.

= 0.29 =
* *21. Februar 2012*
* **CSS Fix:** Sollte durch das Theme für das Listenelement ein overflow definiert haben, werden hier für das Plugin nicht wirksam. Somit wird der hover angezeigt.

= 0.28 =
* *15. Februar 2012*
* **CSS-Patch:** Die einzelnen Elemente können nun keinen Rahmen mehr bekommen. Dies war wohl bei der Zusammenarbeit mit einigen andern Plugins der Fall.
* **JS-Patch:** Zwei Kommas aus dem JavaScript entfernt, damit auch "ältere" Internetexplorer verstehen was sie tun sollen ... (Ich kanns selbst kaum glauben, dass ich das hier hin schreibe.)

= 0.27.1 =
* *11. Februar 2012*
* Patch: Verloren gegangene Optionen sind nun wieder da, sorry :-)

= 0.27 =
* *11. Februar 2012*
* **CSS:** Clearfix (wie er in WordPress 3.4 verwendet wird) hinzugefügt. Somit wird das Floating nach den Buttons automatisch wieder aufgehoben und nachfolgende Elemente schieben nicht nicht daneben. Danke an [jzdm](http://blog.ppfeufer.de/wordpress-plugin-2-click-social-media-buttons/comment-page-6/#comment-28028) für den Hinweis.
* Alten auskommentierten Code rausgeworfen.
* Verwendung von $_REQUEST statt $_POST (wird durch WordPress bevorzugt).

= 0.26 =
* *09. Februar 2012*
* **Fix:** Nicht genutzte Konstante entfernt.
* **Fix:** Xing Dummybild ins CSS aufgenommen.

= 0.25 =
* *06. Februar 2012*
* Auf Wunsch eines [Einzelnen](http://picomol.de/) via [Twitter](http://twitter.com/#!/picomol/statuses/166157390272663552) nun auch mit Einstellungen für die Archivtypen :-)

= 0.24.1 =
* *05. Februar 2012*
* bump

= 0.24 =
* *05. Februar 2012*
* **Neu:** Gewünschte neue Optionen zur Anzeige der Buttons auf Suchergebnisseiten, Kategoriearchiven und Tagarchvien eingebaut. Hinweis: Nicht jedes Theme unterstützt diese Optionen.
* **Fix:** Übesetzung aktualisiert.

= 0.23.1 =
* *29. Januar 2012*
* **Fix:** Dummybild für Xing nachgereicht, sorry ....

= 0.23 =
* *29. Januar 2012*
* **Neu:** Xing zu den Buttons hinzugefügt
* **Fix:** JavaScript per return holen, nicht per echo. Ich hoffe das behebt noch ein paar kleinere Sorgen.

= 0.22 =
* *28. Januar 2012*
* **Fix:** CSS für responsitive Layouts angepasst. (Danke an [Kai Köpke](http://kkoepke.de))
* **Fix:** CSS für den Infobutton. Margin mit "!important" versehen, damit es nicht überschrieben wird. (Danke an [Michael](http://michas-blog.diewebservisten.de/) für den Hinweis)
* **Fix:** Padding der Dummy-Buttons.
* **Fix:** Das Problem, dass die Buttons bei einigen mehrfach unter einem Artikel eingebunden wurden, ist nun hoffentlich behoben.
* Dummybilder wurden von [Kai Köpke](http://kkoepke.de) überarbeitet und haben nun keinen weißen Hintergrund mehr und sind somit einheitlicher.
* Die dauerhaften Einstellungen können nun nur noch im Einzelartikel geändert werden und wirken sich nur noch auf die Anzeige im Einzelartikel aus. Im Loop ist die Permaoption deaktiviert, da sie dort zu fehleranfällig ist.
* JavaScript "minified"

= 0.21.1 =
* *09. Dezember 2011*
* **Fix:** Artikeltitel im Tweettext in der Artikelübersicht wird nun korrekt erkannt und nicht mehr der erste Titel der Übersicht genommen.
* **Neu:** Link zu den Einstellungen in der Pluginübersicht eingefügt. So muss man nach der Installation nicht so lange suchen.

= 0.21 =
* *06. Dezember 2011*
* **Fix:** Buchstabendreher im HTML und CSS behoben.
* **Fix:** RSS-Feeds und Trackbacks werden nicht mit den Buttons versorgt. Danke für den Hinweis an [Chris](http://campino2k.de).

= 0.20 =
* *27. November 2011*
* Das Einbinden auf der Übersichtsseite funktioniert nun endlich :-)

= 0.19.1 =
* *26. November 2011*
* **Fix:** Schreibfehler in Variablenwert behoben. Danke an [Torsten](http://blog.blechkopp.net/) für den Hinweis.

= 0.19 =
* *16. November 2011*
* **Neu:** Option für ein Standardartikelbild eingefügt. Diese wird wirksam, wenn im Artikel oder der Seite kein Artikelbild (Post Thumbnail) oder sonstiges Bild gefunden wurde, welches für Facebook und/oder Google+ verwendet werden könnte.
* **Fix:** Teilen bei Google+ ist nun wieder möglich.

= 0.18.1 =
* *15. November 2011*
* **Update:** Übersetzung (Sorry, dass diese extra kommt)

= 0.18 =
* *15. November 2011*
* **Neu:** Template-Tag zum direkten Einbau ins Theme. Der Template-Tag berücksichtigt alle Einstellungen, die unter "Anzeige" getätigt wurden. Dafür nutze einfach `if(function_exists('get_twoclick_buttons')) {get_twoclick_buttons(get_the_ID());}` innerhalb des Themes. Beachte jedoch, dass dies nur bei Einzelartikeln und/oder -seiten funktioniert, nicht innerhalb des Loops.

= 0.17 =
* *14. November 2011*
* **Fix:** Dummybilder für Facebook werden nun richtig angezeigt. Je nach Auswahl entweder "Gefällt mir"/"Like" oder "Empfehlen"/"Recommend". Danke an [Kai Köpke](http://kkoepke.de) für die Bearbeitung der Grafiken.

= 0.16 =
* *10. November 2011*
* **Fix:** Optionen im Link werden nun an die Buttons übergeben.

= 0.15 =
* *09. November 2011*
* Ready for WordPress 3.3

= 0.14 =
* *02. November 2011*
* **Neu:** Optionen für Twitter erweitert.
* **Neu:** Auswahl des Facebook-Buttons (Empfehlen/Like).
* **Update:** Deutsche Übersetzung überarbeitet.

= 0.13 =
* *28. Oktober 2011*
* **Fix:** Funktion zum Einbinden der Buttons überarbeitet. (schlanker, kürzer und schneller)
* **Fix:** Schreibfehler auf der Einstellungsseite berichtigt.
* **Fix:** Workaround für Themes, welche auf dem Weg vom Content zum Footer die Post-ID verlieren eingebaut.
* Code ein wenig aufgeräumt.

= 0.12 =
* *27. Oktober 2011*
* **Neu:** Sprachunterstützung hinzugefügt (Englisch und Deutsch).
* **Neu:** Direkte Eingabe der Infotexte. Also der Texte, die bei Mouseover angezeigt werden.

= 0.11-r2 =
* *27. Oktober 2011*
* **Update:** JavaScript

= 0.11-r1 =
* *27. Oktober 2011*
* Versionbump

= 0.11 =
* *26. Oktober 2011*
* **Fix:**  CSS - äußeren Bildabstand der Listenelemente auf 0 gesetzt. Dies gab sonst einige Probleme in einigen Themes. (margin:0 !important;)
* **Neu:**  Flattr ist nun ebenfalls dabei :-)

= 0.10 =
* *22. 10. 2011*
* **Fix:**  Falls es kein Excerpt gibt, wird nun explizit einer generiert, damit es auch etwas Text bei Google+ und Faebook anzuzeigen gibt.

= 0.9 =
* *21. Oktober 2011*
* **Fix:**  Sonderzeichen in der Überschrift führen nicht mehr dazu, dass die Buttons nicht geladen werden.
* **Fix:**  CSS - inneren Bildabstand der Listenelemente auf 0 gesetzt. Dies gab sonst einige Probleme in einigen Themes. (padding:0 !important;)
* JavaScript aufgeräumt.

= 0.8.2 =
* *10. Oktober 2001*
* **Fix:**  og:type auf article gesetzt.

= 0.8.1 =
* *08. Oktober 2011*
* **Fix:** JavaScript berichtigt.

= 0.8.0 =
* *08. Oktober 2011*
* APP-ID für Facebook nicht mehr notwendig - entfernt

= 0.7.2 =
* *16. September 2011*
* **Fix:**  Liststyle erneut angepasst, wurde noch von einigen Themes überschrieben.
* **Fix:**  Z-Index angepasst damit die Buttons nicht mehr über der Lightbox liegen.
* **Fix:**  Verschiebung der Buttonleiste in einigen Themes behoben.

= 0.7.1 =
* *15. September 2011*
* **Fix:**  Funktion twoclick_facebook_opengraph_tags() - Abfrage ob das Theme Post Thumbnails unterstützt. Einige Themes tun das einfach nicht.

= 0.7 =
* *15. September 2011*
* **Fix:**  CSS - Aufzählungszeichen entfernt. Einige Themes wollen diese da reinfummeln. Sieht doof aus :-)
* **Fix:**  Hintergrund für die Buttons unterdrückt. Einige Themes wollen da was einbinden, sieht auch doof aus :-)
* **Fix:**  CSS - Checkboxen in den Cookie-Einstellungen repariert.
* **Neu:**  Facebook Admin-ID in den Einstellungen (Wird benötigt, um einige Probleme mit dem Like zu umgehen).
* **Neu:**  Artikelbild oder das erste im Artikel eingebundene Bild wird nun für Facebook hergenommen.
* **Neu:**  Opengraph-Tags werden nun eingebunden.

= 0.6.1 =
* *08. September 2011*
* **Fix:**  Plugin URI in den Kopfdaten.
.
= 0.6 =
* *08. September 2011*
* **Neu:** Anzeige auch für die Artikelübersicht.
* **Neu:** Auswahl welche Buttons angezeigt werden sollen.
* **Neu:** Auswahl welcher Button permanent aktiviert werden darf.

**Wichtig:** Bitte werft nach dem Update einen Blick in die Einstellungen, da die Buttons per default ausgeblendet sind.

= 0.5.1 =
* *07. September 2011*
* **Fix:**  readme.txt

= 0.5 =
* *07. September 2011*
* **Fix:**  Rahmen für Bilder im CSS entfernt.
* **Fix:**  Optionsname für Google+ im Javascript richtig gestellt.
* **Fix:**  Methode um den Pfad der Bilder und des CSS zu ermitteln überarbeitet.
* **Neu:**  Option um die Buttons auf den CMS Seiten ein und ausblenden zu können.
* **Neu:** Option um zu wählen ob die Buttons über oder unter dem Artikel erscheinen sollen.
* **Neu:** Möglichkeit der Einbindung über einen Shortcode direkt in den Artikel. Dies ist jedoch noch mit Vorsicht zu genießen. Hinweis dazu auf der Einstellungsseite beachten.

= 0.4 =
* *06. September 2011*
* **Neu:** Einstellungsseite (Zu finden unter "Einstellungen" -> "2-Klick-Buttons").
* **Fix:**  jQuery wird nun geladen (hoffe ich).
* **Fix:**  Benötigte Option für Facebook App-ID ins das JavaScript eingebunden
* **Fix:** jQuery angepasst.

= 0.3 =
* *05. September 2011*
* **Fix:**  CSS angepasst um ungewolltes Padding zu verhindern.

= 0.2 =
* *05. September 2011*
* **Fix:**  Falsch aufgerufenen Hook entfernt.

= 0.1 =
* *04. September 2011*
* Initial Release

== Frequently Asked Questions ==

= Ist dieses Plugin wirklich absolut mit dem deutschen Datenschutz konform? =
Nicht absolut. Das wäre technisch beinahe nicht möglich, denn dafür müsste man dem Besucher **bevor** dieser überhaupt auf die Seite kommt ein Formular präsentieren, in dem das Ding mit dem Datenschutz erklärt wird und wo dieser zustimmen oder ablehnen kann. Dieses Plugin verhindert lediglich, dass die Scripte der Netzwerke in diesem Plugin direkt beim Besuch der Seite geladen werden. Somit ist es ein Schritt in die richtige Richtung. Nicht mehr und nicht weniger. Diese Funktion betrifft **nur** dieses Plugin. Sollten auf der Seite noch andere Social-Plugins geladen sein, kann es durchaus möglich sein, dass die Scripte dennoch geladen werden. Fremde Plugins können also nicht beeinflusst werden.

= Wieso kann ich in den Einstellungen ein Bild hochladen. Wofür wird dieses verwendet? =
Facebook, Google+ und Pinterest nutzen Bilder aus den Artikeln. Das Plugin erkennt, ob ein Artikel ein Artikelbild hat und stellt dieses für diese Dienste zur Verfügung. Sollte kein Artikelbild vorhanden sein, so sucht das Plugin im Artikel selbst und holt das erste Bild aus dem Artikel. Sollte auch hier kein Bild im Artikel sein, dann würde das dort hinterlegte Bild an diese Dienste geleitet werden. Wichtig ist hier zu wissen, dass Pinterest ein Bild voraussetzt. Sollte also absolut kein Bild für den Artikel zu finden sein. dann funktioniert auch Pinterest nicht. Oder einfacher ausgedrückt, dies ist die Möglichkeit ein Platzhalterbild an diese Dienste zu übermitteln. Euer Logo zum Beispiel.

= Wieso sehe ich die Optionen für die permanente Aktivierung auf der Startseite nicht? =
Nicht nur auf der Startseite. Auf jeder Seite auf der mehrere Artikel angezeigt werden, ist diese Funktion deaktiviert, da sie dort nicht richtig funktioniert.

= Wieso wird der Pinterest-Button auf einigen Seiten angezeigt und auf anderen wieder nicht? =
Pinterest ist kein Social Network in dem Sinne wie es Facebook oder Google+ sind. Es ist eher ein Dienst um Bilder zu teilen, also zielt Pinterest eher auf die Designer und Grafiker unter uns (oder so ähnlich). Das bedeutet für das Plugin, keine Bilder im Artikel, kein Pinterest.

= Wieso sind die Textfelder für die Infotexte vorbelegt? =
Dies sind die Standardtexte und können nicht gelöscht werden. Sollen andere Infotexte angezeigt werden, einfach den Standardtext überschreiben. HTML kann hier genutzt werden.

= Meine Buttons werden nicht richtig angezeigt, bzw. der Facebook-Button ist nur abgeschnitten zu sehen. Was kann ich tun? =
Gehe in die Einstellungen und speichere diese einfach erneut ab. Mit der Version 1.3 wurden die einzelnen Dummybuttons durch ein CSS-Sprite ersetzt. Dafür wurden Änderungen im HTML notwendig, was eventuell zu solchen Anzeigefehlern führen kann. Es kann auch helfen den Cache zu entleeren, sowohl den eines eventuell verwendeten Cacheplugins, als auch den Browsercache.

= Bei mir werden überhaupt keine Dummybuttons angezeigt, woran kann das liegen? =
Die Dummybuttons sind ein sogenanntes CSS-Sprite. Das heißt, diese werden über das CSS des Plugins eingebunden. Wenn Du zusätzlich ein Plugin nutzt, welches die CSS-Dateien Deiner Seite zusammenfasst *(combine)* zu einer Einzigen, stimmen natürlich die Pfade zu den Grafiken nicht mehr und somit können diese nicht mehr angezeigt werden. Hier hilft es, die CSS-Datei des Plugins von der Zusammenfassung auszunehmen. Auch das Komprimieren * (minify) * der CSS-Dateien kann Probleme verursachen.

= Und wenn ich noch andere Fragen habe? =
Falls Du noch eine Frage hast, die hier nicht auftaucht dann stell diese unter [http://ppfeufer.de/wordpress-plugin/2-click-social-media-buttons/](http://ppfeufer.de/wordpress-plugin/2-click-social-media-buttons/) in den Kommentaren. Aber bitte schau vorher einmal grob durch die Kommentare, ob es dieses Anliegen schon gab.

== Upgrade Notice ==

Hier ist nichts zu beachten.
