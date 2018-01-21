<?php
// include php class for QR code generation
include('./phpqrcode.php'); 

// get bitcoin address
$bitcoinaddress = htmlspecialchars( $_GET["bitcoinaddress"] );

// output page
echo '<html><head><title>Bitcoin</title></head><body>';
echo '<div style="text-align:center;"><h1>Bitcoin</h1></div>';
echo '<p style="text-align:center;"><a href="bitcoin:' . $bitcoinaddress . '">bitcoin:' . $bitcoinaddress . '</a></p>';
echo '<p style="text-align:center;">';
QRcode::svg( $bitcoinaddress, false, 'h', 5 );
echo '</p>';
echo '<p style="text-align:center;">Information: <a href="https://www.bitcoin.org" target="_blank">bitcoin.org</a></p>';
echo '</body></html>';
?>
