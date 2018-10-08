<?php

namespace wp_gdpr\lib;

Class Gdpr_Translation{

    public function __construct()
    {
        add_action( 'plugins_loaded', array($this, 'myplugin_load_textdomain') );
    }

    public function myplugin_load_textdomain(){
        load_plugin_textdomain( 'wp_gdpr', false,  GDPR_BASE_NAME . '/languages/'  );
    }
}