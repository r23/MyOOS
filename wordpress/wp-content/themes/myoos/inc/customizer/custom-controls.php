<?php
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cpschool_register_custom_kirki_controls' ) ) {
    // Register custom controls used with Kirki framework.
    add_action( 'customize_register', 'cpschool_register_custom_kirki_controls');

    function cpschool_register_custom_kirki_controls() {
        /**
         * Separator Control for Kirki customizer framework that visualy seperates settings in single tab.
         */
        class Kirki_Controls_CPS_Separator_Control extends Kirki_Control_Base {
            public $type = 'separator';

            // Enqueue scripts related to this control.
            public function enqueue() {
                add_action( 'customize_controls_print_styles', array('Kirki_Controls_CPS_Separator_Control', 'render_styling'), 999 );
            }

            // Render control output.
            public function render_content() {
                if($this->label) {
                ?>
                    <div class="customize-control-separator-item" style="font-size: 16px;">
                        <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
                        <?php if($this->description) { ?>
                            <span class="description customize-control-description"><?php echo esc_html($this->description); ?></span>
                        <?php } ?>
                    </div>
                <?php
                }
                else {
                    echo '<hr>';
                }
            }

            // Render control stylings.
            public static function render_styling() {
            ?>
                <style>
                    .customize-control-separator-item {
                        font-size: 16px;
                        color: #555d66;
                        background-color: #fbfbfb;
                        border: 1px solid #ddd;
                        border-left: none;
                        border-right: none;
                        margin-left: -12px;
                        margin-right: -14px;
                        padding: 10px 14px 10px 12px;
                    }
                    .customize-control-separator-item .customize-control-title {
                        margin-bottom: 0px;
                    }
                    .customize-control-separator-item .customize-control-description {
                        margin-top: 4px;
                        margin-bottom: 0px;
                    }
                </style>
            <?php
            }
        }

        /**
         * Hidden Control for Kirki customizer framework that adds hidden options.
         */
        class Kirki_Controls_CPS_Hidden_Control extends Kirki_Control_Base {
            public $type = 'hidden';
            public function render_content() {
            ?>
                <input type="hidden" data-id="<?php echo esc_attr($this->id); ?>" value="<?php echo esc_attr( $this->value() ); ?>"/>
            <?php
            }
        }
    }
}

if ( ! function_exists( 'cpschool_kirki_control_types' ) ) {
    // Enable new controls in Kirki.
    add_filter('kirki_control_types', 'cpschool_kirki_control_types', 10, 2 );

    function cpschool_kirki_control_types($controls) {
        $controls['hidden'] = 'Kirki_Controls_CPS_Hidden_Control';
        $controls['separator'] = 'Kirki_Controls_CPS_Separator_Control';
    
        return $controls;
    }
}