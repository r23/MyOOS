<?php

namespace wp_gdpr\lib;

/**
 * Class GDPR_Table_Builder
 * @package wp_gdpr\lib
 *
 * allows to build simple table
 */
class Gdpr_Language {

    public function __construct() {
    }


    public function get_language(){
        if(defined('ICL_LANGUAGE_CODE')){
           $lang = ICL_LANGUAGE_CODE;
        }elseif(isset($_GET['lang'])){
            $lang = $_GET['lang'];
        }else{
            $lang = get_language_attributes( 'html' );
            $lang = explode('"', $lang);
            $lang = explode('-', $lang[1]);
            $lang = $lang[0];
        }
        return $lang;
    }
}
