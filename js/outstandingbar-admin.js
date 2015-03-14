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
        if($('#apiKey').val().search(/^[0-9a-f]{32}-us([1-9]|10)$/) !== 0){
            console.log('invalid api key');
            return false;
        }
    });


    function hideDisplaySettings() {
        $('#isActive').prop('checked', false).closest('table').hide().prev('h3').hide();
    }

    function showDisplaySettings() {
        $('#isActive').closest('table').show().prev('h3').show();
    }
    
    $('#mainColour, #accentColour, #textColour').wpColorPicker();
});


