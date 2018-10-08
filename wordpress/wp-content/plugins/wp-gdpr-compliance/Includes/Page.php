<?php

namespace WPGDPRC\Includes;

/**
 * Class Page
 * @package WPGDPRC\Includes
 */
class Page {
    /** @var null */
    private static $instance = null;

    public function registerSettings() {
        foreach (Helper::getCheckList() as $id => $check) {
            register_setting(WP_GDPR_C_SLUG . '_general', WP_GDPR_C_PREFIX . '_general_' . $id, 'intval');
        }
        register_setting(WP_GDPR_C_SLUG . '_settings', WP_GDPR_C_PREFIX . '_settings_privacy_policy_page', 'intval');
        register_setting(WP_GDPR_C_SLUG . '_settings', WP_GDPR_C_PREFIX . '_settings_privacy_policy_text', array('sanitize_callback' => array(Helper::getInstance(), 'sanitizeData')));
        register_setting(WP_GDPR_C_SLUG . '_settings', WP_GDPR_C_PREFIX . '_settings_enable_access_request', 'intval');
        if (Helper::isEnabled('enable_access_request', 'settings')) {
            register_setting(WP_GDPR_C_SLUG . '_settings', WP_GDPR_C_PREFIX . '_settings_access_request_page', 'intval');
            register_setting(WP_GDPR_C_SLUG . '_settings', WP_GDPR_C_PREFIX . '_settings_access_request_form_checkbox_text');
            register_setting(WP_GDPR_C_SLUG . '_settings', WP_GDPR_C_PREFIX . '_settings_delete_request_form_explanation_text');
        }
        register_setting(WP_GDPR_C_SLUG . '_settings', WP_GDPR_C_PREFIX . '_settings_consents_modal_title');
        register_setting(WP_GDPR_C_SLUG . '_settings', WP_GDPR_C_PREFIX . '_settings_consents_modal_explanation_text');
        register_setting(WP_GDPR_C_SLUG . '_settings', WP_GDPR_C_PREFIX . '_settings_consents_bar_explanation_text');
    }

    public function addAdminMenu() {
        $pluginData = Helper::getPluginData();
        add_submenu_page(
            'tools.php',
            $pluginData['Name'],
            $pluginData['Name'],
            'manage_options',
            str_replace('-', '_', WP_GDPR_C_SLUG),
            array($this, 'generatePage')
        );
    }

    public function generatePage() {
        $type = (isset($_REQUEST['type'])) ? esc_html($_REQUEST['type']) : false;
        $pluginData = Helper::getPluginData();
        $enableAccessRequest = Helper::isEnabled('enable_access_request', 'settings');
        $adminUrl = Helper::getPluginAdminUrl();
        ?>
        <div class="wrap">
            <div class="wpgdprc">
                <div class="wpgdprc-contents">
                    <h1 class="wpgdprc-title"><?php echo $pluginData['Name']; ?> <span><?php printf('v%s', $pluginData['Version']); ?></span></h1>

                    <?php settings_errors(); ?>

                    <div class="wpgdprc-navigation wpgdprc-clearfix">
                        <a class="<?php echo (empty($type)) ? 'wpgdprc-active' : ''; ?>" href="<?php echo $adminUrl; ?>"><?php _e('Integrations', WP_GDPR_C_SLUG); ?></a>
                        <a class="<?php echo checked('consents', $type, false) ? 'wpgdprc-active' : ''; ?>" href="<?php echo $adminUrl; ?>&type=consents"><?php _e('Consents', WP_GDPR_C_SLUG); ?></a>
                        <?php
                        if ($enableAccessRequest) :
                            $totalDeleteRequests = DeleteRequest::getInstance()->getTotal(array(
                                'processed' => array(
                                    'value' => 0
                                )
                            ));
                            ?>
                            <a class="<?php echo checked('requests', $type, false) ? 'wpgdprc-active' : ''; ?>" href="<?php echo $adminUrl; ?>&type=requests">
                                <?php _e('Requests', WP_GDPR_C_SLUG); ?>
                                <?php
                                if ($totalDeleteRequests > 1) {
                                    printf('<span class="wpgdprc-badge">%d</span>', $totalDeleteRequests);
                                }
                                ?>
                            </a>
                            <?php
                        endif;
                        ?>
                        <a class="<?php echo checked('checklist', $type, false) ? 'wpgdprc-active' : ''; ?>" href="<?php echo $adminUrl; ?>&type=checklist"><?php _e('Checklist', WP_GDPR_C_SLUG); ?></a>
                        <a class="<?php echo checked('settings', $type, false) ? 'wpgdprc-active' : ''; ?>" href="<?php echo $adminUrl; ?>&type=settings"><?php _e('Settings', WP_GDPR_C_SLUG); ?></a>
                    </div>

                    <div class="wpgdprc-content wpgdprc-clearfix">
                        <?php
                        switch ($type) {
                            case 'consents' :
                                $action = (isset($_REQUEST['action'])) ? esc_html($_REQUEST['action']) : false;
                                switch ($action) {
                                    case 'manage' :
                                        $id = (isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])) ? intval($_REQUEST['id']) : 0;
                                        self::renderManageConsentPage($id);
                                        break;
                                    default :
                                        self::renderConsentsPage();
                                        break;
                                }
                                break;
                            case 'requests' :
                                $id = (isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])) ? intval($_REQUEST['id']) : 0;
                                if (!empty($id) && AccessRequest::getInstance()->exists($id)) {
                                    self::renderManageRequestPage($id);
                                } else {
                                    self::renderRequestsPage();
                                }
                                break;
                            case 'checklist' :
                                self::renderChecklistPage();
                                break;
                            case 'settings' :
                                self::renderSettingsPage();
                                break;
                            default :
                                self::renderIntegrationsPage();
                                break;
                        }
                        ?>
                    </div>

                    <div class="wpgdprc-description">
                        <p><?php _e('This plugin assists website and webshop owners to comply with European privacy regulations known as GDPR. By May 25th, 2018 your site or shop has to comply.', WP_GDPR_C_SLUG); ?></p>
                        <p><?php
                            printf(
                                __('%s currently supports %s. Please visit %s for frequently asked questions and our development roadmap.', WP_GDPR_C_SLUG),
                                $pluginData['Name'],
                                implode(', ', Integration::getSupportedIntegrationsLabels()),
                                sprintf('<a target="_blank" href="%s">%s</a>', '//www.wpgdprc.com/', 'www.wpgdprc.com')
                            );
                            ?></p>
                    </div>

                    <p class="wpgdprc-disclaimer"><?php _e('Disclaimer: The creators of this plugin do not have a legal background please contact a law firm for rock solid legal advice.', WP_GDPR_C_SLUG); ?></p>
                </div>

                <div class="wpgdprc-sidebar">
                    <div class="wpgdprc-sidebar-block">
                        <h3><?php _e('Rate us', WP_GDPR_C_SLUG); ?></h3>
                        <div class="wpgdprc-stars"></div>
                        <p><?php echo sprintf(__('Did %s help you out? Please leave a 5-star review. Thank you!', WP_GDPR_C_SLUG), $pluginData['Name']); ?></p>
                        <a target="_blank" href="//wordpress.org/support/plugin/wp-gdpr-compliance/reviews/#new-post" class="button button-primary" rel="noopener noreferrer"><?php _e('Write a review', WP_GDPR_C_SLUG); ?></a>
                    </div>

                    <div class="wpgdprc-sidebar-block">
                        <h3><?php _e('Support', WP_GDPR_C_SLUG); ?></h3>
                        <p><?php echo sprintf(
                                __('Need a helping hand? Please ask for help on the %s. Be sure to mention your WordPress version and give as much additional information as possible.', WP_GDPR_C_SLUG),
                                sprintf('<a target="_blank" href="//wordpress.org/support/plugin/wp-gdpr-compliance#new-post" rel="noopener noreferrer">%s</a>', __('Support forum', WP_GDPR_C_SLUG))
                            ); ?></p>
                    </div>
                </div>

                <div class="wpgdprc-background"><?php include(WP_GDPR_C_DIR_SVG . '/inline-waves.svg.php'); ?></div>
            </div>
        </div>
        <?php
    }

    private static function renderIntegrationsPage() {
        $pluginData = Helper::getPluginData();
        $activatedPlugins = Helper::getActivatedPlugins();
        ?>
        <form method="post" action="<?php echo admin_url('options.php'); ?>" novalidate="novalidate">
            <?php settings_fields(WP_GDPR_C_SLUG . '_integrations'); ?>
            <?php if (!empty($activatedPlugins)) : ?>
                <ul class="wpgdprc-list">
                    <?php
                    foreach ($activatedPlugins as $key => $plugin) :
                        $optionName = WP_GDPR_C_PREFIX . '_integrations_' . $plugin['id'];
                        $checked = Helper::isEnabled($plugin['id']);
                        $description = (!empty($plugin['description'])) ? apply_filters('wpgdprc_the_content', $plugin['description']) : '';
                        $notices = Helper::getNotices($plugin['id']);
                        $options = Integration::getSupportedPluginOptions($plugin['id']);
                        ?>
                        <li class="wpgdprc-clearfix">
                            <?php if ($plugin['supported']) : ?>
                                <?php if (empty($notices)) : ?>
                                    <div class="wpgdprc-checkbox">
                                        <input type="checkbox" name="<?php echo $optionName; ?>" id="<?php echo $optionName; ?>" value="1" tabindex="1" data-type="save_setting" data-option="<?php echo $optionName; ?>" <?php checked(true, $checked); ?> />
                                        <label for="<?php echo $optionName; ?>"><?php echo $plugin['name']; ?></label>
                                        <span class="wpgdprc-instructions"><?php _e('Enable:', WP_GDPR_C_SLUG); ?></span>
                                        <div class="wpgdprc-switch" aria-hidden="true">
                                            <div class="wpgdprc-switch-label">
                                                <div class="wpgdprc-switch-inner"></div>
                                                <div class="wpgdprc-switch-switch"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="wpgdprc-checkbox-data" <?php if (!$checked) : ?>style="display: none;"<?php endif; ?>>
                                        <?php if (!empty($description)) : ?>
                                            <div class="wpgdprc-checklist-description">
                                                <?php echo $description; ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php echo $options; ?>
                                    </div>
                                <?php else : ?>
                                    <div class="wpgdprc-message wpgdprc-message--notice">
                                        <strong><?php echo $plugin['name']; ?></strong><br />
                                        <?php echo $notices; ?>
                                    </div>
                                <?php endif; ?>
                            <?php else : ?>
                                <div class="wpgdprc-message wpgdprc-message--error">
                                    <strong><?php echo $plugin['name']; ?></strong><br />
                                    <?php printf(__('This plugin is outdated. %s supports version %s and up.', WP_GDPR_C_SLUG), $pluginData['Name'], '<strong>' . $plugin['supported_version']  . '</strong>'); ?>
                                </div>
                            <?php endif; ?>
                        </li>
                    <?php
                    endforeach;
                    ?>
                </ul>
            <?php else : ?>
                <p><strong><?php _e('Couldn\'t find any supported plugins installed.', WP_GDPR_C_SLUG); ?></strong></p>
                <p><?php _e('The following plugins are supported as of now:', WP_GDPR_C_SLUG); ?></p>
                <ul class="ul-square">
                    <?php foreach (Integration::getSupportedPlugins() as $plugin) : ?>
                        <li><?php echo $plugin['name']; ?></li>
                    <?php endforeach; ?>
                </ul>
                <p><?php _e('More plugins will be added in the future.', WP_GDPR_C_SLUG); ?></p>
            <?php endif; ?>
            <?php submit_button(); ?>
        </form>
        <?php
    }

    /**
     * Page: Checklist
     */
    private static function renderChecklistPage() {
        ?>
        <?php if(Helper::hasMailPluginInstalled()) : ?>
            <div class="wpgdprc-message wpgdprc-message--notice">
                <?php
                printf(
                    '<p><strong>%s:</strong> %s</p>',
                    strtoupper(__('Note', WP_GDPR_C_SLUG)),
                    __('We think you might have a mail plugin installed.', WP_GDPR_C_SLUG)
                );
                ?>
                <p><?php _e('Do you know where you got your email database from? Did you ask all the people on your newsletter(s) if they consent to receiving it? GDPR requires that all of the people in your email software has given you explicit permission to mail them.', WP_GDPR_C_SLUG); ?></p>
            </div>
        <?php endif; ?>
        <p><?php _e('Below we ask you what private data you currently collect and provide you with tips to comply.', WP_GDPR_C_SLUG); ?></p>
        <ul class="wpgdprc-list">
            <?php
            foreach (Helper::getCheckList() as $id => $check) :
                $optionName = WP_GDPR_C_PREFIX . '_general_' . $id;
                $checked = Helper::isEnabled($id, 'general');
                $description = (!empty($check['description'])) ? esc_html($check['description']) : '';
                ?>
                <li class="wpgdprc-clearfix">
                    <div class="wpgdprc-checkbox">
                        <input type="checkbox" name="<?php echo $optionName; ?>" id="<?php echo $id; ?>" value="1" tabindex="1" data-type="save_setting" data-option="<?php echo $optionName; ?>" <?php checked(true, $checked); ?> />
                        <label for="<?php echo $id; ?>"><?php echo $check['label']; ?></label>
                        <div class="wpgdprc-switch wpgdprc-switch--reverse" aria-hidden="true">
                            <div class="wpgdprc-switch-label">
                                <div class="wpgdprc-switch-inner"></div>
                                <div class="wpgdprc-switch-switch"></div>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($description)) : ?>
                        <div class="wpgdprc-checkbox-data" <?php if (!$checked) : ?>style="display: none;"<?php endif; ?>>
                            <div class="wpgdprc-checklist-description">
                                <?php echo $description; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </li>
            <?php
            endforeach;
            ?>
        </ul>
        <?php
    }

    /**
     * Page: Settings
     */
    private static function renderSettingsPage() {
        $optionNamePrivacyPolicyPage = WP_GDPR_C_PREFIX . '_settings_privacy_policy_page';
        $optionNamePrivacyPolicyText = WP_GDPR_C_PREFIX . '_settings_privacy_policy_text';
        $optionNameEnableAccessRequest = WP_GDPR_C_PREFIX . '_settings_enable_access_request';
        $optionNameAccessRequestPage = WP_GDPR_C_PREFIX . '_settings_access_request_page';
        $optionNameAccessRequestFormCheckboxText = WP_GDPR_C_PREFIX . '_settings_access_request_form_checkbox_text';
        $optionNameDeleteRequestFormExplanationText = WP_GDPR_C_PREFIX . '_settings_delete_request_form_explanation_text';
        $optionNameConsentsBarExplanationText = WP_GDPR_C_PREFIX . '_settings_consents_bar_explanation_text';
        $optionNameConsentsModalTitle = WP_GDPR_C_PREFIX . '_settings_consents_modal_title';
        $optionNameConsentsModalExplanationText = WP_GDPR_C_PREFIX . '_settings_consents_modal_explanation_text';
        $privacyPolicyPage = get_option($optionNamePrivacyPolicyPage);
        $privacyPolicyText = esc_html(Integration::getPrivacyPolicyText());
        $enableAccessRequest = Helper::isEnabled('enable_access_request', 'settings');
        $accessRequestPage = get_option($optionNameAccessRequestPage);
        $accessRequestFormCheckboxText = Integration::getAccessRequestFormCheckboxText(false);
        $deleteRequestFormExplanationText = Integration::getDeleteRequestFormExplanationText(false);
        $consentsBarExplanationText = Consent::getBarExplanationText(false);
        $consentsModalTitle = Consent::getModalTitle(false);
        $consentsModalExplanationText = Consent::getModalExplanationText(false);
        ?>
        <form method="post" action="<?php echo admin_url('options.php'); ?>" novalidate="novalidate">
            <?php settings_fields(WP_GDPR_C_SLUG . '_settings'); ?>
            <p><strong><?php _e('Privacy Policy', WP_GDPR_C_SLUG); ?></strong></p>
            <div class="wpgdprc-setting">
                <label for="<?php echo $optionNamePrivacyPolicyPage; ?>"><?php _e('Privacy Policy', WP_GDPR_C_SLUG); ?></label>
                <div class="wpgdprc-options">
                    <?php
                    wp_dropdown_pages(array(
                        'post_status' => 'publish,private,draft',
                        'show_option_none' => __('Select an option', WP_GDPR_C_SLUG),
                        'name' => $optionNamePrivacyPolicyPage,
                        'selected' => $privacyPolicyPage
                    ));
                    ?>
                </div>
            </div>
            <div class="wpgdprc-setting">
                <label for="<?php echo $optionNamePrivacyPolicyText; ?>"><?php _e('Link text', WP_GDPR_C_SLUG); ?></label>
                <div class="wpgdprc-options">
                    <input type="text" name="<?php echo $optionNamePrivacyPolicyText; ?>" class="regular-text" id="<?php echo $optionNamePrivacyPolicyText; ?>" placeholder="<?php echo $privacyPolicyText; ?>" value="<?php echo $privacyPolicyText; ?>" />
                </div>
            </div>
            <p><strong><?php _e('Request User Data', WP_GDPR_C_SLUG); ?></strong></p>
            <div class="wpgdprc-information">
                <p><?php _e('Allow your site\'s visitors to request their data stored in the WordPress database (comments, WooCommerce orders etc.). Data found is send to their email address and allows them to put in an additional request to have the data anonymised.', WP_GDPR_C_SLUG); ?></p>
            </div>
            <div class="wpgdprc-setting">
                <label for="<?php echo $optionNameEnableAccessRequest; ?>"><?php _e('Activate', WP_GDPR_C_SLUG); ?></label>
                <div class="wpgdprc-options">
                    <label><input type="checkbox" name="<?php echo $optionNameEnableAccessRequest; ?>" id="<?php echo $optionNameEnableAccessRequest; ?>" value="1" tabindex="1" <?php checked(true, $enableAccessRequest); ?> /> <?php _e('Activate page', WP_GDPR_C_SLUG); ?></label>
                    <div class="wpgdprc-information">
                        <div class="wpgdprc-message wpgdprc-message--notice">
                            <?php
                            printf(
                                '<p><strong>%s:</strong> %s</p>',
                                strtoupper(__('Note', WP_GDPR_C_SLUG)),
                                sprintf(
                                    __('Enabling this will create one private page containing the necessary shortcode: %s. You can determine when and how to publish this page yourself.', WP_GDPR_C_SLUG),
                                    '<span class="wpgdprc-pre"><strong>[wpgdprc_access_request_form]</strong></span>'
                                )
                            );
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php if ($enableAccessRequest) : ?>
                <div class="wpgdprc-setting">
                    <label for="<?php echo $optionNameAccessRequestPage; ?>"><?php _e('Page', WP_GDPR_C_SLUG); ?></label>
                    <div class="wpgdprc-options">
                        <?php
                        wp_dropdown_pages(array(
                            'post_status' => 'publish,private,draft',
                            'show_option_none' => __('Select an option', WP_GDPR_C_SLUG),
                            'name' => $optionNameAccessRequestPage,
                            'selected' => $accessRequestPage
                        ));
                        ?>
                        <?php if (!empty($accessRequestPage)) : ?>
                            <div class="wpgdprc-information">
                                <?php printf('<p><a href="%s">%s</a></p>', get_edit_post_link($accessRequestPage), __('Click here to edit this page', WP_GDPR_C_SLUG)); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="wpgdprc-setting">
                    <label for="<?php echo $optionNameAccessRequestFormCheckboxText; ?>"><?php _e('Checkbox text', WP_GDPR_C_SLUG); ?></label>
                    <div class="wpgdprc-options">
                        <input type="text" name="<?php echo $optionNameAccessRequestFormCheckboxText; ?>" class="regular-text" id="<?php echo $optionNameAccessRequestFormCheckboxText; ?>" placeholder="<?php echo $accessRequestFormCheckboxText; ?>" value="<?php echo $accessRequestFormCheckboxText; ?>" />
                    </div>
                </div>
                <div class="wpgdprc-setting">
                    <label for="<?php echo $optionNameDeleteRequestFormExplanationText; ?>"><?php _e('Anonymise request explanation', WP_GDPR_C_SLUG); ?></label>
                    <div class="wpgdprc-options">
                        <textarea name="<?php echo $optionNameDeleteRequestFormExplanationText; ?>" rows="5" id="<?php echo $optionNameAccessRequestFormCheckboxText; ?>" placeholder="<?php echo $deleteRequestFormExplanationText; ?>"><?php echo $deleteRequestFormExplanationText; ?></textarea>
                        <?php echo Helper::getAllowedHTMLTagsOutput(); ?>
                    </div>
                </div>
            <?php endif; ?>
            <p><strong><?php _e('Consents', WP_GDPR_C_SLUG); ?></strong></p>
            <div class="wpgdprc-information">
                <p><?php _e('Your visitors can give permission to all of the created Consents (scripts) through a Consent bar at the bottom of their screen. There they can also access their personal settings to give or deny permission to individual Consents. Once their settings are saved the bar disappears for 365 days.', WP_GDPR_C_SLUG); ?></p>
                <div class="wpgdprc-message wpgdprc-message--notice">
                    <?php
                        printf(
                            '<p><strong>%s:</strong> %s</p>',
                            strtoupper(__('Note', WP_GDPR_C_SLUG)),
                            sprintf(
                                __('Let your visitors re-access their settings by placing a link to the modal with the shortcode %s or add the "%s" class to a menu item.', WP_GDPR_C_SLUG),
                                sprintf(
                                    '<span class="wpgdprc-pre"><strong>[wpgdprc_consents_settings_link]<em>%s</em>[/wpgdprc_consents_settings_link]</strong></span>',
                                    __('My settings', WP_GDPR_C_SLUG)
                                ),
                                '<span class="wpgdprc-pre"><strong>wpgdprc-consents-settings-link</strong></span>'
                            )
                        );
                    ?>
                </div>
            </div>
            <div class="wpgdprc-setting">
                <label for="<?php echo $optionNameConsentsBarExplanationText; ?>"><?php _e('Bar: Explanation', WP_GDPR_C_SLUG); ?></label>
                <div class="wpgdprc-options">
                    <textarea name="<?php echo $optionNameConsentsBarExplanationText; ?>" rows="2" id="<?php echo $optionNameConsentsBarExplanationText; ?>" placeholder="<?php echo $consentsBarExplanationText; ?>"><?php echo $consentsBarExplanationText; ?></textarea>
                </div>
            </div>
            <div class="wpgdprc-setting">
                <label for="<?php echo $optionNameConsentsModalTitle; ?>"><?php _e('Modal: Title', WP_GDPR_C_SLUG); ?></label>
                <div class="wpgdprc-options">
                    <input type="text" name="<?php echo $optionNameConsentsModalTitle; ?>" class="regular-text" id="<?php echo $optionNameConsentsModalTitle; ?>" placeholder="<?php echo $consentsModalTitle; ?>" value="<?php echo $consentsModalTitle; ?>" />
                </div>
            </div>
            <div class="wpgdprc-setting">
                <label for="<?php echo $optionNameConsentsModalExplanationText; ?>"><?php _e('Modal: Explanation', WP_GDPR_C_SLUG); ?></label>
                <div class="wpgdprc-options">
                    <textarea name="<?php echo $optionNameConsentsModalExplanationText; ?>" rows="5" id="<?php echo $optionNameConsentsModalExplanationText; ?>" placeholder="<?php echo $consentsModalExplanationText; ?>"><?php echo $consentsModalExplanationText; ?></textarea>
                    <?php echo Helper::getAllowedHTMLTagsOutput(); ?>
                </div>
            </div>
            <?php submit_button(); ?>
        </form>
        <?php
    }

    /**
     * @param int $consentId
     */
    private static function renderManageConsentPage($consentId = 0) {
        wp_enqueue_style('wpgdprc.admin.codemirror.css');
        wp_enqueue_script('wpgdprc.admin.codemirror.additional.js');
        $consent = new Consent($consentId);
        if (isset($_POST['submit']) && check_admin_referer('consent_create_or_update', 'consent_nonce')) {
            $active = (isset($_POST['active'])) ? 1 : 0;
            $title = (isset($_POST['title'])) ? esc_html($_POST['title']) : $consent->getTitle();
            $description = (isset($_POST['description'])) ? stripslashes(esc_html($_POST['description'])) : $consent->getDescription();
            $snippet = (isset($_POST['snippet'])) ? stripslashes($_POST['snippet']) : $consent->getSnippet();
            $wrap = (isset($_POST['wrap']) && array_key_exists($_POST['wrap'], Consent::getPossibleCodeWraps())) ? esc_html($_POST['wrap']) : $consent->getWrap();
            $placement = (isset($_POST['placement']) && array_key_exists($_POST['placement'], Consent::getPossiblePlacements())) ? esc_html($_POST['placement']) : $consent->getPlacement();
            $required = (isset($_POST['required'])) ? 1 : 0;
            $consent->setTitle($title);
            $consent->setDescription($description);
            $consent->setSnippet($snippet);
            $consent->setWrap($wrap);
            $consent->setPlacement($placement);
            $consent->setRequired($required);
            $consent->setActive($active);
            $id = $consent->save();
            if (!empty($id)) {
                Helper::showAdminNotice('wpgdprc-consent-updated');
            }
        }
        ?>
        <form method="post" action="">
            <?php wp_nonce_field('consent_create_or_update', 'consent_nonce'); ?>
            <p><strong><?php _e('Add New Consent', WP_GDPR_C_SLUG); ?></strong></p>
            <div class="wpgdprc-setting">
                <label for="wpgdprc_active"><?php _e('Active', WP_GDPR_C_SLUG); ?></label>
                <div class="wpgdprc-options">
                    <label><input type="checkbox" name="active" id="wpgdprc_active" value="1" <?php checked(1, $consent->getActive()); ?> /> <?php _e('Yes', WP_GDPR_C_SLUG); ?></label>
                </div>
            </div>
            <div class="wpgdprc-setting">
                <label for="wpgdprc_title"><?php _e('Title', WP_GDPR_C_SLUG); ?></label>
                <div class="wpgdprc-options">
                    <input type="text" name="title" class="regular-text" id="wpgdprc_title" value="<?php echo $consent->getTitle(); ?>" required="required" />
                    <div class="wpgdprc-information">
                        <p><?php _e('e.g. "Google Analytics" or "Advertising"', WP_GDPR_C_SLUG); ?></p>
                    </div>
                </div>
            </div>
            <div class="wpgdprc-setting">
                <label for="wpgdprc_description"><?php _e('Description', WP_GDPR_C_SLUG); ?></label>
                <div class="wpgdprc-options">
                    <textarea name="description" id="wpgdprc_description" rows="5" autocomplete="false" autocorrect="false" autocapitalize="false" spellcheck="false"><?php echo $consent->getDescription(); ?></textarea>
                    <div class="wpgdprc-information">
                        <p><?php _e('Describe your consent script as thoroughly as possible.', WP_GDPR_C_SLUG); ?></p>
                    </div>
                </div>
            </div>
            <div class="wpgdprc-setting">
                <label for="wpgdprc_snippet"><?php _e('Code Snippet', WP_GDPR_C_SLUG); ?></label>
                <div class="wpgdprc-options">
                    <textarea name="snippet" id="wpgdprc_snippet" rows="10" autocomplete="false" autocorrect="false" autocapitalize="false" spellcheck="false"><?php echo htmlspecialchars($consent->getSnippet(), ENT_QUOTES, get_option('blog_charset')); ?></textarea>
                    <div class="wpgdprc-information">
                        <p><?php _e('Code snippets for Google Analytics, Facebook Pixel, etc.', WP_GDPR_C_SLUG); ?></p>
                    </div>
                </div>
            </div>
            <div class="wpgdprc-setting">
                <label for="wpgdprc_code_wrap"><?php _e('Code Wrap', WP_GDPR_C_SLUG); ?></label>
                <div class="wpgdprc-options">
                    <select name="wrap" id="wpgdprc_code_wrap">
                        <?php
                        foreach (Consent::getPossibleCodeWraps() as $value => $label) {
                            printf(
                                '<option value="%s" %s>%s</option>',
                                $value,
                                selected($value, $consent->getWrap(), false),
                                $label
                            );
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="wpgdprc-setting">
                <label for="wpgdprc_placement"><?php _e('Placement', WP_GDPR_C_SLUG); ?></label>
                <div class="wpgdprc-options">
                    <select name="placement" id="wpgdprc_placement">
                        <?php
                        foreach (Consent::getPossiblePlacements() as $value => $label) {
                            printf(
                                '<option value="%s" %s>%s</option>',
                                $value,
                                selected($value, $consent->getPlacement(), false),
                                $label
                            );
                        }
                        ?>
                    </select>
                    <div class="wpgdprc-information">
                        <?php
                        printf(
                            '<strong>%s:</strong> %s<br />',
                            strtoupper(__('Head', WP_GDPR_C_SLUG)),
                            __('Snippet will be added to the HEAD section.', WP_GDPR_C_SLUG)
                        );
                        printf(
                            '<strong>%s:</strong> %s',
                            strtoupper(__('Footer', WP_GDPR_C_SLUG)),
                            __('Snippet will be added to the FOOTER section.', WP_GDPR_C_SLUG)
                        );
                        ?>
                    </div>
                </div>
            </div>
            <div class="wpgdprc-setting">
                <label for="wpgdprc_active"><?php _e('Required', WP_GDPR_C_SLUG); ?></label>
                <div class="wpgdprc-options">
                    <label><input type="checkbox" name="required" id="wpgdprc-required" value="1" <?php checked(1, $consent->getRequired()); ?> /> <?php _e('Yes', WP_GDPR_C_SLUG); ?></label>
                    <div class="wpgdprc-information">
                        <p><?php _e('Ticking this checkbox means this Consent will always be triggered so users cannot opt-in or opt-out.', WP_GDPR_C_SLUG); ?></p>
                    </div>
                </div>
            </div>
            <p class="submit">
                <?php submit_button((!empty($consentId) ? __('Update', WP_GDPR_C_SLUG) : __('Add', WP_GDPR_C_SLUG)), 'primary', 'submit', false); ?>
                <a class="button button-secondary" href="<?php echo Helper::getPluginAdminUrl('consents'); ?>"><?php _e('Back to overview', WP_GDPR_C_SLUG); ?></a>
            </p>
        </form>
        <?php
    }

    private static function renderConsentsPage() {
        $paged = (isset($_REQUEST['paged'])) ? absint($_REQUEST['paged']) : 1;
        $limit = 20;
        $offset = ($paged - 1) * $limit;
        $total = Consent::getInstance()->getTotal();
        $numberOfPages = ceil($total / $limit);
        $consents = Consent::getInstance()->getList(array(), $limit, $offset);
        ?>
        <div class="wpgdprc-message wpgdprc-message--notice">
            <p><?php _e('Ask your visitors for permission to enable certain scripts for tracking or advertising purposes. Add a Consent for each type of script you are requesting permission for. Scripts will only be activated when permission is given.', WP_GDPR_C_SLUG); ?></p>
            <p><a class="button button-primary" href="<?php echo Helper::getPluginAdminUrl('consents', array('action' => 'create')); ?>"><?php _ex('Add New', 'consent', WP_GDPR_C_SLUG); ?></a></p>
        </div>
        <?php if (!empty($consents)) : ?>
            <table class="wpgdprc-table">
                <thead>
                <tr>
                    <th scope="col" width="10%"><?php _e('Consent', WP_GDPR_C_SLUG); ?></th>
                    <th scope="col" width="16%"><?php _e('Title', WP_GDPR_C_SLUG); ?></th>
                    <th scope="col" width="12%"><?php _e('Required', WP_GDPR_C_SLUG); ?></th>
                    <th scope="col" width="20%"><?php _e('Modified at', WP_GDPR_C_SLUG); ?></th>
                    <th scope="col" width="20%"><?php _e('Created at', WP_GDPR_C_SLUG); ?></th>
                    <th scope="col" width="14%"><?php _e('Action', WP_GDPR_C_SLUG); ?></th>
                    <th scope="col" width="8%"><?php _e('Active', WP_GDPR_C_SLUG); ?></th>
                </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($consents as $consent) :
                        $title = $consent->getTitle();
                        ?>
                        <tr class="wpgdprc-table__row <?php echo (!$consent->getActive()) ? 'wpgdprc-table__row--expired' : ''; ?>">
                            <td><?php printf('#%d', $consent->getId()); ?></td>
                            <td>
                                <?php
                                    printf(
                                        '<a href="%s">%s</a>',
                                        Consent::getActionUrl($consent->getId()),
                                        ((!empty($title)) ? $title : __('(no title)', WP_GDPR_C_SLUG))
                                    );
                                ?>
                            </td>
                            <td><?php echo ($consent->getRequired()) ? __('Yes', WP_GDPR_C_SLUG) : __('No', WP_GDPR_C_SLUG); ?></td>
                            <td><?php echo $consent->getDateModified(); ?></td>
                            <td><?php echo $consent->getDateCreated(); ?></td>
                            <td>
                                <?php
                                    printf(
                                        '%s | %s',
                                        sprintf(
                                            '<a href="%s">%s</a>',
                                            Consent::getActionUrl($consent->getId()),
                                            __('Edit', WP_GDPR_C_SLUG)
                                        ),
                                        sprintf(
                                            '<a href="%s">%s</a>',
                                            Consent::getActionUrl($consent->getId(), 'delete'),
                                            __('Remove', WP_GDPR_C_SLUG)
                                        )
                                    );
                                ?>
                            </td>
                            <td><?php echo ($consent->getActive()) ? __('Yes', WP_GDPR_C_SLUG) : __('No', WP_GDPR_C_SLUG); ?></td>
                        </tr>
                        <?php
                    endforeach;
                    ?>
                </tbody>
            </table>
            <div class="wpgdprc-pagination">
                <?php
                echo paginate_links(array(
                    'base' => str_replace(
                        999999999,
                        '%#%',
                        Helper::getPluginAdminUrl('consents', array('paged' => 999999999))
                    ),
                    'format' => '?paged=%#%',
                    'current' => max(1, $paged),
                    'total' => $numberOfPages,
                    'prev_text' => '&lsaquo;',
                    'next_text' => '&rsaquo;',
                    'before_page_number' => '<span>',
                    'after_page_number' => '</span>'
                ));
                printf('<span class="wpgdprc-pagination__results">%s</span>', sprintf(__('%d of %d results found', WP_GDPR_C_SLUG), count($consents), $total));
                ?>
            </div>
        <?php else : ?>
            <p><strong><?php _e('No consents found.', WP_GDPR_C_SLUG); ?></strong></p>
        <?php endif; ?>
        <?php
    }

    /**
     * @param int $requestId
     */
    private static function renderManageRequestPage($requestId = 0) {
        $accessRequest = new AccessRequest($requestId);
        $filters = array(
            'access_request_id' => array(
                'value' => $accessRequest->getId(),
            ),
        );
        $paged = (isset($_REQUEST['paged'])) ? absint($_REQUEST['paged']) : 1;
        $limit = 20;
        $offset = ($paged - 1) * $limit;
        $total = DeleteRequest::getInstance()->getTotal($filters);
        $numberOfPages = ceil($total / $limit);
        $requests = DeleteRequest::getInstance()->getList($filters, $limit, $offset);
        if (!empty($requests)) :
            ?>
            <div class="wpgdprc-message wpgdprc-message--notice">
                <p><?php _e('Anonymise a request by ticking the checkbox and clicking on the green anonymise button below.', WP_GDPR_C_SLUG); ?></p>
                <p>
                    <?php printf('<strong>%s:</strong> %s', __('WordPress Users', WP_GDPR_C_SLUG), 'Anonymises first and last name, display name, nickname and email address.', WP_GDPR_C_SLUG); ?><br />
                    <?php printf('<strong>%s:</strong> %s', __('WordPress Comments', WP_GDPR_C_SLUG), 'Anonymises author name, email address and IP address.', WP_GDPR_C_SLUG); ?><br />
                    <?php printf('<strong>%s:</strong> %s', __('WooCommerce', WP_GDPR_C_SLUG), 'Anonymises billing and shipping details per order.', WP_GDPR_C_SLUG); ?>
                </p>
            </div>

            <form class="wpgdprc-form wpgdprc-form--process-delete-requests" method="POST" novalidate="novalidate">
                <div class="wpgdprc-message" style="display: none;"></div>
                <table class="wpgdprc-table">
                    <thead>
                    <tr>
                        <th scope="col" width="10%"><?php _e('Request', WP_GDPR_C_SLUG); ?></th>
                        <th scope="col" width="22%"><?php _e('Type', WP_GDPR_C_SLUG); ?></th>
                        <th scope="col" width="18%"><?php _e('IP Address', WP_GDPR_C_SLUG); ?></th>
                        <th scope="col" width="22%"><?php _e('Date', WP_GDPR_C_SLUG); ?></th>
                        <th scope="col" width="12%"><?php _e('Processed', WP_GDPR_C_SLUG); ?></th>
                        <th scope="col" width="10%"><?php _e('Action', WP_GDPR_C_SLUG); ?></th>
                        <th scope="col" width="6%"><input type="checkbox" class="wpgdprc-select-all" /></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    /** @var DeleteRequest $request */
                    foreach ($requests as $request) :
                        ?>
                        <tr data-id="<?php echo $request->getId(); ?>">
                            <td><?php printf('#%d', $request->getId()); ?></td>
                            <td><?php echo $request->getNiceTypeLabel(); ?></td>
                            <td><?php echo $request->getIpAddress(); ?></td>
                            <td><?php echo $request->getDateCreated(); ?></td>
                            <td><span class="dashicons dashicons-<?php echo ($request->getProcessed()) ? 'yes' : 'no'; ?>"></span></td>
                            <td><?php printf('<a target="_blank" href="%s">%s</a>', $request->getManageUrl(), __('View', WP_GDPR_C_SLUG)); ?></td>
                            <td>
                                <?php if (!$request->getProcessed()) : ?>
                                    <input type="checkbox" class="wpgdprc-checkbox" value="<?php echo $request->getId(); ?>" />
                                <?php else : ?>
                                    &nbsp;
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php
                    endforeach;
                    ?>
                    </tbody>
                </table>
                <?php submit_button(__('Anonymise selected request(s)', WP_GDPR_C_SLUG), 'primary wpgdprc-remove'); ?>
            </form>

            <div class="wpgdprc-pagination">
                <?php
                    echo paginate_links(array(
                        'base' => str_replace(
                            999999999,
                            '%#%',
                            Helper::getPluginAdminUrl('requests', array('paged' => 999999999))
                        ),
                        'format' => '?paged=%#%',
                        'current' => max(1, $paged),
                        'total' => $numberOfPages,
                        'prev_text' => '&lsaquo;',
                        'next_text' => '&rsaquo;',
                        'before_page_number' => '<span>',
                        'after_page_number' => '</span>'
                    ));
                    printf('<span class="wpgdprc-pagination__results">%s</span>', sprintf(__('%d of %d results found', WP_GDPR_C_SLUG), count($requests), $total));
                ?>
            </div>
            <?php
        else :
            ?>
            <p><strong><?php _e('No requests found.', WP_GDPR_C_SLUG); ?></strong></p>
            <?php
        endif;
        ?>
        <?php
    }

    /**
     * Page: Requests
     */
    private static function renderRequestsPage() {
        $paged = (isset($_REQUEST['paged'])) ? absint($_REQUEST['paged']) : 1;
        $limit = 20;
        $offset = ($paged - 1) * $limit;
        $total = AccessRequest::getInstance()->getTotal();
        $numberOfPages = ceil($total / $limit);
        $requests = AccessRequest::getInstance()->getList(array(), $limit, $offset);
        if (!empty($requests)) :
            ?>
            <table class="wpgdprc-table">
                <thead>
                <tr>
                    <th scope="col" width="10%"><?php _e('ID', WP_GDPR_C_SLUG); ?></th>
                    <th scope="col" width="20%"><?php _e('Requests to Process', WP_GDPR_C_SLUG); ?></th>
                    <th scope="col" width="22%"><?php _e('Email Address', WP_GDPR_C_SLUG); ?></th>
                    <th scope="col" width="18%"><?php _e('IP Address', WP_GDPR_C_SLUG); ?></th>
                    <th scope="col" width="22%"><?php _e('Date', WP_GDPR_C_SLUG); ?></th>
                    <th scope="col" width="8%"><?php _e('Status', WP_GDPR_C_SLUG); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                /** @var AccessRequest $request */
                foreach ($requests as $request) :
                    $amountOfDeleteRequests = DeleteRequest::getInstance()->getAmountByAccessRequestId($request->getId());
                    ?>
                    <tr class="wpgdprc-table__row <?php echo ($request->getExpired()) ? 'wpgdprc-table__row--expired' : ''; ?>">
                        <td><?php printf('#%d', $request->getId()); ?></td>
                        <td>
                            <?php printf('%d', $amountOfDeleteRequests); ?>
                            <?php
                            if ($amountOfDeleteRequests > 0) {
                                printf(
                                    '<a href="%s">%s</a>',
                                    Helper::getPluginAdminUrl('requests', array('id' => $request->getId())),
                                    __('Manage', WP_GDPR_C_SLUG)
                                );
                            }
                            ?>
                        </td>
                        <td><?php echo $request->getEmailAddress(); ?></td>
                        <td><?php echo $request->getIpAddress(); ?></td>
                        <td><?php echo $request->getDateCreated(); ?></td>
                        <td><?php echo ($request->getExpired()) ? __('Expired', WP_GDPR_C_SLUG) : __('Active', WP_GDPR_C_SLUG); ?></td>
                    </tr>
                    <?php
                endforeach;
                ?>
                </tbody>
            </table>
            <div class="wpgdprc-pagination">
                <?php
                echo paginate_links(array(
                    'base' => str_replace(
                        999999999,
                        '%#%',
                        Helper::getPluginAdminUrl('requests', array('paged' => 999999999))
                    ),
                    'format' => '?paged=%#%',
                    'current' => max(1, $paged),
                    'total' => $numberOfPages,
                    'prev_text' => '&lsaquo;',
                    'next_text' => '&rsaquo;',
                    'before_page_number' => '<span>',
                    'after_page_number' => '</span>'
                ));
                printf('<span class="wpgdprc-pagination__results">%s</span>', sprintf(__('%d of %d results found', WP_GDPR_C_SLUG), count($requests), $total));
                ?>
            </div>
            <?php
        else :
            ?>
            <p><strong><?php _e('No requests found.', WP_GDPR_C_SLUG); ?></strong></p>
            <?php
        endif;
    }

    /**
     * @return null|Page
     */
    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}