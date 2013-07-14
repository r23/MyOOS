<?php
/*
   Plugin Name: WP Permalauts Extended
   Plugin URI: http://www.webarbeit.net/
   Description: Ermöglicht das automatisierte Umschreiben von deutschen Umlauten in URLs für Artikel, Seiten, Kategorien und Schlagwörter in einen lesbaren Permalink. Zusätzliche Funktionen: Stopwords und Windows Live Writer-Unterstützung. Basiert auf: <a a href="http://wordpress.org/extend/plugins/wp-permalauts/">http://wordpress.org/extend/plugins/wp-permalauts/</a> von <a a href="http://blogcraft.de/">Christoph Grabo</a>
   Version: 1.0
   Author: Frank Kugler
   Author URI: http://www.webarbeit.net/
   License: GPL3
*/

$WPL_VERSION = "1.0";

/**
 * Hilfsfunktionen
 */
function u8e($c)
{
    return utf8_encode($c);
}
function u8d($c)
{
    return utf8_decode($c);
}

/**
 * Zu ersetzende Zeichen
 */
$wpl_chartable = array(
    'raw' => array('ä' , 'Ä' , 'ö' , 'Ö' , 'ü' , 'Ü' , 'ß'),
    'in' => array(chr(228), chr(196), chr(246), chr(214), chr(252), chr(220), chr(223)),
    'perma' => array('ae' , 'Ae' , 'oe' , 'Oe' , 'ue' , 'Ue' , 'ss'),
    'post' => array('&auml;', '&Auml;', '&ouml;', '&Ouml;', '&uuml;', '&Uuml;', '&szlig;'),
    'feed' => array('&#228;', '&#196;', '&#246;', '&#214;', '&#252;', '&#220;', '&#223;'),
    'utf8' => array(u8e('ä'), u8e('Ä'), u8e('ö'), u8e('Ö'), u8e('ü'), u8e('Ü'), u8e('ß'),
	'html' => array('&auml;', '&Auml;', '&ouml;', '&Ouml;', '&uuml;', '&Uuml;', '&szlig;'))
	);
	
/**
 * Stopwords
 */
$stopwords = array ("aber", "als", "am", "an", "auch", "auf", "aus", "bei", "bin", "bis", "bist", "da", "dadurch", "daher", "darum", "das", "daß", "dass", "dein", "deine", "dem", "den", "der", "des", "dessen", "deshalb", "die", "dies", "dieser", "dieses", "doch", "dort", "du", "durch", "ein", "eine", "einem", "einen", "einer", "eines", "er", "es", "euer", "eure", "für", "hatte", "hatten", "hattest", "hattet", "hier", "hinter", "ich", "ihr", "ihre", "im", "in", "ist", "ja", "jede", "jedem", "jeden", "jeder", "jedes", "jener", "jenes", "jetzt", "kann", "kannst", "können", "könnt", "machen", "mein", "meine", "mit", "muß", "mußt", "musst", "müssen", "müßt", "nach", "nachdem", "nein", "nicht", "nun", "oder", "seid", "sein", "seine", "sich", "sie", "sind", "soll", "sollen", "sollst", "sollt", "sonst", "soweit", "sowie", "und", "unser", "unsere", "unter", "vom", "von", "vor", "wann", "warum", "was", "weiter", "weitere", "wenn", "wer", "werde", "werden", "werdet", "weshalb", "wie", "wieder", "wieso", "wir", "wird", "wirst", "wo", "woher", "wohin", "zu", "zum", "zur", "über");


/**
 * Permalink generieren
 */
function wpl_permalink($slug)
{
    global $wpl_chartable;
    global $stopwords;

    if (seems_utf8($slug)) {
        $invalid_latin_chars = array(
            chr(197) . chr(146) => 'OE',
            chr(197) . chr(147) => 'oe',
            chr(197) . chr(160) => 'S',
            chr(197) . chr(189) => 'Z',
            chr(197) . chr(161) => 's',
            chr(197) . chr(190) => 'z',
            chr(226) . chr(130) . chr(172) => 'E');
        $slug = u8d(strtr($slug, $invalid_latin_chars));
    }

    $slug = str_replace($wpl_chartable['raw'], $wpl_chartable['perma'], $slug);
    $slug = str_replace($wpl_chartable['utf8'], $wpl_chartable['perma'], $slug);
    $slug = str_replace($wpl_chartable['in'], $wpl_chartable['perma'], $slug);
	$slug = str_replace($wpl_chartable['html'], $wpl_chartable['perma'], $slug);

    $current_wpl_options = get_option('wpl_options');

    if ($current_wpl_options['stopwords'] == 1) { 
		// Stopwords entfernen
        $stopwords_array = array_diff (split(" ", $slug), $stopwords); 
        $slug = join("-", $stopwords_array);
    } 

    return $slug;
}

/**
 * wpl_permalink_with_dashes
 */
function wpl_permalink_with_dashes($slug)
{
    $slug = wpl_permalink($slug);
    $slug = sanitize_title_with_dashes($slug);
    return $slug;
}
/**
 * wpl_restore_raw_title
 */
function wpl_restore_raw_title($title, $raw_title = "", $context = "")
{
    if ($context == 'save')
        return $raw_title;
    else
        return $title;
}
/**
 * Options Page
 */
function wpl_options_page()
{
    global $WPL_VERSION;
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
	}
?>

<div class="wrap">
<div id="icon-options-general" class="icon32"></div>
<h2>WP Permalauts</h2>
<div class="metabox-holder has-right-sidebar">
  <div class="inner-sidebar">
    <div class="postbox">
      <h3><span>Wichtig zu wissen</span></h3>
      <div class="inside">
        <p>Dieses Plugin kann nur die Permalinks von neuen Objekten anpassen. Alte Permalinks werden nie neu erstellt! Dies muss manuell nachgeholt werden.</p>
      </div>
    </div>
    <div class="postbox">
      <h3><span>Blick in die Zukunft</span></h3>
      <div class="inside">
        <p>Automatische Umstellung alter Permalinks </p>
      </div>
    </div>
    <div class="postbox">
      <h3><span>Kontakt</span></h3>
      <div class="inside">
        <p>E-Mail: <a href="mailto:wordpress@webarbeit.net">wordpress@webarbeit.net</a></p>
      </div>
    </div>
  </div>
  <!-- .inner-sidebar -->
  
  <div id="post-body">
    <div id="post-body-content">
      <div class="postbox">
        <h3><span>Anwendung</span></h3>
        <div class="inside">
          <form method="post" action="options.php">
            <?php settings_fields('wpl_setting_options'); ?>
            <?php $options = wpl_options_validate(wpl_options_defaults(get_option('wpl_options'))); // pre validation and defaults ?>
            <table class="form-table">
              <tr valign="top">
                <th scope="row">Wo sollen Permalinks angepasst werden?</th>
                <td><label>
                    <input name="wpl_options[clean_pp]" type="checkbox" value="1" <?php checked('1', $options['clean_pp']); ?> />
                    Artikel und Seiten </label>
                  <br />
                  <label>
                    <input name="wpl_options[clean_ct]" type="radio"  value="2" <?php checked('2', $options['clean_ct']); ?>>
                    Alle Taxonomien (inklusive Kategorien) </label>
                  <br />
                  <label>
                    <input name="wpl_options[clean_ct]" type="radio"  value="1" <?php checked('1', $options['clean_ct']); ?>>
                    Nur Kategorien </label>
                  <br />
                  <label>
                    <input name="wpl_options[clean_ct]" type="radio"  value="0" <?php checked('0', $options['clean_ct']); ?>>
                    Keine Kategorien/Taxonomien</label></td>
              </tr>
              
                <th scope="row">Verwendung von Stopwords?</th>
                <td><input id="wpl_opt_stopwords" name="wpl_options[stopwords]" type="checkbox" value="1" <?php checked('1', $options['stopwords']); ?> />
                  <label for="wpl_opt_stopwords">Setze einen Haken, um die Stopwords zu aktivieren.<br />
                    <small>Wörter werden aus den Permalinks entfernt</small> </label>
                  <br />
                  <textarea id="" name="" cols="80" rows="10" disabled="disabled">aber, als, am, an, auch, auf, aus, bei, bin, bis, bist, da, dadurch, daher, darum, das, daß, dass, dein, deine, dem, den, der, des, dessen, deshalb, die, dies, dieser, dieses, doch, dort, du, durch, ein, eine, einem, einen, einer, eines, er, es, euer, eure, für, hatte, hatten, hattest, hattet, hier, hinter, ich, ihr, ihre, im, in, ist, ja, jede, jedem, jeden, jeder, jedes, jener, jenes, jetzt, kann, kannst, können, könnt, machen, mein, meine, mit, muß, mußt, musst, müssen, müßt, nach, nachdem, nein, nicht, nun, oder, seid, sein, seine, sich, sie, sind, soll, sollen, sollst, sollt, sonst, soweit, sowie, und, unser, unsere, unter, vom, von, vor, wann, warum, was, weiter, weitere, wenn, wer, werde, werden, werdet, weshalb, wie, wieder, wieso, wir, wird, wirst, wo, woher, wohin, zu, zum, zur, über</textarea></td>
              </tr>
            </table>
            <p class="submit">
              <input type="submit" class="button-primary" value="Änderungen übernehmen" />
            </p>
          </form>
        </div>
        <!-- .inside --> 
      </div>
      <!-- #post-body-content --> 
    </div>
    <!-- #post-body --> 
  </div>
  <!-- .metabox-holder --> 
</div>
<!-- .wrap -->
<?php ;
}
/**
 * wpl_options_menu
 */
function wpl_options_menu()
{
    add_options_page('WP Permalauts', 'Permalauts Extended', 8, __FILE__, 'wpl_options_page');
}
add_action('admin_menu', 'wpl_options_menu');

/**
 * wpl_options_defaults
 */
function wpl_options_defaults($input)
{
    $defaults = array('clean_pp' => 1, 'clean_ct' => 2, 'stopwords' => - 1); // pre defaults for unset values
    $output = array('clean_pp' => 0, 'clean_ct' => 0, 'stopwords' => 0); // init with zeros
    $output['clean_pp'] = ($input['clean_pp'] == 0 ? $defaults['clean_pp'] : $input['clean_pp']);
    $output['clean_ct'] = ($input['clean_ct'] == 0 ? $defaults['clean_ct'] : $input['clean_ct']);
    $output['stopwords'] = ($input['stopwords'] == 0 ? $defaults['stopwords'] : $input['stopwords']);

    return $output;
}

/**
 * wpl_options_validate
 */
function wpl_options_validate($input)
{
    $input['clean_pp'] = ($input['clean_pp'] == 1 ? 1 : - 1);
    $input['clean_ct'] = ($input['clean_ct'] == 1 ? 1 : ($input['clean_ct'] == 2 ? 2 : - 1)); // 2-cascade embedded-if (difficult to read?)
    $input['stopwords'] = ($input['stopwords'] == 1 ? 1 : - 1);
    return $input;
}

/**
 * wpl_options_init
 */
function wpl_options_init()
{
    register_setting('wpl_setting_options', 'wpl_options', 'wpl_options_validate');
}
add_action('admin_init', 'wpl_options_init');

/**
 * always validate data! (and get defaults for unset values)
 */
$current_wpl_options = wpl_options_validate(wpl_options_defaults(get_option('wpl_options'))); 

if ($current_wpl_options['clean_pp'] == 1) {
    remove_filter('sanitize_title', 'sanitize_title_with_dashes');
    add_filter('sanitize_title', 'wpl_restore_raw_title', 9, 3);
    add_filter('sanitize_title', 'wpl_permalink_with_dashes', 10);
} ;
if ($current_wpl_options['clean_ct'] == 1) {
    remove_filter('sanitize_category', 'sanitize_title_with_dashes');
    add_filter('sanitize_category', 'wpl_restore_raw_title', 9, 3);
    add_filter('sanitize_category', 'wpl_permalink_with_dashes', 10);
} ;
if ($current_wpl_options['clean_ct'] == 2) {
    remove_filter('sanitize_term', 'sanitize_title_with_dashes');
    add_filter('sanitize_term', 'wpl_restore_raw_title', 9, 3);
    add_filter('sanitize_term', 'wpl_permalink_with_dashes', 10);
} ;
?>
