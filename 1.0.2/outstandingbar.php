<?php

namespace Contrast;

require_once 'OutstandingBarOptions.php';

/**
 * Plugin Name: OutstandingBar
 * Plugin URI: http://outstandingbar.com/
 * Description: Tired of countless pop-ups? Outstanding Bar is a simple Wordpress plugin that integrates with Mailchimp. Simply set up your settings once and collect emails in a way that your users won't find offensive.
 * Version: 1.0.2
 * Author: CONTRAST
 * Author URI: http://wearecontrast.com/
 * License: Copyright 2015 Mike Gatward &amp; Fred Rivett (mike@wearecontrast.com, fred@wearecontrast.com)
 */
class OutstandingBar {

    /**
     * Constants
     */
    const name = 'OutstandingBar';

    /**
     * Constructor
     */
    public function __construct() {
        register_activation_hook(__FILE__, array(&$this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));

        //Hook up to the init action
        add_action('init', array($this, 'init'));
        add_action('wp_head', array($this, '_wpHead'));
        add_action('wp_footer', array($this, '_wpFooter'));
        add_action('wp_ajax_outstandingbar_signup', array($this, 'outstandingbar_signup'));
        add_action('wp_ajax_nopriv_outstandingbar_signup', array($this, 'outstandingbar_signup'));
        add_action('load-settings_page_outstanding-bar', array($this, 'settingsJavascriptCss'));
    }

    public function activate() {
        if (!current_user_can('activate_plugins')) {
            return;
        }
        $OBOptions = new \Contrast\OutstandingBarOptions();
        update_option($OBOptions->getOptionName(), array($OBOptions->getFieldName('apiKey') => ''));
    }

    public function deactivate() {
        if (!current_user_can('activate_plugins')) {
            return;
        }
        $OBOptions = new \Contrast\OutstandingBarOptions();
        delete_option($OBOptions->getOptionName());
    }

    /**
     * Runs when the plugin is initialized
     */
    public function init() {
        $this->_registerStylesheet();
        $this->_registerScript();
        require_once $this->_getFullFilePath('options.php');
    }

    private function _registerStylesheet() {
        wp_register_style('outstandingbar-css', plugins_url('css/outstandingbar.css', __FILE__));
        wp_register_style('outstandingbar-css-ie9', plugins_url('css/ie9.css', __FILE__));
        $GLOBALS['wp_styles']->add_data( 'outstandingbar-css-ie9', 'conditional', 'lte IE 9' );
    }

    private function _registerScript() {
        if(!is_admin()){
            wp_enqueue_script(
                'outstandingbar-js'
                , plugins_url('/js/outstandingbar.js', __FILE__)
                , array('jquery')
            );
        }
    }

    private function _getFullFilePath($path) {
        return __DIR__ . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $path);
    }

    public function _wpHead() {
        if ($this->_isActive()) {
            wp_enqueue_style('outstandingbar-css');
            wp_enqueue_style('outstandingbar-css-ie9');
            $this->_outputActiveJavascript();
            $this->_outputCustomColours();
        } else {
            $this->_outputNotActiveJavascript();
        }
    }

    private function _outputActiveJavascript() {
        ?>
        <script>
            var outstandingBar_isActive = true;
            var outstandingBar_ajaxUrl = '<?php echo admin_url('admin-ajax.php'); ?>';
            var outstandingBar_nonce = '<?php echo wp_create_nonce('outstandingbar-security'); ?>';
            var outstandingBar_displayStyle = '<?php echo $this->_getDisplayStyle(); ?>';
        </script>
        <?php
    }
    
    private function _getDisplayStyle(){
        $OBOptions = new \Contrast\OutstandingBarOptions();
        return $OBOptions->getOption('displayStyle', 'Always');
    }
    
    private function _outputCustomColours(){
        ?>
        <style>
            .outstanding-bar, .ob-collapsed .outstanding-bar { background-color: <?php echo $this->_getMainColour(); ?> !important; }
            .outstanding-bar p, .outstanding-bar a.ob-hide { color: <?php echo $this->_getTextColour(); ?> !important; }
            .outstanding-bar .ob-submit-btn { 
                background-color: <?php echo $this->_getTextColour(); ?> !important;
                color: <?php echo $this->_getMainColour(); ?> !important;
            }
            .outstanding-bar .ob-submit-btn:hover, .outstanding-bar .ob-submit-btn:focus { 
                background-color: <?php echo $this->_getAccentColour(); ?> !important;
                color: <?php echo $this->_getMainColour(); ?> !important;
            }
        </style>
        <?php
    }
    
    private function _getMainColour(){
        $OBOptions = new \Contrast\OutstandingBarOptions();
        return $OBOptions->getOption('mainColour', '#333333');
    }
    
    private function _getTextColour(){
        $OBOptions = new \Contrast\OutstandingBarOptions();
        return $OBOptions->getOption('textColour', '#ffffff');
    }
    
    private function _getAccentColour(){
        $OBOptions = new \Contrast\OutstandingBarOptions();
        return $OBOptions->getOption('accentColour', '#fff000');
    }
    
    private function _outputNotActiveJavascript() {
        ?>
        <script>
            var outstandingBar_isActive = false;
        </script>
        <?php
    }

    public function _wpFooter() {
        if ($this->_isActive()) {
            wp_enqueue_script('outstandingbar-js');
            $this->_outputHtml();
        }
    }

    private function _isActive() {
        $OBOptions = new \Contrast\OutstandingBarOptions();
        return ($OBOptions->getOption('isActive', '0') === '1');
    }

    private function _outputHtml() {
        $OBOptions = new \Contrast\OutstandingBarOptions();
        ?>
        <section class="outstanding-bar ob-bottom ob-offscreen">
            <p class="ob-text"><?php echo $OBOptions->getOption('mainText'); ?></p>
            <input type="email" value="" name="email" id="ob-mc-email" class="ob-email" placeholder="Email address&hellip;">
            <button id="ob-mc-signup" class="ob-submit-btn"><?php echo $OBOptions->getOption('signupButton'); ?></button>
            <a href="javascript:void(0)" class="ob-hide-md"><?php echo $OBOptions->getOption('hideButton'); ?></a>
            <a href="javascript:void(0)" class="ob-hide-sm">
              <svg version="1.1" xmlns="http://www.w3.org/2000/svg"
                   xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                   width="14px" height="14px" viewBox="0 0 14 14"
                   enable-background="new 0 0 14 14" xml:space="preserve">
                <g>
                  <polyline fill="none" stroke="#FFFFFF" stroke-width="1.4897"
                            points="10.57,10.86 7,7.292 10.57,3.722"/>
                  <polyline fill="none" stroke="#FFFFFF" stroke-width="1.4897"
                            points="3.431,10.86 7,7.292 3.431,3.722"/>
                </g>
              </svg>
            </a>
        </section>
        <?php
    }

    public function outstandingbar_signup() {
        check_ajax_referer('outstandingbar-security', 'security');
        $email = $this->_getEmail();
        if ($email !== false) {
            $return = $this->_signupEmail($email);
        } else {
            $return = '1';
        }
        echo json_encode($return);
        wp_die();
    }

    private function _getEmail() {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    private function _signupEmail($email) {
        $OBOptions = new \Contrast\OutstandingBarOptions();
        $mc = new \Drewm\MailChimp($OBOptions->getOption('apiKey'));
        return $mc->call('lists/subscribe', array(
                    'id' => $OBOptions->getOption('list'),
                    'email' => array('email' => $email)
        ));
    }
    
    public function settingsJavascriptCss(){
        wp_enqueue_script('outstandingbar-admin-js'
                , plugins_url('/js/outstandingbar-admin.js', __FILE__)
                , array('jquery', 'wp-color-picker'));
        wp_enqueue_style( 'wp-color-picker' );
    }

}

$outstandingbar = new \Contrast\OutstandingBar();
