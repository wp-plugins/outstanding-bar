<?php
require_once 'Mailchimp.php';
require_once 'OutstandingBarOptions.php';

add_action('admin_menu', 'outstandingBar_add_admin_menu');
add_action('admin_init', 'outstandingBar_settings_init');

function outstandingBar_add_admin_menu() {
    add_options_page('Outstanding Bar', 'Outstanding Bar', 'manage_options', 'outstanding-bar', 'outstandingBar_options_page');
}

function outstandingBar_settings_init() {
    $OBOptions = new \Contrast\OutstandingBarOptions();
    register_setting($OBOptions->getOptionGroup(), $OBOptions->getOptionName());
    outstandingBar_addMailchimpSection($OBOptions);
    outstandingBar_addDisplaySection($OBOptions);
}

function outstandingBar_addMailchimpSection($OBOptions) {
    $fields = array(
        array('name' => 'apiKey', 'text' => 'Mailchimp API Key')
        , array('name' => 'list', 'text' => 'Mailchimp List')
        , array('name' => 'doubleOptIn', 'text' => 'Double Opt-in?')
    );
    $OBOptions->addSettingsSection('mailchimp', 'MailChimp Settings', $fields);
}

function outstandingBar_addDisplaySection($OBOptions) {
    $fields = array(
        array('name' => 'isActive', 'text' => 'Show Outstanding Bar')
        , array('name' => 'mainText', 'text' => 'Main Text')
        , array('name' => 'signupButton', 'text' => 'Signup Button')
        , array('name' => 'hideButton', 'text' => 'Hide Button')
        , array('name' => 'successText', 'text' => 'Thank you Message')
        , array('name' => 'emailPlaceholder', 'text' => 'Email Placeholder')
        , array('name' => 'mainColour', 'text' => 'Main Colour')
        , array('name' => 'accentColour', 'text' => 'Accent Colour')
        , array('name' => 'textColour', 'text' => 'Text Colour')
        , array('name' => 'displayStyle', 'text' => 'Display Style')
    );
    $OBOptions->addSettingsSection('display', 'Display Settings', $fields);
}

function outstandingBar_apiKey_render() {
    $OBOptions = new \Contrast\OutstandingBarOptions();
    $OBOptions->outputFormElement('apiKey', 'text');
}

function outstandingBar_list_render() {
    $OBOptions = new \Contrast\OutstandingBarOptions();
    $options = outstandingBar_getMailChimpLists();
    ?>
    <select id="list" name="<?php echo $OBOptions->getFormElementName('list'); ?>">
        <?php foreach ($options as $option) { ?>
            <option value="<?php echo $option['value']; ?>" <?php selected($OBOptions->getOption('list'), $option['value']); ?>><?php echo $option['text']; ?></option>
        <?php } ?>
    </select>
    <?php
}

function outstandingBar_getMailChimpLists(){
    $OBOptions = new \Contrast\OutstandingBarOptions();
    $options = array(
        array('value' => '', 'text' => 'Please Select')
    );
    
    $apiKey = $OBOptions->getOption('apiKey');
    if (outstandingBar_isValidApiKey($apiKey)) {
        $mc = new \Drewm\MailChimp($apiKey);
        $lists = $mc->call('lists/list');
        if ($lists['total'] > 0) {
            foreach ($lists['data'] as $list) {
                $options[] = array('value' => $list['id'], 'text' => $list['name'] . ' (' . $list['stats']['member_count'] . ')');
            }
        }
    } else {
        $options = array(array('value' => '', 'text' => 'Invalid API Key'));
    }
    return $options;
}

function outstandingBar_isValidApiKey($apiKey){
     if($apiKey !== '' && preg_match('/^[0-9a-f]{32}-us([0-9]{1,2})$/', $apiKey) === 1){
        $mc = new \Drewm\MailChimp($apiKey);
        $ping = $mc->call('helper/ping');
        if(array_key_exists('msg', $ping) && $ping['msg'] === "Everything's Chimpy!"){
            return true;
        }
     }
     return false;
}

function outstandingBar_doubleOptIn_render() {
    $options = array(
        array('value' => 1, 'text' => 'Yes')
        ,array('value' => 0, 'text' => 'No')
    );
    $OBOptions = new \Contrast\OutstandingBarOptions();
    $OBOptions->outputFormElement('doubleOptIn', 'select', array('options' => $options, 'default' => 1));
}

function outstandingBar_isActive_render() {
    $OBOptions = new \Contrast\OutstandingBarOptions();
    $OBOptions->outputFormElement('isActive', 'checkbox');
}

function outstandingBar_mainText_render() {
    $OBOptions = new \Contrast\OutstandingBarOptions();
    $OBOptions->outputFormElement('mainText', 'text', array('default' => 'Enter your email to signup to our newsletter'));
}

function outstandingBar_signupButton_render() {
    $OBOptions = new \Contrast\OutstandingBarOptions();
    $OBOptions->outputFormElement('signupButton', 'text', array('default' => 'sign up'));
}

function outstandingBar_hideButton_render() {
    $OBOptions = new \Contrast\OutstandingBarOptions();
    $OBOptions->outputFormElement('hideButton', 'text', array('default' => 'hide'));
}

function outstandingBar_successText_render() {
    $OBOptions = new \Contrast\OutstandingBarOptions();
    $OBOptions->outputFormElement('successText', 'text', array('default' => 'Thanks for signing up'));
}

function outstandingBar_emailPlaceholder_render() {
    $OBOptions = new \Contrast\OutstandingBarOptions();
    $OBOptions->outputFormElement('emailPlaceholder', 'text', array('default' => 'Email Address...'));
}

function outstandingBar_mainColour_render() {
    $OBOptions = new \Contrast\OutstandingBarOptions();
    $OBOptions->outputFormElement('mainColour', 'text', array('default' => '#333333'));
}

function outstandingBar_accentColour_render() {
    $OBOptions = new \Contrast\OutstandingBarOptions();
    $OBOptions->outputFormElement('accentColour', 'text', array('default' => '#fff000'));
}

function outstandingBar_textColour_render() {
    $OBOptions = new \Contrast\OutstandingBarOptions();
    $OBOptions->outputFormElement('textColour', 'text', array('default' => '#ffffff'));
}

function outstandingBar_displayStyle_render() {
    $options = array(
        array('value' => 'Always', 'text' => 'Always')
        ,array('value' => 'OnScrollUp', 'text' => 'On Scroll Up')
        ,array('value' => '50Percent', 'text' => 'Scroll >50%')
    );
    $OBOptions = new \Contrast\OutstandingBarOptions();
    $OBOptions->outputFormElement('displayStyle', 'select', array('options' => $options));
}

function outstandingBar_options_page() {
    ?>
    <form action='options.php' method='post'>
        <h2>Outstanding Bar Settings</h2>

        <?php
        settings_fields('outstandingBarPage');
        do_settings_sections('outstandingBarPage');
        submit_button();
        ?>
    </form>
    <?php
}
?>