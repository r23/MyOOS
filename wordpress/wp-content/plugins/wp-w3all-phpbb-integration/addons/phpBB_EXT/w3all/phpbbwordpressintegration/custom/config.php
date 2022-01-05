<?php
	defined( 'IN_PHPBB' ) or die( 'forbidden' );

/** Setup WordPress database connection values **/
// NOTE IMPORTANT:
// If only db connection values are active, the extension will only work to UPDATE email, password and url fields into the linked WordPress
// and can run as standalone.
// To test that the db connection run fine (because values here are correct) just go to update an email of an user via ACP.
//////////////

$wp_w3all_dbhost = 'localhost';
$wp_w3all_dbname = 'wp';
$wp_w3all_dbuser = 'root';
$wp_w3all_dbpasswd = 'pass';
$wp_w3all_table_prefix = 'wp_';
$wp_w3all_dbport = ''; // maybe required

////////////////////////////
// NOTE IMPORTANT:
// If the following options are not activated
// the extension will only work to UPDATE email, password and url fields into the linked WordPress, and can run as standalone.
// Activating the options here below, may require that the WordPress phpBB integration plugin is installed and active into your WordPress
// or only redirects will happen as expected (not users insertion into WordPress, as obvious may it is, because the cURL post (without visible errors) will fail if the integration plugin is not installed in WP)

/**
    Setup User Addition in WordPress option using cURL or Redirect options.
    Activate Or Disable available options removing the prepending // chars
    Read hints
**/


//////////////
// OPTION
// --> Check if the email or username already exists into the linked WordPress, before the user's addition into phpBB
// Remove prepeding // to activate USERS check into WordPress, and prevent user registration in phpBB if the username or email already exists into the linked WordPress

// define("W3ALLCKWPUSEREXIST", true); // Check if the username or email already exists into the linked WordPress, before the user addition in phpBB


//////////////
// OPTION
// --> cURL
// setup WordPress URL
// this will be also the page were the user will be redirected to, if the next options '--> Redirect to WordPress' are active
// NOTE that it is REQUIRED any valid URL that point to the linked WordPress

// Remove prepeding // to activate USERS ADDITION into WordPress as soon when users register into phpBB
// NOTE IMPORTANT: if this option is not enabled or empty, the addition into WordPress at same time using cURL is disabled
// and the extension will run/work only for: password, email and url update.


// $wp_w3all_wordpress_url = 'https://localhost/wp/';


// DO NOT activate following REDIRECTS options if using the template IFRAME integration

//////////////
// OPTION
// --> Redirect to WordPress ONLY after user has been created in phpBB and autologin happen (because no email confirmation required)
// WARNING: DO NOT activate this if using template IFRAME integration! Or you'll have wp iframed into wp as result, when the phpBB redirect happen
// NOTE IMPORTANT: to activate the follow, it is REQUIRED that the previous '$wp_w3all_wordpress_url' option is also active
// Remove prepending // on next define() to redirect the user into wordpress as soon when the user is added into phpBB

// define("W3ALLREDIRECTUAFTERADD", true); // Redirect only after autologin: DO NOT activate this if using template IFRAME integration


//////////////
// OPTION
// --> EVER Redirect users to WordPress after phpBB login (ACP exluded)
// NOTE IMPORTANT: to activate the follow, it is REQUIRED that the previous '$wp_w3all_wordpress_url' option is also active
// Remove prepending // on next define() to activate

// define("W3ALLREDIRECTEVERAFTERLOGIN", true); // Always redirect to the WordPress Url, after the login in phpBB (ACP exluded): DO NOT activate this if using template IFRAME integration
