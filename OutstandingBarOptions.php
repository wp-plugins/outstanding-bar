<?php

namespace Contrast;

/**
 * Description of OutstandingBarOptions
 *
 * @author Mike
 */
class OutstandingBarOptions {

    private $optionGroup = 'outstandingBarPage';
    private $optionName = 'outstandingBar_settings';
    private $fieldPrefix = 'outstandingBar_';
    private $renderSuffix = '_render';

    public function getOptionGroup() {
        return $this->optionGroup;
    }

    public function getOptionName() {
        return $this->optionName;
    }

    public function getSectionName($name) {
        return $this->optionGroup . '_' . $name . 'Section';
    }

    public function getFieldName($name) {
        return $this->fieldPrefix . $name;
    }

    public function getRenderName($name) {
        return $this->fieldPrefix . $name . $this->renderSuffix;
    }

    public function getFormElementName($name) {
        return $this->optionName . '[' . $this->getFieldName($name) . ']';
    }

    public function getOption($name, $default='') {
        $options = get_option($this->optionName);
        $optionName = $this->getFieldName($name);
        return (array_key_exists($optionName, $options)) ? $options[$optionName] : $default;
    }

    public function addSettingsSection($sectionName, $displayText, $fields) {
        add_settings_section(
                $this->getSectionName($sectionName)
                , $this->_i18n($displayText)
                , ''
                , $this->getOptionGroup()
        );
        foreach($fields as $field){
            $this->addSettingsField($field['name'], $field['text'], $sectionName);
        }
    }

    private function _i18n($text, $domain = 'wordpress') {
        return __($text, $domain);
    }

    public function addSettingsField($fieldName, $displayText, $sectionName) {
        add_settings_field(
                $this->getFieldName($fieldName)
                , $this->_i18n($displayText)
                , $this->getRenderName($fieldName)
                , $this->getOptionGroup()
                , $this->getSectionName($sectionName)
        );
    }
    
    public function outputFormElement($name, $type, $options=array()){
        switch($type){
            case 'text': $this->_outputTextElement($name, $options); break;
            case 'checkbox': $this->_outputCheckboxElement($name, $options); break;
            case 'select': $this->_outputSelectElement($name, $options); break;
        }
    }
    
    private function _outputTextElement($name, $options){
        ?>
        <input type="text" 
               id="<?php echo $name; ?>"
               name="<?php echo $this->getFormElementName($name); ?>" 
               value="<?php echo $this->getOption($name, $this->_getDefaultFromOptions($options)); ?>">
        <?php
    }

    private function _outputCheckboxElement($name, $options) {
        ?>
        <input type="checkbox"
               id="<?php echo $name; ?>"
               name="<?php echo $this->getFormElementName($name); ?>"
               <?php checked($this->getOption($name, $this->_getDefaultFromOptions($options, '0')), 1); ?> 
               value='1'>
        <?php
    }
    
    private function _outputSelectElement($name, $options){
        ?>
        <select id="<?php echo $name; ?>" name="<?php echo $this->getFormElementName($name); ?>">
            <?php foreach($options['options'] as $option){ ?>
                <option value="<?php echo $option['value']; ?>" 
                    <?php selected($option['value'], $this->getOption($name, $this->_getDefaultFromOptions($options, ''))); ?>>
                        <?php echo $option['text']; ?>
                </option>
            <?php } ?>
        </select>
        <?php
    }
    
    private function _getDefaultFromOptions($options, $default=''){
        return (array_key_exists('default', $options)) ? $options['default'] : $default;
    }

}
