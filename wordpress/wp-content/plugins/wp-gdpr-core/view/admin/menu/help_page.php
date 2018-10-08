<?php namespace wp_gdpr\view\admin;
use wp_gdpr\lib\Gdpr_Helper;
/**
 * this template is to show manu page in admin-menu
 */
?>
<div class="wrap">
    <h2><b><?php _e( 'Help', 'wp_gdpr' ); ?></b> <?php _e( 'Center', 'wp_gdpr' ); ?></h2>
    <p align="center"><img class="a_background_img" src="<?php echo GDPR_URL . 'assets/images/logo-trans-bg.png'; ?>">
    </p>
    <div id="nav_menu">
        <a id="a_help" class="active_tab" href="<?php echo admin_url( 'admin.php?page=help' ) ?>"><span
                    class="dashicons dashicons-editor-help"></span>&nbsp;Help</a>
<!--    Not for v1.5!!!     <a id="a_datarequest" href="--><?php //echo admin_url( 'admin.php?page=wp_gdpr' ) ?><!--"><span-->
<!--                    class="dashicons dashicons-admin-page"></span>System info</a>-->
        <a id="a_settings" href="<?php echo admin_url( 'admin.php?page=settings_wp-gdpr' ) ?>"><span
                    class="dashicons dashicons-admin-generic"></span>&nbsp;Settings</a>
        <a id="a_addon" href="<?php echo admin_url( 'admin.php?page=addon' ) ?>"><span class="dashicons dashicons-screenoptions"></span> Add-ons</a>
    </div>
    <div id="nav_menu_extra">
        <a id="a_review" target="_blank"
           href="https://wordpress.org/support/plugin/wp-gdpr-core/reviews/#new-post"><span
                    class="dashicons dashicons-admin-comments"></span>&nbsp;Review
            our plugin</a>
        <a id="a_homepage" target="_blank" href="https://wp-gdpr.eu/"><span
                    class="dashicons dashicons-admin-home"></span>&nbsp;Visit our homepage</a>
    </div>
    <br>
    <div id="user_guides">
        <div class="user_guides_header">
            <h4>Guide, Tutorials & Informative blogposts</h4>
        </div>
        <div class="user_guides_content">
            <img class="a_info" src="<?php echo GDPR_URL . 'assets/images/icon-info-bg.png'; ?>">
            <section class="variable slider">
	            <?php $plugins = Gdpr_Helper::get_plugin_addon_status();
	            if (is_array($plugins) && count( $plugins ) != 0) :
		            foreach ($plugins as $plugin_data ): ?>
                        <div><a href="<?php echo $plugin_data['plugin_tutorial_link'] ?>" target="_blank"><img class="carousel_img" src="<?php echo $plugin_data['plugin_tutorial_img'] ?>" alt="Tutorial - Install WP GDPR for add-ons/core">
                                <p align="center"><?php echo $plugin_data['plugin_tutorial_intro'] ?></p></a>
                        </div>
		            <?php endforeach;
	            endif; ?>

            </section>
        </div>
    </div>
    <div id="user_freq_questions">
        <div class="user_freq_questions_header">
            <h4>Frequently Asked Questions</h4>
            <div class="container user_freq_questions_content">
                <ul class="tabs">
                    <li class="tab-link current" data-tab="tab-1"><b>WP-GDPR Core</b></li>
                    <li class="tab-link" data-tab="tab-2"><b>Add-ons</b></li>
                </ul>
                <div id="tab-1" class="tab-content current">
                    <div id="accordion_core">
                        <h4><span><img src="<?php echo GDPR_URL . 'assets/images/icon_arrow.png'; ?>" alt="" class="icon_arrow"></span>How do I install the WP-GDPR Core plugin?</h4>
                        <div class="accordion-content">
                            <ol>
                                <li>Upload the plugin files to the /wp-content/plugins, or install the plugin trough th WordPress plugins screen directly.</li>
                                <li>Activate the plugin through the 'Plugins' screen in WordPress.</li>
                                <li>'WP GDPR' will be created in the admin menu where you can view requests & settings.</li>
                                <li>The page 'GDPR - Request personal data' will be created automatically. This page displays the form where visitors can submit their request.</li>
                            </ol>
                        </div>
                        <h4><span><img src="<?php echo GDPR_URL . 'assets/images/icon_arrow.png'; ?>" alt="" class="icon_arrow"></span>What does the WP-GDPR Core plugin do exactly?</h4>
                        <div class="accordion-content">
                            <p>Our core plugin makes your WordPress GDPR compliant by providing a platform where all personal data can be collected and an automatic system for users to access that data securely. Features (as of v 1.4.4) are :</p>
                            <ul>
                                <li>
                                    Automatically create a request page with the shortcode [ REQ_CRED_FORM ]
                                    <ul>
                                        <li>
                                            Users can enter their emailaddress in this form to request access to their personal data
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    Secure access link emailed to the user to access their personal data
                                </li>
                                <li>
                                    Apply the consent box to all WordPress comments
                                </li>
                                <li>
                                    Gather all data from WordPress comments to be included in the personal data register
                                </li>
                                <li>
                                    User tools while accessing their personal data :
                                    <ul>
                                        <li>
                                            View their personal data
                                        </li>
                                        <li>
                                            Download their personal data
                                        </li>
                                        <li>
                                            Update their personal data
                                        </li>
                                        <li>
                                            Request to delete their personal data

                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    Admin views for :
                                    <ul>
                                        <li>
                                            List all requested access and resend mails
                                        </li>
                                        <li>
                                            List of all request for deletion and perform the action ( delete or anonymize )
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    Settings page for the consent box content

                                </li>
                                <li>
                                    Email address for DPO
                                </li>
                                <li>
                                    List of all plugins that use personal data
                                </li>
                            </ul>
                        </div>
                        <h4><span><img src="<?php echo GDPR_URL . 'assets/images/icon_arrow.png'; ?>" alt="" class="icon_arrow"></span>What is the DPO Email address for?</h4>
                        <div class="accordion-content">
                            <p>
                                The DPO or Data Protection Officer is the person handling all GDPR issues for the organisation.
                                Entering an email address on the settings page will make sure all access & delete requests are mailed to the DPO.
                            </p>

                        </div>
                        <h4><span><img src="<?php echo GDPR_URL . 'assets/images/icon_arrow.png'; ?>" alt="" class="icon_arrow"></span>When do I need to ask for consent?</h4>
                        <div>
                            <p>
                                Every form on your website that asks for <a href="https://ec.europa.eu/info/law/law-topic/data-protection/reform/what-personal-data_en" target="_blank"><b>personal data</b></a> needs to have a clear consent of the user.
                                More info on the subject van be found <a href="https://www.itgovernance.eu/blog/en/gdpr-when-do-you-need-to-seek-consent/" target="_blank"><b>here</b></a>.
                            </p>
                        </div>
                        <h4><span><img src="<?php echo GDPR_URL . 'assets/images/icon_arrow.png'; ?>" alt="" class="icon_arrow"></span>Which articles in the GDPR law does this plugin cover?</h4>
                        <div class="accordion-content">
                            <ul>
                                <li>
                                    Users can view their data ( Art 15 GDPR )
                                </li>
                                <li>
                                    Users can adapt their personal data ( Art 16 GDPR )
                                </li>
                                <li>
                                    Users can download their personal data ( Art 20 GDPR )
                                </li>
                                <li>
                                    Users can request to delete their data ( Art 17 GDPR )
                                </li>
                            </ul>
                        </div>
                        <h4><span><img src="<?php echo GDPR_URL . 'assets/images/icon_arrow.png'; ?>" alt="" class="icon_arrow"></span>Does WP-GDPR make my hole company GDPR Compliant?</h4>
                        <div class="accordion-content">
                            <p>
                                As stated on our website we are not lawyers, we are WordPress developers that followed courses on GDPR. Our company is GDPR Compliant and through our plugin we try to make life easier for website owners.
                                The features we implement in our plugin and add-ons are created to be compliant with several articles of the GDPR law, but we always advise to consult a lawyer to make sure your whole business is compliant with the GDPR law.
                            </p>
                        </div>
                        <h4><span><img src="<?php echo GDPR_URL . 'assets/images/icon_arrow.png'; ?>" alt="" class="icon_arrow"></span>Where do I need to display the link to my Privacy Policy?</h4>
                        <div class="accordion-content">
                            <p>
                                Generally the link to your Privacy Policy is displayed in your websites footer.
                            </p>
                        </div>
                        <h4><span><img src="<?php echo GDPR_URL . 'assets/images/icon_arrow.png'; ?>" alt="" class="icon_arrow"></span>What information should be displayed in my Privacy Policy?</h4>
                        <div class="accordion-content">
                            <p>
                                As per the GDPR , the information you provide to people about how you process their personal data has to be :
                            </p>
                            <ul>
                                <li>
                                    Free of charge
                                </li>
                                <li>
                                    Transparant, intelligible, easily accessible & concise

                                </li>
                                <li>
                                    The writing should be clear & in plain language, particularly if addressing a child
                                </li>
                            </ul>
                            <p>
                                It's necessary to provide the link to the access request page in your Privacy Policy.
                                For a good example of a Privacy Statement click <a href="https://ec.europa.eu/taxation_customs/about/privacy-statement-internet-website-commissions-taxation-customs-union-directorategeneral_en" target="_blank"><b>here</b></a>.
                            </p>
                        </div>
                        <h4><span><img src="<?php echo GDPR_URL . 'assets/images/icon_arrow.png'; ?>" alt="" class="icon_arrow"></span>I have a support question, where do i go?</h4>
                        <div class="accordion-content">
                            <p>
                                We encourage all users of the WP-GDPR core and all its support questions related to this plugin make a support thread at our wordpress.org support page.
                            </p>
                        </div>
                    </div>
                </div>
                <div id="tab-2" class="tab-content">
                    <div id="accordion_addons">
                        <h4><span><img src="<?php echo GDPR_URL . 'assets/images/icon_arrow.png'; ?>" alt="" class="icon_arrow"></span>What are add-ons and why do they cost money?</h4>
                        <div class="accordion-content">
                            <p>
                                Add-ons are additional plugins that extend the features of our WP-GDPR core plugin to other popular plugins. ( for example: Gravity Forms )

                                All our add-ons are premium because it takes alot of resources to check the complete engine behind another plugin and then build hooks to let it cooperate with our core plugin. It also enables us to keep the wp-gdpr core updated and supported by our team.
                            </p>
                        </div>
                        <h4><span><img src="<?php echo GDPR_URL . 'assets/images/icon_arrow.png'; ?>" alt="" class="icon_arrow"></span>Why does plugin X not have an add-on?</h4>
                        <div class="accordion-content">
                            <p>
                                This plugin is still very young. We are working hard to add meaningful updates for the core plugin while also adding add-ons.

                                Some plugins are currently working on their own add-on for WP-GDPR ( <a href="https://calderaforms.com/" target="_blank"><b>Caldera Forms</b></a> &
                                <a href="https://easydigitaldownloads.com/" target="_blank"><b>Easy Digital Downloads</b></a> for example ).
                            </p>
                        </div>
                        <h4><span><img src="<?php echo GDPR_URL . 'assets/images/icon_arrow.png'; ?>" alt="" class="icon_arrow"></span>How can I request add-on X?</h4>
                        <div class="accordion-content">
                            <p>
                                The decision process to start working on a particular add-on is through sheer popularity on our
                                <a href="https://wp-gdpr.eu/add-ons/"><b>add-ons</b></a> page, so if you want to speed up the process : Go vote!
                                Another alternate method is having an Agency license. Every <a href="https://wp-gdpr.eu/pricing/" target="_blank"><b>Agency</b></a> can request a custom add-on for a plugin of their choice.
                            </p>

                        </div>
                        <h4><span><img src="<?php echo GDPR_URL . 'assets/images/icon_arrow.png'; ?>" alt="" class="icon_arrow"></span>Why Contact Form 7 DB?</h4>
                        <div>
                            <p>
                                Contact Form 7 out of the box does not store personal data , only emails it.
                                When you activate the free plugin <a href="https://wordpress.org/plugins/contact-form-cfdb7/" target="_blank"><b>CFDB7</b></a> all your form entries will be stored in the database and it will allow our plugin to categorise this data and make it viewable in the Personal Data Overview.
                            </p>
                        </div>
                        <h4><span><img src="<?php echo GDPR_URL . 'assets/images/icon_arrow.png'; ?>" alt="" class="icon_arrow"></span>What does your plugin do for Gravity Forms?</h4>
                        <div class="accordion-content">
                            <ul>
                                <li>
                                    Shows personal data of all Gravity Form entries in our Personal Data Overview.
                                </li>
                                <li>
                                    Hooks into E-mail fields , Name fields , Address fields , Phone fields.
                                </li>
                                <li>
                                    NO automatic consent checkbox, follow our tutorial to see how this works.
                                </li>
                            </ul>
                            <p>
                                <b>
                                    Makes your Gravity Forms compliant for GDPR Articles 15, 16 , 17 , 20
                                </b>
                            </p>
                        </div>
                        <h4><span><img src="<?php echo GDPR_URL . 'assets/images/icon_arrow.png'; ?>" alt="" class="icon_arrow"></span>What does your plugin do for Contact Form DB 7?</h4>
                        <div class="accordion-content">
                            <ul>
                                <li>
                                    Shows personal data fields that are selected by the admin in our Personal Data Overview ( To select these fields check out our
                                    <a href="https://wp-gdpr.eu/portfolio/make-your-contact-form-7-gdpr-ready/" target="_blank"><b>tutorial</b></a>)
                                </li>
                                <li>
                                    NO automatic consent checkbox, follow our tutorial to see how this works.
                                </li>
                            </ul>
                            <p>
                                <b>
                                    Makes your Contact Form 7 forms compliant for GDPR Articles 15, 16 , 17 , 20
                                </b>
                            </p>
                        </div>
                        <h4><span><img src="<?php echo GDPR_URL . 'assets/images/icon_arrow.png'; ?>" alt="" class="icon_arrow"></span>What does your plugin do for WooCommerce?</h4>
                        <div class="accordion-content">
                            <ul>
                                <li>
                                    Shows personal data fields in our Personal Data Overview.
                                </li>
                                <li>
                                    It's not possible to delete fields needed for billing as these are required by law.
                                </li>
                                <li>
                                    NO automatic consent checkbox yet, this is planned in our next woocommerce update.
                                </li>
                            </ul>
                            <p>
                                <b>
                                    Makes your WooCommerce compliant for GDPR Articles 15, 16 , 17 , 20
                                </b>
                            </p>
                        </div>
                        <h4><span><img src="<?php echo GDPR_URL . 'assets/images/icon_arrow.png'; ?>" alt="" class="icon_arrow"></span>Can I upgrade my license?</h4>
                        <div class="accordion-content">
                            <p>
                                If you bought a single license or multiple single licenses and you would like to upgrade to a professional/agency license we will give you a discount worth the total amount of single licenses you bought before. This offer is valid until the 25th of May 2018.

                                To upgrade, drop us an <a href="https://wp-gdpr.eu/contact/" target="_blank"><b>e-mail</b></a> or talk to us in live chat.
                            </p>
                        </div>
                        <h4><span><img src="<?php echo GDPR_URL . 'assets/images/icon_arrow.png'; ?>" alt="" class="icon_arrow"></span>Where do I access my downloads & invoices?</h4>
                        <div class="accordion-content">
                            <p>
                                In the top right of our page you can login with the account you created during your checkout. Once logged in you will see My Account appear where you will find all needed information.
                                <a href="https://wp-gdpr.eu/my-account/" target="_blank"><b>Direct link</b></a>.
                            </p>
                        </div>
                        <h4><span><img src="<?php echo GDPR_URL . 'assets/images/icon_arrow.png'; ?>" alt="" class="icon_arrow"></span>Is there premium support?</h4>
                        <div class="accordion-content">
                            <p>
                                Yes! Create a ticket on <a href="https://wp-gdpr.eu/my-account/" target="_blank"><b>this page</b></a>.
                            </p>
                        </div>
                    </div>
                </div>



            </div>
        </div>


    </div>
    <div id="user_support">
        <div class="user_support_header">
            <h4>Support</h4>
        </div>
        <div class="user_support_content">
            <p align="center">Before you contact support, be sure to read our <a href="#user_freq_questions">FAQ</a> and
                check our
                <a href="">Guides & Tutorials.</a></p>
<!--    Not for v1.5!!!         <p align="center">Still need help? <b>Make sure to click on <a href="#"><span-->
<!--                                class="dashicons dashicons-admin-page"></span>System info</a> at the top to copy your-->
<!--                    information. ( Support will ask for it )</b></p>-->
            <div class="users_support_btn_group">
                <div class="user_support_wp">
                    <p>I need help for WP-GDPR core plugin.</p>
                    <a class="button button-primary" target="_blank" href="https://wordpress.org/support/plugin/wp-gdpr-core">Support on
                        wordpress.org</a>
                </div>
                <div class="user_support_premium">
                    <p>I bought an add-on and need help..</p>
                    <a class="button button-succes" target="_blank" href="https://wp-gdpr.eu/support/">Premium Support Tickets</a>
                </div>
            </div>
        </div>
        <img class="a_support" src="<?php echo GDPR_URL . 'assets/images/icon-support.png'; ?>">
    </div>
</div>
<p class="appsaloon_footer">WP-GDPR <?php echo Gdpr_Helper::get_core_version() ?> developed by <a
            href="https://appsaloon.be/" target="_blank"><b>Appsaloon</b></a></p>
