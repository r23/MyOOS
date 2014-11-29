<?php

/**
  ReduxFramework Sample Config File
  For full documentation, please visit: https://docs.reduxframework.com
 * */

if (!class_exists('Redux_Framework_config')) {

    class Redux_Framework_config {

        public $args        = array();
        public $sections    = array();
        public $theme;
        public $ReduxFramework;

        public function __construct() {

            if (!class_exists('ReduxFramework')) {
                return;
            }

            // This is needed. Bah WordPress bugs.  ;)
            if ( true == Redux_Helpers::isTheme( __FILE__ ) ) {
                $this->initSettings();
            } else {
                add_action('plugins_loaded', array($this, 'initSettings'), 10);
            }

        }

        public function initSettings() {

            // Just for demo purposes. Not needed per say.
            $this->theme = wp_get_theme();

            // Set the default arguments
            $this->setArguments();

            // Set a few help tabs so you can see how it's done
            $this->setHelpTabs();

            // Create the sections and fields
            $this->setSections();

            if (!isset($this->args['opt_name'])) { // No errors please
                return;
            }

            // If Redux is running as a plugin, this will remove the demo notice and links
            //add_action( 'redux/loaded', array( $this, 'remove_demo' ) );
            
            // Function to test the compiler hook and demo CSS output.
            // Above 10 is a priority, but 2 in necessary to include the dynamically generated CSS to be sent to the function.
            //add_filter('redux/options/'.$this->args['opt_name'].'/compiler', array( $this, 'compiler_action' ), 10, 2);
            
            // Change the arguments after they've been declared, but before the panel is created
            //add_filter('redux/options/'.$this->args['opt_name'].'/args', array( $this, 'change_arguments' ) );
            
            // Change the default value of a field after it's been set, but before it's been useds
            //add_filter('redux/options/'.$this->args['opt_name'].'/defaults', array( $this,'change_defaults' ) );
            
            // Dynamically add a section. Can be also used to modify sections/fields
            //add_filter('redux/options/' . $this->args['opt_name'] . '/sections', array($this, 'dynamic_section'));

            $this->ReduxFramework = new ReduxFramework($this->sections, $this->args);
        }

        /**

          This is a test function that will let you see when the compiler hook occurs.
          It only runs if a field	set with compiler=>true is changed.

         * */
        function compiler_action($options, $css) {
            //echo '<h1>The compiler hook has run!';
            //print_r($options); //Option values
            //print_r($css); // Compiler selector CSS values  compiler => array( CSS SELECTORS )

            /*
              // Demo of how to use the dynamic CSS and write your own static CSS file
              $filename = dirname(__FILE__) . '/style' . '.css';
              global $wp_filesystem;
              if( empty( $wp_filesystem ) ) {
                require_once( ABSPATH .'/wp-admin/includes/file.php' );
              WP_Filesystem();
              }

              if( $wp_filesystem ) {
                $wp_filesystem->put_contents(
                    $filename,
                    $css,
                    FS_CHMOD_FILE // predefined mode settings for WP files
                );
              }
             */
        }

        /**

          Custom function for filtering the sections array. Good for child themes to override or add to the sections.
          Simply include this function in the child themes functions.php file.

          NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
          so you must use get_template_directory_uri() if you want to use any of the built in icons

         * */
        function dynamic_section($sections) {
            //$sections = array();
            $sections[] = array(
                'title' => __('Section via hook', MYOOS_THEME_NAME),
                'desc' => __('<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', MYOOS_THEME_NAME),
                'icon' => 'el-icon-paper-clip',
                // Leave this as a blank section, no options just some intro text set above.
                'fields' => array()
            );

            return $sections;
        }

        /**

          Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.

         * */
        function change_arguments($args) {
            //$args['dev_mode'] = true;

            return $args;
        }

        /**

          Filter hook for filtering the default value of any given field. Very useful in development mode.

         * */
        function change_defaults($defaults) {
            $defaults['str_replace'] = 'Testing filter hook!';

            return $defaults;
        }

        // Remove the demo link and the notice of integrated demo from the redux-framework plugin
        function remove_demo() {

            // Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
            if (class_exists('ReduxFrameworkPlugin')) {
                remove_filter('plugin_row_meta', array(ReduxFrameworkPlugin::instance(), 'plugin_metalinks'), null, 2);

                // Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
                remove_action('admin_notices', array(ReduxFrameworkPlugin::instance(), 'admin_notices'));
            }
        }

        public function setSections() {

            /**
              Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
             * */
            // Background Patterns Reader
            $sample_patterns_path   = ReduxFramework::$_dir . '../sample/patterns/';
            $sample_patterns_url    = ReduxFramework::$_url . '../sample/patterns/';
            $sample_patterns        = array();

            if (is_dir($sample_patterns_path)) :

                if ($sample_patterns_dir = opendir($sample_patterns_path)) :
                    $sample_patterns = array();

                    while (( $sample_patterns_file = readdir($sample_patterns_dir) ) !== false) {

                        if (stristr($sample_patterns_file, '.png') !== false || stristr($sample_patterns_file, '.jpg') !== false) {
                            $name = explode('.', $sample_patterns_file);
                            $name = str_replace('.' . end($name), '', $sample_patterns_file);
                            $sample_patterns[]  = array('alt' => $name, 'img' => $sample_patterns_url . $sample_patterns_file);
                        }
                    }
                endif;
            endif;

            ob_start();

            $ct             = wp_get_theme();
            $this->theme    = $ct;
            $item_name      = $this->theme->get('Name');
            $tags           = $this->theme->Tags;
            $screenshot     = $this->theme->get_screenshot();
            $class          = $screenshot ? 'has-screenshot' : '';

            $customize_title = sprintf(__('Customize &#8220;%s&#8221;', MYOOS_THEME_NAME), $this->theme->display('Name'));
            
            ?>
            <div id="current-theme" class="<?php echo esc_attr($class); ?>">
            <?php if ($screenshot) : ?>
                <?php if (current_user_can('edit_theme_options')) : ?>
                        <a href="<?php echo wp_customize_url(); ?>" class="load-customize hide-if-no-customize" title="<?php echo esc_attr($customize_title); ?>">
                            <img src="<?php echo esc_url($screenshot); ?>" alt="<?php esc_attr_e('Current theme preview'); ?>" />
                        </a>
                <?php endif; ?>
                    <img class="hide-if-customize" src="<?php echo esc_url($screenshot); ?>" alt="<?php esc_attr_e('Current theme preview'); ?>" />
                <?php endif; ?>

                <h4><?php echo $this->theme->display('Name'); ?></h4>

                <div>
                    <ul class="theme-info">
                        <li><?php printf(__('By %s', MYOOS_THEME_NAME), $this->theme->display('Author')); ?></li>
                        <li><?php printf(__('Version %s', MYOOS_THEME_NAME), $this->theme->display('Version')); ?></li>
                        <li><?php echo '<strong>' . __('Tags', MYOOS_THEME_NAME) . ':</strong> '; ?><?php printf($this->theme->display('Tags')); ?></li>
                    </ul>
                    <p class="theme-description"><?php echo $this->theme->display('Description'); ?></p>
            <?php
            if ($this->theme->parent()) {
                printf(' <p class="howto">' . __('This <a href="%1$s">child theme</a> requires its parent theme, %2$s.') . '</p>', __('http://codex.wordpress.org/Child_Themes', MYOOS_THEME_NAME), $this->theme->parent()->display('Name'));
            }
            ?>

                </div>
            </div>

            <?php
            $item_info = ob_get_contents();

            ob_end_clean();

            $sampleHTML = '';
            if (file_exists(dirname(__FILE__) . '/info-html.html')) {
                /** @global WP_Filesystem_Direct $wp_filesystem  */
                global $wp_filesystem;
                if (empty($wp_filesystem)) {
                    require_once(ABSPATH . '/wp-admin/includes/file.php');
                    WP_Filesystem();
                }
                $sampleHTML = $wp_filesystem->get_contents(dirname(__FILE__) . '/info-html.html');
            }

            // ACTUAL DECLARATION OF SECTIONS
            $this->sections[] = array(
                'title'     => __('Home Settings', MYOOS_THEME_NAME),
                'desc'      => __('Redux Framework was created with the developer in mind. It allows for any theme developer to have an advanced theme panel with most of the features a developer would need. For more information check out the Github repo at: <a href="https://github.com/ReduxFramework/Redux-Framework">https://github.com/ReduxFramework/Redux-Framework</a>', MYOOS_THEME_NAME),
                'icon'      => 'el-icon-home',
                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(

                    array(
                        'id'        => 'opt-web-fonts',
                        'type'      => 'media',
                        'title'     => __('Web Fonts', MYOOS_THEME_NAME),
                        'compiler'  => 'true',
                        'mode'      => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                        'desc'      => __('Basic media uploader with disabled URL input field.', MYOOS_THEME_NAME),
                        'subtitle'  => __('Upload any media using the WordPress native uploader', MYOOS_THEME_NAME),
                        'hint'      => array(
                            //'title'     => '',
                            'content'   => 'This is a <b>hint</b> tool-tip for the webFonts field.<br/><br/>Add any HTML based text you like here.',
                        )
                    ),
                    array(
                        'id'        => 'section-media-start',
                        'type'      => 'section',
                        'title'     => __('Media Options', MYOOS_THEME_NAME),
                        'subtitle'  => __('With the "section" field you can create indent option sections.', MYOOS_THEME_NAME),
                        'indent'    => true // Indent all options below until the next 'section' option is set.
                    ),
                    array(
                        'id'        => 'opt-media',
                        'type'      => 'media',
                        'url'       => true,
                        'title'     => __('Media w/ URL', MYOOS_THEME_NAME),
                        'compiler'  => 'true',
                        //'mode'      => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                        'desc'      => __('Basic media uploader with disabled URL input field.', MYOOS_THEME_NAME),
                        'subtitle'  => __('Upload any media using the WordPress native uploader', MYOOS_THEME_NAME),
                        'default'   => array('url' => 'http://s.wordpress.org/style/images/codeispoetry.png'),
                        //'hint'      => array(
                        //    'title'     => 'Hint Title',
                        //    'content'   => 'This is a <b>hint</b> for the media field with a Title.',
                        //)
                    ),
                    array(
                        'id'        => 'section-media-end',
                        'type'      => 'section',
                        'indent'    => false // Indent all options below until the next 'section' option is set.
                    ),
                    array(
                        'id'        => 'media-no-url',
                        'type'      => 'media',
                        'title'     => __('Media w/o URL', MYOOS_THEME_NAME),
                        'desc'      => __('This represents the minimalistic view. It does not have the preview box or the display URL in an input box. ', MYOOS_THEME_NAME),
                        'subtitle'  => __('Upload any media using the WordPress native uploader', MYOOS_THEME_NAME),
                    ),
                    array(
                        'id'        => 'media-no-preview',
                        'type'      => 'media',
                        'preview'   => false,
                        'title'     => __('Media No Preview', MYOOS_THEME_NAME),
                        'desc'      => __('This represents the minimalistic view. It does not have the preview box or the display URL in an input box. ', MYOOS_THEME_NAME),
                        'subtitle'  => __('Upload any media using the WordPress native uploader', MYOOS_THEME_NAME),
                    ),
                    array(
                        'id'        => 'opt-gallery',
                        'type'      => 'gallery',
                        'title'     => __('Add/Edit Gallery', 'so-panels'),
                        'subtitle'  => __('Create a new Gallery by selecting existing or uploading new images using the WordPress native uploader', 'so-panels'),
                        'desc'      => __('This is the description field, again good for additional info.', MYOOS_THEME_NAME),
                    ),
                    array(
                        'id'            => 'opt-slider-label',
                        'type'          => 'slider',
                        'title'         => __('Slider Example 1', MYOOS_THEME_NAME),
                        'subtitle'      => __('This slider displays the value as a label.', MYOOS_THEME_NAME),
                        'desc'          => __('Slider description. Min: 1, max: 500, step: 1, default value: 250', MYOOS_THEME_NAME),
                        'default'       => 250,
                        'min'           => 1,
                        'step'          => 1,
                        'max'           => 500,
                        'display_value' => 'label'
                    ),
                    array(
                        'id'            => 'opt-slider-text',
                        'type'          => 'slider',
                        'title'         => __('Slider Example 2 with Steps (5)', MYOOS_THEME_NAME),
                        'subtitle'      => __('This example displays the value in a text box', MYOOS_THEME_NAME),
                        'desc'          => __('Slider description. Min: 0, max: 300, step: 5, default value: 75', MYOOS_THEME_NAME),
                        'default'       => 75,
                        'min'           => 0,
                        'step'          => 5,
                        'max'           => 300,
                        'display_value' => 'text'
                    ),
                    array(
                        'id'            => 'opt-slider-select',
                        'type'          => 'slider',
                        'title'         => __('Slider Example 3 with two sliders', MYOOS_THEME_NAME),
                        'subtitle'      => __('This example displays the values in select boxes', MYOOS_THEME_NAME),
                        'desc'          => __('Slider description. Min: 0, max: 500, step: 5, slider 1 default value: 100, slider 2 default value: 300', MYOOS_THEME_NAME),
                        'default'       => array(
                            1 => 100,
                            2 => 300,
                        ),
                        'min'           => 0,
                        'step'          => 5,
                        'max'           => '500',
                        'display_value' => 'select',
                        'handles'       => 2,
                    ),
                    array(
                        'id'            => 'opt-slider-float',
                        'type'          => 'slider',
                        'title'         => __('Slider Example 4 with float values', MYOOS_THEME_NAME),
                        'subtitle'      => __('This example displays float values', MYOOS_THEME_NAME),
                        'desc'          => __('Slider description. Min: 0, max: 1, step: .1, default value: .5', MYOOS_THEME_NAME),
                        'default'       => .5,
                        'min'           => 0,
                        'step'          => .1,
                        'max'           => 1,
                        'resolution'    => 0.1,
                        'display_value' => 'text'
                    ),
                    array(
                        'id'        => 'opt-spinner',
                        'type'      => 'spinner',
                        'title'     => __('JQuery UI Spinner Example 1', MYOOS_THEME_NAME),
                        'desc'      => __('JQuery UI spinner description. Min:20, max: 100, step:20, default value: 40', MYOOS_THEME_NAME),
                        'default'   => '40',
                        'min'       => '20',
                        'step'      => '20',
                        'max'       => '100',
                    ),
                    array(
                        'id'        => 'switch-on',
                        'type'      => 'switch',
                        'title'     => __('Switch On', MYOOS_THEME_NAME),
                        'subtitle'  => __('Look, it\'s on!', MYOOS_THEME_NAME),
                        'default'   => true,
                    ),
                    array(
                        'id'        => 'switch-off',
                        'type'      => 'switch',
                        'title'     => __('Switch Off', MYOOS_THEME_NAME),
                        'subtitle'  => __('Look, it\'s on!', MYOOS_THEME_NAME),
                        'default'   => false,
                    ),
                    array(
                        'id'        => 'switch-custom',
                        'type'      => 'switch',
                        'title'     => __('Switch - Custom Titles', MYOOS_THEME_NAME),
                        'subtitle'  => __('Look, it\'s on! Also hidden child elements!', MYOOS_THEME_NAME),
                        'default'   => 0,
                        'on'        => 'Enabled',
                        'off'       => 'Disabled',
                    ),
                    array(
                        'id'        => 'switch-fold',
                        'type'      => 'switch',
                        'required'  => array('switch-custom', '=', '1'),
                        'title'     => __('Switch - With Hidden Items (NESTED!)', MYOOS_THEME_NAME),
                        'subtitle'  => __('Also called a "fold" parent.', MYOOS_THEME_NAME),
                        'desc'      => __('Items set with a fold to this ID will hide unless this is set to the appropriate value.', MYOOS_THEME_NAME),
                        'default'   => false,
                    ),
                    array(
                        'id'        => 'opt-patterns',
                        'type'      => 'image_select',
                        'tiles'     => true,
                        'required'  => array('switch-fold', 'equals', '0'),
                        'title'     => __('Images Option (with pattern=>true)', MYOOS_THEME_NAME),
                        'subtitle'  => __('Select a background pattern.', MYOOS_THEME_NAME),
                        'default'   => 0,
                        'options'   => $sample_patterns
                    ,
                    ),
                    array(
                        'id'        => 'opt-homepage-layout',
                        'type'      => 'sorter',
                        'title'     => 'Layout Manager Advanced',
                        'subtitle'  => 'You can add multiple drop areas or columns.',
                        'compiler'  => 'true',
                        'options'   => array(
                            'enabled'   => array(
                                'highlights'    => 'Highlights',
                                'slider'        => 'Slider',
                                'staticpage'    => 'Static Page',
                                'services'      => 'Services'
                            ),
                            'disabled'  => array(
                            ),
                            'backup'    => array(
                            ),
                        ),
                        'limits' => array(
                            'disabled'  => 1,
                            'backup'    => 2,
                        ),
                    ),
                    
                    array(
                        'id'        => 'opt-homepage-layout-2',
                        'type'      => 'sorter',
                        'title'     => 'Homepage Layout Manager',
                        'desc'      => 'Organize how you want the layout to appear on the homepage',
                        'compiler'  => 'true',
                        'options'   => array(
                            'disabled'  => array(
                                'highlights'    => 'Highlights',
                                'slider'        => 'Slider',
                            ),
                            'enabled'   => array(
                                'staticpage'    => 'Static Page',
                                'services'      => 'Services'
                            ),
                        ),
                    ),
                    array(
                        'id'        => 'opt-slides',
                        'type'      => 'slides',
                        'title'     => __('Slides Options', MYOOS_THEME_NAME),
                        'subtitle'  => __('Unlimited slides with drag and drop sortings.', MYOOS_THEME_NAME),
                        'desc'      => __('This field will store all slides values into a multidimensional array to use into a foreach loop.', MYOOS_THEME_NAME),
                        'placeholder'   => array(
                            'title'         => __('This is a title', MYOOS_THEME_NAME),
                            'description'   => __('Description Here', MYOOS_THEME_NAME),
                            'url'           => __('Give us a link!', MYOOS_THEME_NAME),
                        ),
                    ),
                    array(
                        'id'        => 'opt-presets',
                        'type'      => 'image_select',
                        'presets'   => true,
                        'title'     => __('Preset', MYOOS_THEME_NAME),
                        'subtitle'  => __('This allows you to set a json string or array to override multiple preferences in your theme.', MYOOS_THEME_NAME),
                        'default'   => 0,
                        'desc'      => __('This allows you to set a json string or array to override multiple preferences in your theme.', MYOOS_THEME_NAME),
                        'options'   => array(
                            '1'         => array('alt' => 'Preset 1', 'img' => ReduxFramework::$_url . '../sample/presets/preset1.png', 'presets' => array('switch-on' => 1, 'switch-off' => 1, 'switch-custom' => 1)),
                            '2'         => array('alt' => 'Preset 2', 'img' => ReduxFramework::$_url . '../sample/presets/preset2.png', 'presets' => '{"slider1":"1", "slider2":"0", "switch-on":"0"}'),
                        ),
                    ),
                    array(
                        'id'            => 'opt-typography',
                        'type'          => 'typography',
                        'title'         => __('Typography', MYOOS_THEME_NAME),
                        //'compiler'      => true,  // Use if you want to hook in your own CSS compiler
                        'google'        => true,    // Disable google fonts. Won't work if you haven't defined your google api key
                        'font-backup'   => true,    // Select a backup non-google font in addition to a google font
                        //'font-style'    => false, // Includes font-style and weight. Can use font-style or font-weight to declare
                        //'subsets'       => false, // Only appears if google is true and subsets not set to false
                        //'font-size'     => false,
                        //'line-height'   => false,
                        //'word-spacing'  => true,  // Defaults to false
                        //'letter-spacing'=> true,  // Defaults to false
                        //'color'         => false,
                        //'preview'       => false, // Disable the previewer
                        'all_styles'    => true,    // Enable all Google Font style/weight variations to be added to the page
                        'output'        => array('h2.site-description'), // An array of CSS selectors to apply this font style to dynamically
                        'compiler'      => array('h2.site-description-compiler'), // An array of CSS selectors to apply this font style to dynamically
                        'units'         => 'px', // Defaults to px
                        'subtitle'      => __('Typography option with each property can be called individually.', MYOOS_THEME_NAME),
                        'default'       => array(
                            'color'         => '#333',
                            'font-style'    => '700',
                            'font-family'   => 'Abel',
                            'google'        => true,
                            'font-size'     => '33px',
                            'line-height'   => '40px'),
                        'preview' => array('text' => 'ooga booga'),
                    ),
                ),
            );

            $this->sections[] = array(
                'type' => 'divide',
            );

            $this->sections[] = array(
                'icon'      => 'el-icon-cogs',
                'title'     => __('General Settings', MYOOS_THEME_NAME),
                'fields'    => array(
                    array(
                        'id'        => 'opt-layout',
                        'type'      => 'image_select',
                        'compiler'  => true,
                        'title'     => __('Main Layout', MYOOS_THEME_NAME),
                        'subtitle'  => __('Select main content and sidebar alignment. Choose between 1, 2 or 3 column layout.', MYOOS_THEME_NAME),
                        'options'   => array(
                            '1' => array('alt' => '1 Column',       'img' => ReduxFramework::$_url . 'assets/img/1col.png'),
                            '2' => array('alt' => '2 Column Left',  'img' => ReduxFramework::$_url . 'assets/img/2cl.png'),
                            '3' => array('alt' => '2 Column Right', 'img' => ReduxFramework::$_url . 'assets/img/2cr.png'),
                            '4' => array('alt' => '3 Column Middle','img' => ReduxFramework::$_url . 'assets/img/3cm.png'),
                            '5' => array('alt' => '3 Column Left',  'img' => ReduxFramework::$_url . 'assets/img/3cl.png'),
                            '6' => array('alt' => '3 Column Right', 'img' => ReduxFramework::$_url . 'assets/img/3cr.png')
                        ),
                        'default'   => '2'
                    ),
                    array(
                        'id'        => 'opt-textarea',
                        'type'      => 'textarea',
                        'required'  => array('layout', 'equals', '1'),
                        'title'     => __('Tracking Code', MYOOS_THEME_NAME),
                        'subtitle'  => __('Paste your Google Analytics (or other) tracking code here. This will be added into the footer template of your theme.', MYOOS_THEME_NAME),
                        'validate'  => 'js',
                        'desc'      => 'Validate that it\'s javascript!',
                    ),
                    array(
                        'id'        => 'opt-ace-editor-css',
                        'type'      => 'ace_editor',
                        'title'     => __('CSS Code', MYOOS_THEME_NAME),
                        'subtitle'  => __('Paste your CSS code here.', MYOOS_THEME_NAME),
                        'mode'      => 'css',
                        'theme'     => 'monokai',
                        'desc'      => 'Possible modes can be found at <a href="http://ace.c9.io" target="_blank">http://ace.c9.io/</a>.',
                        'default'   => "#header{\nmargin: 0 auto;\n}"
                    ),
                    array(
                        'id'        => 'opt-ace-editor-js',
                        'type'      => 'ace_editor',
                        'title'     => __('JS Code', MYOOS_THEME_NAME),
                        'subtitle'  => __('Paste your JS code here.', MYOOS_THEME_NAME),
                        'mode'      => 'javascript',
                        'theme'     => 'chrome',
                        'desc'      => 'Possible modes can be found at <a href="http://ace.c9.io" target="_blank">http://ace.c9.io/</a>.',
                        'default'   => "jQuery(document).ready(function(){\n\n});"
                    ),
                    array(
                        'id'        => 'opt-ace-editor-php',
                        'type'      => 'ace_editor',
                        'title'     => __('PHP Code', MYOOS_THEME_NAME),
                        'subtitle'  => __('Paste your PHP code here.', MYOOS_THEME_NAME),
                        'mode'      => 'php',
                        'theme'     => 'chrome',
                        'desc'      => 'Possible modes can be found at <a href="http://ace.c9.io" target="_blank">http://ace.c9.io/</a>.',
                        'default'   => '<?php\nisset ( $redux ) ? true : false;\n?>'
                    ),
                    array(
                        'id'        => 'opt-editor',
                        'type'      => 'editor',
                        'title'     => __('Footer Text', MYOOS_THEME_NAME),
                        'subtitle'  => __('You can use the following shortcodes in your footer text: [wp-url] [site-url] [theme-url] [login-url] [logout-url] [site-title] [site-tagline] [current-year]', MYOOS_THEME_NAME),
                        'default'   => 'Powered by Redux Framework.',
                    ),
                    array(
                        'id'        => 'password',
                        'type'      => 'password',
                        'username'  => true,
                        'title'     => 'SMTP Account',
                        //'placeholder' => array('username' => 'Enter your Username')
                    )
                )
            );

            $this->sections[] = array(
                'icon'      => 'el-icon-website',
                'title'     => __('Styling Options', MYOOS_THEME_NAME),
                'fields'    => array(
                    array(
                        'id'        => 'opt-select-stylesheet',
                        'type'      => 'select',
                        'title'     => __('Theme Stylesheet', MYOOS_THEME_NAME),
                        'subtitle'  => __('Select your themes alternative color scheme.', MYOOS_THEME_NAME),
                        'options'   => array('default.css' => 'default.css', 'color1.css' => 'color1.css'),
                        'default'   => 'default.css',
                    ),
                    array(
                        'id'        => 'opt-color-background',
                        'type'      => 'color',
                        'output'    => array('.site-title'),
                        'title'     => __('Body Background Color', MYOOS_THEME_NAME),
                        'subtitle'  => __('Pick a background color for the theme (default: #fff).', MYOOS_THEME_NAME),
                        'default'   => '#FFFFFF',
                        'validate'  => 'color',
                    ),
                    array(
                        'id'        => 'opt-background',
                        'type'      => 'background',
                        'output'    => array('body'),
                        'title'     => __('Body Background', MYOOS_THEME_NAME),
                        'subtitle'  => __('Body background with image, color, etc.', MYOOS_THEME_NAME),
                        //'default'   => '#FFFFFF',
                    ),
                    array(
                        'id'        => 'opt-color-footer',
                        'type'      => 'color',
                        'title'     => __('Footer Background Color', MYOOS_THEME_NAME),
                        'subtitle'  => __('Pick a background color for the footer (default: #dd9933).', MYOOS_THEME_NAME),
                        'default'   => '#dd9933',
                        'validate'  => 'color',
                    ),
                    array(
                        'id'        => 'opt-color-rgba',
                        'type'      => 'color_rgba',
                        'title'     => __('Color RGBA - BETA', MYOOS_THEME_NAME),
                        'subtitle'  => __('Gives you the RGBA color. Still quite experimental. Use at your own risk.', MYOOS_THEME_NAME),
                        'default'   => array('color' => '#dd9933', 'alpha' => '1.0'),
                        'output'    => array('body'),
                        'mode'      => 'background',
                        'validate'  => 'colorrgba',
                    ),
                    array(
                        'id'        => 'opt-color-header',
                        'type'      => 'color_gradient',
                        'title'     => __('Header Gradient Color Option', MYOOS_THEME_NAME),
                        'subtitle'  => __('Only color validation can be done on this field type', MYOOS_THEME_NAME),
                        'desc'      => __('This is the description field, again good for additional info.', MYOOS_THEME_NAME),
                        'default'   => array(
                            'from'      => '#1e73be', 
                            'to'        => '#00897e'
                        )
                    ),
                    array(
                        'id'        => 'opt-link-color',
                        'type'      => 'link_color',
                        'title'     => __('Links Color Option', MYOOS_THEME_NAME),
                        'subtitle'  => __('Only color validation can be done on this field type', MYOOS_THEME_NAME),
                        'desc'      => __('This is the description field, again good for additional info.', MYOOS_THEME_NAME),
                        //'regular'   => false, // Disable Regular Color
                        //'hover'     => false, // Disable Hover Color
                        //'active'    => false, // Disable Active Color
                        //'visited'   => true,  // Enable Visited Color
                        'default'   => array(
                            'regular'   => '#aaa',
                            'hover'     => '#bbb',
                            'active'    => '#ccc',
                        )
                    ),
                    array(
                        'id'        => 'opt-header-border',
                        'type'      => 'border',
                        'title'     => __('Header Border Option', MYOOS_THEME_NAME),
                        'subtitle'  => __('Only color validation can be done on this field type', MYOOS_THEME_NAME),
                        'output'    => array('.site-header'), // An array of CSS selectors to apply this font style to
                        'desc'      => __('This is the description field, again good for additional info.', MYOOS_THEME_NAME),
                        'default'   => array(
                            'border-color'  => '#1e73be', 
                            'border-style'  => 'solid', 
                            'border-top'    => '3px', 
                            'border-right'  => '3px', 
                            'border-bottom' => '3px', 
                            'border-left'   => '3px'
                        )
                    ),
                    array(
                        'id'            => 'opt-spacing',
                        'type'          => 'spacing',
                        'output'        => array('.site-header'), // An array of CSS selectors to apply this font style to
                        'mode'          => 'margin',    // absolute, padding, margin, defaults to padding
                        'all'           => true,        // Have one field that applies to all
                        //'top'           => false,     // Disable the top
                        //'right'         => false,     // Disable the right
                        //'bottom'        => false,     // Disable the bottom
                        //'left'          => false,     // Disable the left
                        //'units'         => 'em',      // You can specify a unit value. Possible: px, em, %
                        //'units_extended'=> 'true',    // Allow users to select any type of unit
                        //'display_units' => 'false',   // Set to false to hide the units if the units are specified
                        'title'         => __('Padding/Margin Option', MYOOS_THEME_NAME),
                        'subtitle'      => __('Allow your users to choose the spacing or margin they want.', MYOOS_THEME_NAME),
                        'desc'          => __('You can enable or disable any piece of this field. Top, Right, Bottom, Left, or Units.', MYOOS_THEME_NAME),
                        'default'       => array(
                            'margin-top'    => '1px', 
                            'margin-right'  => '2px', 
                            'margin-bottom' => '3px', 
                            'margin-left'   => '4px'
                        )
                    ),
                    array(
                        'id'                => 'opt-dimensions',
                        'type'              => 'dimensions',
                        'units'             => 'em',    // You can specify a unit value. Possible: px, em, %
                        'units_extended'    => 'true',  // Allow users to select any type of unit
                        'title'             => __('Dimensions (Width/Height) Option', MYOOS_THEME_NAME),
                        'subtitle'          => __('aaa Allow your users to choose width, height, and/or unit.', MYOOS_THEME_NAME),
                        'desc'              => __('You can enable or disable any piece of this field. Width, Height, or Units.', MYOOS_THEME_NAME),
                        'default'           => array(
                            'width'     => 200, 
                            'height'    => 100,
                        )
                    ),
                    array(
                        'id'        => 'opt-typography-body',
                        'type'      => 'typography',
                        'title'     => __('Body Font', MYOOS_THEME_NAME),
                        'subtitle'  => __('Specify the body font properties.', MYOOS_THEME_NAME),
                        'google'    => true,
                        'default'   => array(
                            'color'         => '#dd9933',
                            'font-size'     => '30px',
                            'font-family'   => 'Arial,Helvetica,sans-serif',
                            'font-weight'   => 'Normal',
                        ),
                    ),
                    array(
                        'id'        => 'opt-custom-css',
                        'type'      => 'textarea',
                        'title'     => __('Custom CSS', MYOOS_THEME_NAME),
                        'subtitle'  => __('Quickly add some CSS to your theme by adding it to this block.', MYOOS_THEME_NAME),
                        'desc'      => __('This field is even CSS validated!', MYOOS_THEME_NAME),
                        'validate'  => 'css',
                    ),
                    array(
                        'id'        => 'opt-custom-html',
                        'type'      => 'textarea',
                        'title'     => __('Custom HTML', MYOOS_THEME_NAME),
                        'subtitle'  => __('Just like a text box widget.', MYOOS_THEME_NAME),
                        'desc'      => __('This field is even HTML validated!', MYOOS_THEME_NAME),
                        'validate'  => 'html',
                    ),
                )
            );

            /**
             *  Note here I used a 'heading' in the sections array construct
             *  This allows you to use a different title on your options page
             * instead of reusing the 'title' value.  This can be done on any
             * section - kp
             */
            $this->sections[] = array(
                'icon'      => 'el-icon-bullhorn',
                'title'     => __('Field Validation', MYOOS_THEME_NAME),
                'heading'   => __('Validate ALL fields within Redux.', MYOOS_THEME_NAME),
                'desc'      => __('<p class="description">This is the Description. Again HTML is allowed2</p>', MYOOS_THEME_NAME),
                'fields'    => array(
                    array(
                        'id'        => 'opt-text-email',
                        'type'      => 'text',
                        'title'     => __('Text Option - Email Validated', MYOOS_THEME_NAME),
                        'subtitle'  => __('This is a little space under the Field Title in the Options table, additional info is good in here.', MYOOS_THEME_NAME),
                        'desc'      => __('This is the description field, again good for additional info.', MYOOS_THEME_NAME),
                        'validate'  => 'email',
                        'msg'       => 'custom error message',
                        'default'   => 'test@test.com',
//                        'text_hint' => array(
//                            'title'     => 'Valid Email Required!',
//                            'content'   => 'This field required a valid email address.'
//                        )
                    ),
                    array(
                        'id'        => 'opt-text-post-type',
                        'type'      => 'text',
                        'title'     => __('Text Option with Data Attributes', MYOOS_THEME_NAME),
                        'subtitle'  => __('You can also pass an options array if you want. Set the default to whatever you like.', MYOOS_THEME_NAME),
                        'desc'      => __('This is the description field, again good for additional info.', MYOOS_THEME_NAME),
                        'data'      => 'post_type',
                    ),
                    array(
                        'id'        => 'opt-multi-text',
                        'type'      => 'multi_text',
                        'title'     => __('Multi Text Option - Color Validated', MYOOS_THEME_NAME),
                        'validate'  => 'color',
                        'subtitle'  => __('If you enter an invalid color it will be removed. Try using the text "blue" as a color.  ;)', MYOOS_THEME_NAME),
                        'desc'      => __('This is the description field, again good for additional info.', MYOOS_THEME_NAME)
                    ),
                    array(
                        'id'        => 'opt-text-url',
                        'type'      => 'text',
                        'title'     => __('Text Option - URL Validated', MYOOS_THEME_NAME),
                        'subtitle'  => __('This must be a URL.', MYOOS_THEME_NAME),
                        'desc'      => __('This is the description field, again good for additional info.', MYOOS_THEME_NAME),
                        'validate'  => 'url',
                        'default'   => 'http://reduxframework.com',
//                        'text_hint' => array(
//                            'title'     => '',
//                            'content'   => 'Please enter a valid <strong>URL</strong> in this field.'
//                        )
                    ),
                    array(
                        'id'        => 'opt-text-numeric',
                        'type'      => 'text',
                        'title'     => __('Text Option - Numeric Validated', MYOOS_THEME_NAME),
                        'subtitle'  => __('This must be numeric.', MYOOS_THEME_NAME),
                        'desc'      => __('This is the description field, again good for additional info.', MYOOS_THEME_NAME),
                        'validate'  => 'numeric',
                        'default'   => '0',
                    ),
                    array(
                        'id'        => 'opt-text-comma-numeric',
                        'type'      => 'text',
                        'title'     => __('Text Option - Comma Numeric Validated', MYOOS_THEME_NAME),
                        'subtitle'  => __('This must be a comma separated string of numerical values.', MYOOS_THEME_NAME),
                        'desc'      => __('This is the description field, again good for additional info.', MYOOS_THEME_NAME),
                        'validate'  => 'comma_numeric',
                        'default'   => '0',
                    ),
                    array(
                        'id'        => 'opt-text-no-special-chars',
                        'type'      => 'text',
                        'title'     => __('Text Option - No Special Chars Validated', MYOOS_THEME_NAME),
                        'subtitle'  => __('This must be a alpha numeric only.', MYOOS_THEME_NAME),
                        'desc'      => __('This is the description field, again good for additional info.', MYOOS_THEME_NAME),
                        'validate'  => 'no_special_chars',
                        'default'   => '0'
                    ),
                    array(
                        'id'        => 'opt-text-str_replace',
                        'type'      => 'text',
                        'title'     => __('Text Option - Str Replace Validated', MYOOS_THEME_NAME),
                        'subtitle'  => __('You decide.', MYOOS_THEME_NAME),
                        'desc'      => __('This field\'s default value was changed by a filter hook!', MYOOS_THEME_NAME),
                        'validate'  => 'str_replace',
                        'str'       => array(
                            'search'        => ' ', 
                            'replacement'   => 'thisisaspace'
                        ),
                        'default'   => 'This is the default.'
                    ),
                    array(
                        'id'        => 'opt-text-preg_replace',
                        'type'      => 'text',
                        'title'     => __('Text Option - Preg Replace Validated', MYOOS_THEME_NAME),
                        'subtitle'  => __('You decide.', MYOOS_THEME_NAME),
                        'desc'      => __('This is the description field, again good for additional info.', MYOOS_THEME_NAME),
                        'validate'  => 'preg_replace',
                        'preg'      => array(
                            'pattern'       => '/[^a-zA-Z_ -]/s', 
                            'replacement'   => 'no numbers'
                         ),
                        'default'   => '0'
                    ),
                    array(
                        'id'                => 'opt-text-custom_validate',
                        'type'              => 'text',
                        'title'             => __('Text Option - Custom Callback Validated', MYOOS_THEME_NAME),
                        'subtitle'          => __('You decide.', MYOOS_THEME_NAME),
                        'desc'              => __('This is the description field, again good for additional info.', MYOOS_THEME_NAME),
                        'validate_callback' => 'redux_validate_callback_function',
                        'default'           => '0'
                    ),
                    array(
                        'id'        => 'opt-textarea-no-html',
                        'type'      => 'textarea',
                        'title'     => __('Textarea Option - No HTML Validated', MYOOS_THEME_NAME),
                        'subtitle'  => __('All HTML will be stripped', MYOOS_THEME_NAME),
                        'desc'      => __('This is the description field, again good for additional info.', MYOOS_THEME_NAME),
                        'validate'  => 'no_html',
                        'default'   => 'No HTML is allowed in here.'
                    ),
                    array(
                        'id'        => 'opt-textarea-html',
                        'type'      => 'textarea',
                        'title'     => __('Textarea Option - HTML Validated', MYOOS_THEME_NAME),
                        'subtitle'  => __('HTML Allowed (wp_kses)', MYOOS_THEME_NAME),
                        'desc'      => __('This is the description field, again good for additional info.', MYOOS_THEME_NAME),
                        'validate'  => 'html', //see http://codex.wordpress.org/Function_Reference/wp_kses_post
                        'default'   => 'HTML is allowed in here.'
                    ),
                    array(
                        'id'            => 'opt-textarea-some-html',
                        'type'          => 'textarea',
                        'title'         => __('Textarea Option - HTML Validated Custom', MYOOS_THEME_NAME),
                        'subtitle'      => __('Custom HTML Allowed (wp_kses)', MYOOS_THEME_NAME),
                        'desc'          => __('This is the description field, again good for additional info.', MYOOS_THEME_NAME),
                        'validate'      => 'html_custom',
                        'default'       => '<p>Some HTML is allowed in here.</p>',
                        'allowed_html'  => array('') //see http://codex.wordpress.org/Function_Reference/wp_kses
                    ),
                    array(
                        'id'        => 'opt-textarea-js',
                        'type'      => 'textarea',
                        'title'     => __('Textarea Option - JS Validated', MYOOS_THEME_NAME),
                        'subtitle'  => __('JS will be escaped', MYOOS_THEME_NAME),
                        'desc'      => __('This is the description field, again good for additional info.', MYOOS_THEME_NAME),
                        'validate'  => 'js'
                    ),
                )
            );
            
            $this->sections[] = array(
                'icon'      => 'el-icon-check',
                'title'     => __('Radio/Checkbox Fields', MYOOS_THEME_NAME),
                'desc'      => __('<p class="description">This is the Description. Again HTML is allowed</p>', MYOOS_THEME_NAME),
                'fields'    => array(
                    array(
                        'id'        => 'opt-checkbox',
                        'type'      => 'checkbox',
                        'title'     => __('Checkbox Option', MYOOS_THEME_NAME),
                        'subtitle'  => __('No validation can be done on this field type', MYOOS_THEME_NAME),
                        'desc'      => __('This is the description field, again good for additional info.', MYOOS_THEME_NAME),
                        'default'   => '1'// 1 = on | 0 = off
                    ),
                    array(
                        'id'        => 'opt-multi-check',
                        'type'      => 'checkbox',
                        'title'     => __('Multi Checkbox Option', MYOOS_THEME_NAME),
                        'subtitle'  => __('No validation can be done on this field type', MYOOS_THEME_NAME),
                        'desc'      => __('This is the description field, again good for additional info.', MYOOS_THEME_NAME),
                        
                        //Must provide key => value pairs for multi checkbox options
                        'options'   => array(
                            '1' => 'Opt 1', 
                            '2' => 'Opt 2', 
                            '3' => 'Opt 3'
                        ),
                        
                        //See how std has changed? you also don't need to specify opts that are 0.
                        'default'   => array(
                            '1' => '1', 
                            '2' => '0', 
                            '3' => '0'
                        )
                    ),
                    array(
                        'id'        => 'opt-checkbox-data',
                        'type'      => 'checkbox',
                        'title'     => __('Multi Checkbox Option (with menu data)', MYOOS_THEME_NAME),
                        'subtitle'  => __('No validation can be done on this field type', MYOOS_THEME_NAME),
                        'desc'      => __('This is the description field, again good for additional info.', MYOOS_THEME_NAME),
                        'data'      => 'menu'
                    ),
                    array(
                        'id'        => 'opt-checkbox-sidebar',
                        'type'      => 'checkbox',
                        'title'     => __('Multi Checkbox Option (with sidebar data)', MYOOS_THEME_NAME),
                        'subtitle'  => __('No validation can be done on this field type', MYOOS_THEME_NAME),
                        'desc'      => __('This is the description field, again good for additional info.', MYOOS_THEME_NAME),
                        'data'      => 'sidebars'
                    ),
                    array(
                        'id'        => 'opt-radio',
                        'type'      => 'radio',
                        'title'     => __('Radio Option', MYOOS_THEME_NAME),
                        'subtitle'  => __('No validation can be done on this field type', MYOOS_THEME_NAME),
                        'desc'      => __('This is the description field, again good for additional info.', MYOOS_THEME_NAME),
                        
                         //Must provide key => value pairs for radio options
                        'options'   => array(
                            '1' => 'Opt 1', 
                            '2' => 'Opt 2', 
                            '3' => 'Opt 3'
                        ),
                        'default'   => '2'
                    ),
                    array(
                        'id'        => 'opt-radio-data',
                        'type'      => 'radio',
                        'title'     => __('Multi Checkbox Option (with menu data)', MYOOS_THEME_NAME),
                        'subtitle'  => __('No validation can be done on this field type', MYOOS_THEME_NAME),
                        'desc'      => __('This is the description field, again good for additional info.', MYOOS_THEME_NAME),
                        'data'      => 'menu'
                    ),
                    array(
                        'id'        => 'opt-image-select',
                        'type'      => 'image_select',
                        'title'     => __('Images Option', MYOOS_THEME_NAME),
                        'subtitle'  => __('No validation can be done on this field type', MYOOS_THEME_NAME),
                        'desc'      => __('This is the description field, again good for additional info.', MYOOS_THEME_NAME),
                        
                        //Must provide key => value(array:title|img) pairs for radio options
                        'options'   => array(
                            '1' => array('title' => 'Opt 1', 'img' => 'images/align-none.png'),
                            '2' => array('title' => 'Opt 2', 'img' => 'images/align-left.png'),
                            '3' => array('title' => 'Opt 3', 'img' => 'images/align-center.png'),
                            '4' => array('title' => 'Opt 4', 'img' => 'images/align-right.png')
                        ), 
                        'default'   => '2'
                    ),
                    array(
                        'id'        => 'opt-image-select-layout',
                        'type'      => 'image_select',
                        'title'     => __('Images Option for Layout', MYOOS_THEME_NAME),
                        'subtitle'  => __('No validation can be done on this field type', MYOOS_THEME_NAME),
                        'desc'      => __('This uses some of the built in images, you can use them for layout options.', MYOOS_THEME_NAME),
                        
                        //Must provide key => value(array:title|img) pairs for radio options
                        'options'   => array(
                            '1' => array('alt' => '1 Column',        'img' => ReduxFramework::$_url . 'assets/img/1col.png'),
                            '2' => array('alt' => '2 Column Left',   'img' => ReduxFramework::$_url . 'assets/img/2cl.png'),
                            '3' => array('alt' => '2 Column Right',  'img' => ReduxFramework::$_url . 'assets/img/2cr.png'),
                            '4' => array('alt' => '3 Column Middle', 'img' => ReduxFramework::$_url . 'assets/img/3cm.png'),
                            '5' => array('alt' => '3 Column Left',   'img' => ReduxFramework::$_url . 'assets/img/3cl.png'),
                            '6' => array('alt' => '3 Column Right',  'img' => ReduxFramework::$_url . 'assets/img/3cr.png')
                        ), 
                        'default' => '2'
                    ),
                    array(
                        'id'        => 'opt-sortable',
                        'type'      => 'sortable',
                        'title'     => __('Sortable Text Option', MYOOS_THEME_NAME),
                        'subtitle'  => __('Define and reorder these however you want.', MYOOS_THEME_NAME),
                        'desc'      => __('This is the description field, again good for additional info.', MYOOS_THEME_NAME),
                        'options'   => array(
                            'si1' => 'Item 1',
                            'si2' => 'Item 2',
                            'si3' => 'Item 3',
                        )
                    ),
                    array(
                        'id'        => 'opt-check-sortable',
                        'type'      => 'sortable',
                        'mode'      => 'checkbox', // checkbox or text
                        'title'     => __('Sortable Text Option', MYOOS_THEME_NAME),
                        'subtitle'  => __('Define and reorder these however you want.', MYOOS_THEME_NAME),
                        'desc'      => __('This is the description field, again good for additional info.', MYOOS_THEME_NAME),
                        'options'   => array(
                            'si1' => 'Item 1',
                            'si2' => 'Item 2',
                            'si3' => 'Item 3',
                        )
                    ),
                )
            );
            
            $this->sections[] = array(
                'icon'      => 'el-icon-list-alt',
                'title'     => __('Select Fields', MYOOS_THEME_NAME),
                'desc'      => __('<p class="description">This is the Description. Again HTML is allowed</p>', MYOOS_THEME_NAME),
                'fields'    => array(
                    array(
                        'id'        => 'opt-select',
                        'type'      => 'select',
                        'title'     => __('Select Option', MYOOS_THEME_NAME),
                        'subtitle'  => __('No validation can be done on this field type', MYOOS_THEME_NAME),
                        'desc'      => __('This is the description field, again good for additional info.', MYOOS_THEME_NAME),
                        
                        //Must provide key => value pairs for select options
                        'options'   => array(
                            '1' => 'Opt 1', 
                            '2' => 'Opt 2', 
                            '3' => 'Opt 3'
                        ),
                        'default'   => '2'
                    ),
                    array(
                        'id'        => 'opt-multi-select',
                        'type'      => 'select',
                        'multi'     => true,
                        'title'     => __('Multi Select Option', MYOOS_THEME_NAME),
                        'subtitle'  => __('No validation can be done on this field type', MYOOS_THEME_NAME),
                        'desc'      => __('This is the description field, again good for additional info.', MYOOS_THEME_NAME),
                        
                        //Must provide key => value pairs for radio options
                        'options'   => array(
                            '1' => 'Opt 1', 
                            '2' => 'Opt 2', 
                            '3' => 'Opt 3'
                        ), 
                        'required'  => array('select', 'equals', array('1', '3')),
                        'default'   => array('2', '3')
                    ),
                    array(
                        'id'        => 'opt-select-image',
                        'type'      => 'select_image',
                        'title'     => __('Select Image', MYOOS_THEME_NAME),
                        'subtitle'  => __('A preview of the selected image will appear underneath the select box.', MYOOS_THEME_NAME),
                        'options'   => $sample_patterns,
                        // Alternatively
                        //'options'   => Array(
                        //                'img_name' => 'img_path'
                        //             )
                        'default' => 'tree_bark.png',
                    ),
                    array(
                        'id'    => 'opt-info',
                        'type'  => 'info',
                        'desc'  => __('You can easily add a variety of data from WordPress.', MYOOS_THEME_NAME),
                    ),
                    array(
                        'id'        => 'opt-select-categories',
                        'type'      => 'select',
                        'data'      => 'categories',
                        'title'     => __('Categories Select Option', MYOOS_THEME_NAME),
                        'subtitle'  => __('No validation can be done on this field type', MYOOS_THEME_NAME),
                        'desc'      => __('This is the description field, again good for additional info.', MYOOS_THEME_NAME),
                    ),
                    array(
                        'id'        => 'opt-select-categories-multi',
                        'type'      => 'select',
                        'data'      => 'categories',
                        'multi'     => true,
                        'title'     => __('Categories Multi Select Option', MYOOS_THEME_NAME),
                        'subtitle'  => __('No validation can be done on this field type', MYOOS_THEME_NAME),
                        'desc'      => __('This is the description field, again good for additional info.', MYOOS_THEME_NAME),
                    ),
                    array(
                        'id'        => 'opt-select-pages',
                        'type'      => 'select',
                        'data'      => 'pages',
                        'title'     => __('Pages Select Option', MYOOS_THEME_NAME),
                        'subtitle'  => __('No validation can be done on this field type', MYOOS_THEME_NAME),
                        'desc'      => __('This is the description field, again good for additional info.', MYOOS_THEME_NAME),
                    ),
                    array(
                        'id'        => 'opt-multi-select-pages',
                        'type'      => 'select',
                        'data'      => 'pages',
                        'multi'     => true,
                        'title'     => __('Pages Multi Select Option', MYOOS_THEME_NAME),
                        'subtitle'  => __('No validation can be done on this field type', MYOOS_THEME_NAME),
                        'desc'      => __('This is the description field, again good for additional info.', MYOOS_THEME_NAME),
                    ),
                    array(
                        'id'        => 'opt-select-tags',
                        'type'      => 'select',
                        'data'      => 'tags',
                        'title'     => __('Tags Select Option', MYOOS_THEME_NAME),
                        'subtitle'  => __('No validation can be done on this field type', MYOOS_THEME_NAME),
                        'desc'      => __('This is the description field, again good for additional info.', MYOOS_THEME_NAME),
                    ),
                    array(
                        'id'        => 'opt-multi-select-tags',
                        'type'      => 'select',
                        'data'      => 'tags',
                        'multi'     => true,
                        'title'     => __('Tags Multi Select Option', MYOOS_THEME_NAME),
                        'subtitle'  => __('No validation can be done on this field type', MYOOS_THEME_NAME),
                        'desc'      => __('This is the description field, again good for additional info.', MYOOS_THEME_NAME),
                    ),
                    array(
                        'id'        => 'opt-select-menus',
                        'type'      => 'select',
                        'data'      => 'menus',
                        'title'     => __('Menus Select Option', MYOOS_THEME_NAME),
                        'subtitle'  => __('No validation can be done on this field type', MYOOS_THEME_NAME),
                        'desc'      => __('This is the description field, again good for additional info.', MYOOS_THEME_NAME),
                    ),
                    array(
                        'id'        => 'opt-multi-select-menus',
                        'type'      => 'select',
                        'data'      => 'menu',
                        'multi'     => true,
                        'title'     => __('Menus Multi Select Option', MYOOS_THEME_NAME),
                        'subtitle'  => __('No validation can be done on this field type', MYOOS_THEME_NAME),
                        'desc'      => __('This is the description field, again good for additional info.', MYOOS_THEME_NAME),
                    ),
                    array(
                        'id'        => 'opt-select-post-type',
                        'type'      => 'select',
                        'data'      => 'post_type',
                        'title'     => __('Post Type Select Option', MYOOS_THEME_NAME),
                        'subtitle'  => __('No validation can be done on this field type', MYOOS_THEME_NAME),
                        'desc'      => __('This is the description field, again good for additional info.', MYOOS_THEME_NAME),
                    ),
                    array(
                        'id'        => 'opt-multi-select-post-type',
                        'type'      => 'select',
                        'data'      => 'post_type',
                        'multi'     => true,
                        'title'     => __('Post Type Multi Select Option', MYOOS_THEME_NAME),
                        'subtitle'  => __('No validation can be done on this field type', MYOOS_THEME_NAME),
                        'desc'      => __('This is the description field, again good for additional info.', MYOOS_THEME_NAME),
                    ),
                    array(
                        'id'        => 'opt-multi-select-sortable',
                        'type'      => 'select',
                        'data'      => 'post_type',
                        'multi'     => true,
                        'sortable'  => true,
                        'title'     => __('Post Type Multi Select Option + Sortable', MYOOS_THEME_NAME),
                        'subtitle'  => __('This field also has sortable enabled!', MYOOS_THEME_NAME),
                        'desc'      => __('This is the description field, again good for additional info.', MYOOS_THEME_NAME),
                    ),
                    array(
                        'id'        => 'opt-select-posts',
                        'type'      => 'select',
                        'data'      => 'post',
                        'title'     => __('Posts Select Option2', MYOOS_THEME_NAME),
                        'subtitle'  => __('No validation can be done on this field type', MYOOS_THEME_NAME),
                        'desc'      => __('This is the description field, again good for additional info.', MYOOS_THEME_NAME),
                    ),
                    array(
                        'id'        => 'opt-multi-select-posts',
                        'type'      => 'select',
                        'data'      => 'post',
                        'multi'     => true,
                        'title'     => __('Posts Multi Select Option', MYOOS_THEME_NAME),
                        'subtitle'  => __('No validation can be done on this field type', MYOOS_THEME_NAME),
                        'desc'      => __('This is the description field, again good for additional info.', MYOOS_THEME_NAME),
                    ),
                    array(
                        'id'        => 'opt-select-roles',
                        'type'      => 'select',
                        'data'      => 'roles',
                        'title'     => __('User Role Select Option', MYOOS_THEME_NAME),
                        'subtitle'  => __('No validation can be done on this field type', MYOOS_THEME_NAME),
                        'desc'      => __('This is the description field, again good for additional info.', MYOOS_THEME_NAME),
                    ),
                    array(
                        'id'        => 'opt-select-capabilities',
                        'type'      => 'select',
                        'data'      => 'capabilities',
                        'multi'     => true,
                        'title'     => __('Capabilities Select Option', MYOOS_THEME_NAME),
                        'subtitle'  => __('No validation can be done on this field type', MYOOS_THEME_NAME),
                        'desc'      => __('This is the description field, again good for additional info.', MYOOS_THEME_NAME),
                    ),
                    array(
                        'id'        => 'opt-select-elusive',
                        'type'      => 'select',
                        'data'      => 'elusive-icons',
                        'title'     => __('Elusive Icons Select Option', MYOOS_THEME_NAME),
                        'subtitle'  => __('No validation can be done on this field type', MYOOS_THEME_NAME),
                        'desc'      => __('Here\'s a list of all the elusive icons by name and icon.', MYOOS_THEME_NAME),
                    ),
                )
            );

            $theme_info  = '<div class="redux-framework-section-desc">';
            $theme_info .= '<p class="redux-framework-theme-data description theme-uri">' . __('<strong>Theme URL:</strong> ', MYOOS_THEME_NAME) . '<a href="' . $this->theme->get('ThemeURI') . '" target="_blank">' . $this->theme->get('ThemeURI') . '</a></p>';
            $theme_info .= '<p class="redux-framework-theme-data description theme-author">' . __('<strong>Author:</strong> ', MYOOS_THEME_NAME) . $this->theme->get('Author') . '</p>';
            $theme_info .= '<p class="redux-framework-theme-data description theme-version">' . __('<strong>Version:</strong> ', MYOOS_THEME_NAME) . $this->theme->get('Version') . '</p>';
            $theme_info .= '<p class="redux-framework-theme-data description theme-description">' . $this->theme->get('Description') . '</p>';
            $tabs = $this->theme->get('Tags');
            if (!empty($tabs)) {
                $theme_info .= '<p class="redux-framework-theme-data description theme-tags">' . __('<strong>Tags:</strong> ', MYOOS_THEME_NAME) . implode(', ', $tabs) . '</p>';
            }
            $theme_info .= '</div>';

            if (file_exists(dirname(__FILE__) . '/../README.md')) {
                $this->sections['theme_docs'] = array(
                    'icon'      => 'el-icon-list-alt',
                    'title'     => __('Documentation', MYOOS_THEME_NAME),
                    'fields'    => array(
                        array(
                            'id'        => '17',
                            'type'      => 'raw',
                            'markdown'  => true,
                            'content'   => file_get_contents(dirname(__FILE__) . '/../README.md')
                        ),
                    ),
                );
            }
            
            // You can append a new section at any time.
            $this->sections[] = array(
                'icon'      => 'el-icon-eye-open',
                'title'     => __('Additional Fields', MYOOS_THEME_NAME),
                'desc'      => __('<p class="description">This is the Description. Again HTML is allowed</p>', MYOOS_THEME_NAME),
                'fields'    => array(
                    array(
                        'id'        => 'opt-datepicker',
                        'type'      => 'date',
                        'title'     => __('Date Option', MYOOS_THEME_NAME),
                        'subtitle'  => __('No validation can be done on this field type', MYOOS_THEME_NAME),
                        'desc'      => __('This is the description field, again good for additional info.', MYOOS_THEME_NAME)
                    ),
                    array(
                        'id'    => 'opt-divide',
                        'type'  => 'divide'
                    ),
                    array(
                        'id'        => 'opt-button-set',
                        'type'      => 'button_set',
                        'title'     => __('Button Set Option', MYOOS_THEME_NAME),
                        'subtitle'  => __('No validation can be done on this field type', MYOOS_THEME_NAME),
                        'desc'      => __('This is the description field, again good for additional info.', MYOOS_THEME_NAME),
                        
                        //Must provide key => value pairs for radio options
                        'options'   => array(
                            '1' => 'Opt 1', 
                            '2' => 'Opt 2', 
                            '3' => 'Opt 3'
                        ), 
                        'default'   => '2'
                    ),
                    array(
                        'id'        => 'opt-button-set-multi',
                        'type'      => 'button_set',
                        'title'     => __('Button Set, Multi Select', MYOOS_THEME_NAME),
                        'subtitle'  => __('No validation can be done on this field type', MYOOS_THEME_NAME),
                        'desc'      => __('This is the description field, again good for additional info.', MYOOS_THEME_NAME),
                        'multi'     => true,
                        
                        //Must provide key => value pairs for radio options
                        'options'   => array(
                            '1' => 'Opt 1', 
                            '2' => 'Opt 2', 
                            '3' => 'Opt 3'
                        ), 
                        'default'   => array('2', '3')
                    ),
                    array(
                        'id'        => 'opt-info-field',
                        'type'      => 'info',
                        'required'  => array('18', 'equals', array('1', '2')),
                        'desc'      => __('This is the info field, if you want to break sections up.', MYOOS_THEME_NAME)
                    ),
                    array(
                        'id'    => 'opt-info-warning',
                        'type'  => 'info',
                        'style' => 'warning',
                        'title' => __('This is a title.', MYOOS_THEME_NAME),
                        'desc'  => __('This is an info field with the warning style applied and a header.', MYOOS_THEME_NAME)
                    ),
                    array(
                        'id'    => 'opt-info-success',
                        'type'  => 'info',
                        'style' => 'success',
                        'icon'  => 'el-icon-info-sign',
                        'title' => __('This is a title.', MYOOS_THEME_NAME),
                        'desc'  => __('This is an info field with the success style applied, a header and an icon.', MYOOS_THEME_NAME)
                    ),
                    array(
                        'id'    => 'opt-info-critical',
                        'type'  => 'info',
                        'style' => 'critical',
                        'icon'  => 'el-icon-info-sign',
                        'title' => __('This is a title.', MYOOS_THEME_NAME),
                        'desc'  => __('This is an info field with the critical style applied, a header and an icon.', MYOOS_THEME_NAME)
                    ),
                    array(
                        'id'        => 'opt-raw_info',
                        'type'      => 'info',
                        'required'  => array('18', 'equals', array('1', '2')),
                        'raw_html'  => true,
                        'desc'      => $sampleHTML,
                    ),
                    array(
                        'id'        => 'opt-info-normal',
                        'type'      => 'info',
                        'notice'    => true,
                        'title'     => __('This is a title.', MYOOS_THEME_NAME),
                        'desc'      => __('This is an info notice field with the normal style applied, a header and an icon.', MYOOS_THEME_NAME)
                    ),
                    array(
                        'id'        => 'opt-notice-info',
                        'type'      => 'info',
                        'notice'    => true,
                        'style'     => 'info',
                        'title'     => __('This is a title.', MYOOS_THEME_NAME),
                        'desc'      => __('This is an info notice field with the info style applied, a header and an icon.', MYOOS_THEME_NAME)
                    ),
                    array(
                        'id'        => 'opt-notice-warning',
                        'type'      => 'info',
                        'notice'    => true,
                        'style'     => 'warning',
                        'icon'      => 'el-icon-info-sign',
                        'title'     => __('This is a title.', MYOOS_THEME_NAME),
                        'desc'      => __('This is an info notice field with the warning style applied, a header and an icon.', MYOOS_THEME_NAME)
                    ),
                    array(
                        'id'        => 'opt-notice-success',
                        'type'      => 'info',
                        'notice'    => true,
                        'style'     => 'success',
                        'icon'      => 'el-icon-info-sign',
                        'title'     => __('This is a title.', MYOOS_THEME_NAME),
                        'desc'      => __('This is an info notice field with the success style applied, a header and an icon.', MYOOS_THEME_NAME)
                    ),
                    array(
                        'id'        => 'opt-notice-critical',
                        'type'      => 'info',
                        'notice'    => true,
                        'style'     => 'critical',
                        'icon'      => 'el-icon-info-sign',
                        'title'     => __('This is a title.', MYOOS_THEME_NAME),
                        'desc'      => __('This is an notice field with the critical style applied, a header and an icon.', MYOOS_THEME_NAME)
                    ),

                )
            );

            $this->sections[] = array(
                'title'     => __('Import / Export', MYOOS_THEME_NAME),
                'desc'      => __('Import and Export your Redux Framework settings from file, text or URL.', MYOOS_THEME_NAME),
                'icon'      => 'el-icon-refresh',
                'fields'    => array(
                    array(
                        'id'            => 'opt-import-export',
                        'type'          => 'import_export',
                        'title'         => 'Import Export',
                        'subtitle'      => 'Save and restore your Redux options',
                        'full_width'    => false,
                    ),
                ),
            );                     
                    
            $this->sections[] = array(
                'type' => 'divide',
            );

            $this->sections[] = array(
                'icon'      => 'el-icon-info-sign',
                'title'     => __('Theme Information', MYOOS_THEME_NAME),
                'desc'      => __('<p class="description">This is the Description. Again HTML is allowed</p>', MYOOS_THEME_NAME),
                'fields'    => array(
                    array(
                        'id'        => 'opt-raw-info',
                        'type'      => 'raw',
                        'content'   => $item_info,
                    )
                ),
            );

            if (file_exists(trailingslashit(dirname(__FILE__)) . 'README.html')) {
                $tabs['docs'] = array(
                    'icon'      => 'el-icon-book',
                    'title'     => __('Documentation', MYOOS_THEME_NAME),
                    'content'   => nl2br(file_get_contents(trailingslashit(dirname(__FILE__)) . 'README.html'))
                );
            }
        }

        public function setHelpTabs() {

            // Custom page help tabs, displayed using the help API. Tabs are shown in order of definition.
            $this->args['help_tabs'][] = array(
                'id'        => 'redux-help-tab-1',
                'title'     => __('Theme Information 1', MYOOS_THEME_NAME),
                'content'   => __('<p>This is the tab content, HTML is allowed.</p>', MYOOS_THEME_NAME)
            );

            $this->args['help_tabs'][] = array(
                'id'        => 'redux-help-tab-2',
                'title'     => __('Theme Information 2', MYOOS_THEME_NAME),
                'content'   => __('<p>This is the tab content, HTML is allowed.</p>', MYOOS_THEME_NAME)
            );

            // Set the help sidebar
            $this->args['help_sidebar'] = __('<p>This is the sidebar content, HTML is allowed.</p>', MYOOS_THEME_NAME);
        }

        /**

          All the possible arguments for Redux.
          For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments

         * */
        public function setArguments() {

            $theme = wp_get_theme(); // For use with some settings. Not necessary.

            $this->args = array(
                'opt_name' => 'MyOOS Theme',
                'page_slug' => '_options',
                'page_title' => 'Theme Options',
                'update_notice' => true,
                'intro_text' => '',
                'footer_text' => '',
                'admin_bar' => true,
                'menu_type' => 'menu',
                'menu_title' => 'Theme Options',
                'allow_sub_menu' => true,
                'page_parent_post_type' => 'your_post_type',
                'customizer' => true,
                'default_show' => true,
                'default_mark' => '*',
                'google_api_key' => 'AIzaSyCX0CYMy6tM6HG-a7A1nImbQeX4ZplnlbI',
				'dev_mode' => false,
                'hints' => 
                array(
                  'icon' => 'el-icon-question-sign',
                  'icon_position' => 'right',
                  'icon_size' => 'normal',
                  'tip_style' => 
                  array(
                    'color' => 'light',
                  ),
                  'tip_position' => 
                  array(
                    'my' => 'top left',
                    'at' => 'bottom right',
                  ),
                  'tip_effect' => 
                  array(
                    'show' => 
                    array(
                      'duration' => '500',
                      'event' => 'mouseover',
                    ),
                    'hide' => 
                    array(
                      'duration' => '500',
                      'event' => 'mouseleave unfocus',
                    ),
                  ),
                ),
                'output' => true,
                'output_tag' => true,
                'compiler' => true,
                'page_icon' => 'icon-themes',
                'page_permissions' => 'manage_options',
                'save_defaults' => true,
                'show_import_export' => true,
                'transient_time' => '3600',
                'network_sites' => true,
              );

            $theme = wp_get_theme(); // For use with some settings. Not necessary.
            $this->args["display_name"] = $theme->get("Name");
            $this->args["display_version"] = $theme->get("Version");
/*
// SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
            $this->args['share_icons'][] = array(
                'url'   => 'https://github.com/ReduxFramework/ReduxFramework',
                'title' => 'Visit us on GitHub',
                'icon'  => 'el-icon-github'
                //'img'   => '', // You can use icon OR img. IMG needs to be a full URL.
            );
            $this->args['share_icons'][] = array(
                'url'   => 'https://www.facebook.com/pages/Redux-Framework/243141545850368',
                'title' => 'Like us on Facebook',
                'icon'  => 'el-icon-facebook'
            );
            $this->args['share_icons'][] = array(
                'url'   => 'http://twitter.com/reduxframework',
                'title' => 'Follow us on Twitter',
                'icon'  => 'el-icon-twitter'
            );
            $this->args['share_icons'][] = array(
                'url'   => 'http://www.linkedin.com/company/redux-framework',
                'title' => 'Find us on LinkedIn',
                'icon'  => 'el-icon-linkedin'
            );
*/
        }

    }
    
    global $reduxConfig;
    $reduxConfig = new Redux_Framework_config();
}

/**
  Custom function for the callback referenced above
 */
if (!function_exists('redux_my_custom_field')):
    function redux_my_custom_field($field, $value) {
        print_r($field);
        echo '<br/>';
        print_r($value);
    }
endif;

/**
  Custom function for the callback validation referenced above
 * */
if (!function_exists('redux_validate_callback_function')):
    function redux_validate_callback_function($field, $value, $existing_value) {
        $error = false;
        $value = 'just testing';

        /*
          do your validation

          if(something) {
            $value = $value;
          } elseif(something else) {
            $error = true;
            $value = $existing_value;
            $field['msg'] = 'your custom error message';
          }
         */

        $return['value'] = $value;
        if ($error == true) {
            $return['error'] = $field;
        }
        return $return;
    }
endif;
