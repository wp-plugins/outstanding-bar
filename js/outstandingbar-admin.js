/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready(function ($) {

    if ($('#apiKey').val() === '') {
        $('#list').closest('tr').hide();
        hideDisplaySettings();
    }
    
    if($('#list').val() === ''){
        hideDisplaySettings();
    }

    $('#wpbody-content form').on('submit', function(){
        if($('#apiKey').val().search(/^[0-9a-f]{32}-us([0-9]{1,2})$/) !== 0){
            alert('Invalid MailChimp API Key');
            return false;
        }
    });
    
    function hideDisplaySettings() {
        $('#isActive').prop('checked', false).closest('table').hide().prev('h3').hide();
    }
    
    $('#mainColour, #accentColour, #textColour').wpColorPicker();
});


