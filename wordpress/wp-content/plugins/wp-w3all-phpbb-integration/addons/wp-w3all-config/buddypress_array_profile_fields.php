<?php defined( 'ABSPATH' ) or die( 'forbidden' );

if( !isset( $w3_bpl_profile_occupation ) OR !is_array( $w3_bpl_profile_occupation ) ){
$w3_bpl_profile_occupation = array(
    "en" => "occupation",
    "it" => "occupazione",
    "fr" => "occupation",
    "de" => "occupation",
    "nl" => "bezetting",
    "es" => "ocupacion"
);
}

if( !isset( $w3_bpl_profile_location ) OR !is_array( $w3_bpl_profile_location ) ){
$w3_bpl_profile_location = array(
    "en" => "location",
    "it" => "locazione",
    "fr" => "emplacement",
    "de" => "lage",
    "nl" => "plaats",
    "es" => "location"
);
}

if( !isset( $w3_bpl_profile_interests ) OR !is_array( $w3_bpl_profile_interests ) ){
$w3_bpl_profile_interests = array(
    "en" => "interests",
    "it" => "interessi",
    "fr" => "interets",
    "de" => "interest",
    "nl" => "belangen",
    "es" => "intereses"
);
}

if( !isset( $w3_bpl_profile_website ) OR !is_array( $w3_bpl_profile_website ) ){
$w3_bpl_profile_website = array(
    "en" => "website",
    "it" => "sito web",
    "fr" => "site web",
    "de" => "website",
    "nl" => "website",
    "es" => "sitio web"
);
}
