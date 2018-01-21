<?php
/**
 * In dieser Datei werden die Grundeinstellungen für WordPress vorgenommen.
 *
 * Zu diesen Einstellungen gehören: MySQL-Zugangsdaten, Tabellenpräfix,
 * Secret-Keys, Sprache und ABSPATH. Mehr Informationen zur wp-config.php gibt es
 * auf der {@link http://codex.wordpress.org/Editing_wp-config.php wp-config.php editieren}
 * Seite im Codex. Die Informationen für die MySQL-Datenbank bekommst du von deinem Webhoster.
 *
 * Diese Datei wird von der wp-config.php-Erzeugungsroutine verwendet. Sie wird ausgeführt,
 * wenn noch keine wp-config.php (aber eine wp-config-sample.php) vorhanden ist,
 * und die Installationsroutine (/wp-admin/install.php) aufgerufen wird.
 * Man kann aber auch direkt in dieser Datei alle Eingaben vornehmen und sie von
 * wp-config-sample.php in wp-config.php umbenennen und die Installation starten.
 *
 * @package WordPress
 */

/**  MySQL Einstellungen - diese Angaben bekommst du von deinem Webhoster. */
/**  Ersetze database_name_here mit dem Namen der Datenbank, die du verwenden möchtest. */
define('DB_NAME', 'blog');

/** Ersetze username_here mit deinem MySQL-Datenbank-Benutzernamen */
define('DB_USER', 'root');

/** Ersetze password_here mit deinem MySQL-Passwort */
define('DB_PASSWORD', '');

/** Ersetze localhost mit der MySQL-Serveradresse */
define('DB_HOST', 'localhost');

/** Der Datenbankzeichensatz der beim Erstellen der Datenbanktabellen verwendet werden soll */
define('DB_CHARSET', 'utf8');

/** Der collate type sollte nicht geändert werden */
define('DB_COLLATE', '');

/**#@+
 * Sicherheitsschlüssel
 *
 * Ändere jeden KEY in eine beliebige, möglichst einzigartige Phrase.
 * Auf der Seite {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * kannst du dir alle KEYS generieren lassen.
 * Bitte trage für jeden KEY eine eigene Phrase ein. Du kannst die Schlüssel jederzeit wieder ändern,
 * alle angemeldeten Benutzer müssen sich danach erneut anmelden.
 *
 * @seit 2.6.0
 */
define('AUTH_KEY',         '2GaS P[2Uypp1,>Ql#2yP&8+LzdIi1_Jg?BT5<n4),]BDz$O3d[Dq}3]Gwy!jd[7');
define('SECURE_AUTH_KEY',  '8E4nQiJ_`NfNkWTi &YTzI!d:%?3<oQ-[Ea1^-&b46Vfu0e1,d>Gt(LCCR.V#<NJ');
define('LOGGED_IN_KEY',    '2x3;;F<27C9ug+h+C)rjr0DhAkJ1 Kj]$pjyL[f%E;OT%6Ihv*#fw58h^N_/h?LV');
define('NONCE_KEY',        'z^F:sLIImKDI4oZ0f?zR(z90Hp{o(]c{0v8S9X6k 6_LNh@R?V-P+>^8{U(xaGSy');
define('AUTH_SALT',        '[P(^StpFng{^)|-6LeJypyu[)(iP?nzcZpV4NBhs5n$%ctX]<1,:-IH[m7[al<Ic');
define('SECURE_AUTH_SALT', '@*;~pGt[,`<u)yTX-V_I5OyyNZ9N=^ #{)p#xV$LjtjLtWk=V#7CXjf*7&4>RPLT');
define('LOGGED_IN_SALT',   'F~vG45yf~37>)03 ^t7<22+<A9@3 _A#$9H0]bkLYh2MkT*q-c{n;AlCy+F^lnu~');
define('NONCE_SALT',       '#7lH^fXSq3D+;f0P}U4j#RNM#9SmAaAtSy 2xX[Z1^|xmt4+nUMu[=+p-1d9jZfO');


/**#@-*/

/**
 * WordPress Datenbanktabellen-Präfix
 *
 *  Wenn du verschiedene Präfixe benutzt, kannst du innerhalb einer Datenbank
 *  verschiedene WordPress-Installationen betreiben. Nur Zahlen, Buchstaben und Unterstriche bitte!
 */
$table_prefix  = 'seftwp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
