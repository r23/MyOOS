<?php
/*
 * Smarty plugin
 * ------------------------------------------------------------
 * Type:       modifier
 * Name:       B2Smilies
 * Purpose:    Converts smilies in string to <IMG SRC> references
 * Author:     Gavin Cowie ('Lifted' from b2++ code by 
 *             Donncha O Caoimh (http://blogs.linux.ie/xeer/))
 * Version:    0.1
 * Remarks:    Expects a b2 installation at www.yoursite.com/b2/
 *             as this is where the smiley gifs are got from.
 *             http://cork.linux.ie/filemgmt/viewcat.php?cid=4
 * Optimized for use with OOS by r23 (info AT r23 DOT de)
 * ------------------------------------------------------------
 */
function smarty_modifier_B2Smilies($message) {

// the smiley IMG directory for IMG SRC tags
$smilies_directory = OOS_IMAGES . 'smilies';

	$b2smiliestrans = array(

		'^_^'		=> 'icon_happy.gif',
		':('		=> 'icon_sad.gif',
		':-('		=> 'icon_sad.gif',
		':sad:'		=> 'icon_sad.gif',
		':)'		=> 'icon_smile.gif',
		':-)'		=> 'icon_smile.gif',
		':smile:'	=> 'icon_smile.gif',
		':angry:'	=> 'icon_angry.gif',
		':D'		=> 'icon_biggrin.gif',
		':-D'		=> 'icon_biggrin.gif',
		':grin:'	=> 'icon_biggrin.gif',
		':blink:'	=> 'icon_blink.gif',
		':blush:'	=> 'icon_bush.gif',
		':closedeyes:'	=> 'icon_closedeyes.gif',
		':lol:'		=> 'icon_laugh.gif',
		':x'		=> 'icon_mad.gif',
		':-x'		=> 'icon_mad.gif',
		':mad:'		=> 'icon_mad.gif',
		':P'		=> 'icon_tongue.gif',
		':-P'		=> 'icon_tongue.gif',
		':razz:'	=> 'icon_tongue.gif',
		';)'		=> 'icon_wink.gif',
		';-)'		=> 'icon_wink.gif',
		':wink:'	=> 'icon_wink.gif',
		'8)'		=> 'icon_cool.gif',
		'8-)'		=> 'icon_cool.gif',
		':cool:'	=> 'icon_cool.gif',
		':roll:'	=> 'icon_rolleyes.gif',
		':rolleyes:'	=> 'icon_rolleyes.gif',
		':o'		=> 'icon_ohmy.gif',
		':-o'		=> 'icon_ohmy.gif',
		':unsure:'	=> 'icon_unsure.gif',
		'-_-'		=> 'icon_sleep.gif',
		'<_<'		=> 'icon_dry.gif'


	);

	# sorts the smilies' array
	if (!function_exists('smiliescmp')) {
		function smiliescmp ($a, $b) {
			if (strlen($a) == strlen($b)) {
			return strcmp($a, $b);
		}
		return (strlen($a) > strlen($b)) ? -1 : 1;
		}
	}
	uksort($b2smiliestrans, 'smiliescmp');

	# generates smilies' search & replace arrays
	foreach($b2smiliestrans as $smiley => $img) {
		$b2_smiliessearch[] = $smiley;
		$smiley_masked = '';
		for ($i = 0; $i < strlen($smiley); $i = $i + 1) {
			$smiley_masked .= substr($smiley, $i, 1).chr(160);
		}
		$b2_smiliesreplace[] = "<img src='$smilies_directory/$img' border=0 />";
	}

	return str_replace($b2_smiliessearch, $b2_smiliesreplace, $message);
}

?>