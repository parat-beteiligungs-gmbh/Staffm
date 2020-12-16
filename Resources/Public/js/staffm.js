/* 
 * Copyright (c) 2016 Markus Puffer <mpuffer@parat.eu>.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Markus Puffer <mpuffer@parat.eu> - initial API and implementation and/or initial documentation
 */

// funktioniert nicht
//var date = $( "#dateb" ).datepicker({ dateFormat: 'dd-mm-yy' }).valueOf(); // Formatierte Datumsausgabe nach Auswahl 


/** setDates<br />
 * change date into an Unix-Timestamp
 * @returns {undefined}
 * @author Markus Puffer
 */
function setDates() {  
    var datDate = document.getElementById("dateb");
    var datInt = document.getElementById("dateInteger");
    //var datDate = document.getElementById("dateb").value;
    //alert(datDate);
    if (datDate.value == 0 || datDate.value == '') {
        datInt.value = 0;
    } else {       
        var strDate = datDate.value;
        var index = strDate.indexOf(".");
        var symbol = ".";
        if (index > 0) {
            symbol = ".";
        } else {
            symbol = "-";
        }
        var dat = datDate.value.split(symbol);
        var newDate = dat[1] + "/" + dat[0] + "/" + dat[2];
        //var datInteger = (new Date(datDate).getTime());
        var datInteger = (new Date(newDate).getTime()) / 1000;
        datInt.value = datInteger;
    }  
}

/**
 * Set the employee presence status 
 * 
 * @param {type} stat
 * @returns {undefined}
 */
function setEmployeePresentStatus(stat) {
    var fullId = stat.id;
    var cb = document.getElementById(fullId);
    var url_controller = 'tx_staffm_staffm[controller]=Mitarbeiter';
    var url_action = 'tx_staffm_staffm[action]=setEmployeePresentStatus';
    var url_data = 'tx_staffm_staffm[employeeUid]=' + cb.value +
            '&tx_staffm_staffm[cbStatus]=' + cb.checked;
    var url_type = 'type=100022';
        
    $.ajax({
        url: window.location.href + '&' + url_type,
        data: url_controller + '&' + url_action + '&' + url_data,
        success: function (result) {
            // Set label red or black
            var labelPresentStatus = document.getElementById('labelPresentStatus');
            var classstring = labelPresentStatus.className;
            if(result == "0") {
                if(classstring == "custom-control-label tx-staffm bold") {
                    labelPresentStatus.className = classstring + " red";
                }
            } else {
                if(classstring == "custom-control-label tx-staffm bold red") {
                    labelPresentStatus.className = "custom-control-label tx-staffm bold";
                }
            }
        }
    });
}

/**
 * Set the deputy active status 
 * 
 * @param {type} stat
 * @returns {undefined}
 */
function setDeputyActiveStatus(stat) {
    var fullId = stat.id;
    var cb = document.getElementById(fullId);
    var url_controller = 'tx_staffm_staffm[controller]=Representation';
    var url_action = 'tx_staffm_staffm[action]=setDeputyActiveStatus';
    var url_data = 'tx_staffm_staffm[representationUid]=' + cb.value +
            '&tx_staffm_staffm[cbStatus]=' + cb.checked;
    var url_type = 'type=100022';
        
    $.ajax({
        url: window.location.href + '&' + url_type,
        data: url_controller + '&' + url_action + '&' + url_data
    });
}

/**
 * Set the deputy active status 
 * 
 * @param {type} stat
 * @returns {undefined}
 */
function setQualificationAuthorizationStatus(stat) {
    var fullId = stat.id;
    var cb = document.getElementById(fullId);
    
    var url_controller = 'tx_staffm_staffm[controller]=Representation';
    var url_action = 'tx_staffm_staffm[action]=setQualificationAuthorizationStatus';
    var url_data = 'tx_staffm_staffm[representationUid]=' + cb.value +
            '&tx_staffm_staffm[cbStatus]=' + cb.checked;
    var url_type = 'type=100022';

    $.ajax({
        url: window.location.href + '&' + url_type,
        data: url_controller + '&' + url_action + '&' + url_data,
        success: function (result) {
            
        }
    });    
}

/**
 * Show calendar in CreateNewUser
 */
function showCalendar() {
    $("#validTo").datepicker(
    {
       prevText: '&#x3c;zurück', prevStatus: '',
        prevJumpText: '&#x3c;&#x3c;', prevJumpStatus: '',
        nextText: 'Vor&#x3e;', nextStatus: '',
        nextJumpText: '&#x3e;&#x3e;', nextJumpStatus: '',
        currentText: 'heute', currentStatus: '',
        todayText: 'heute', todayStatus: '',
        clearText: '-', clearStatus: '',
        closeText: 'schließen', closeStatus: '',
        monthNames: ['Januar','Februar','März','April','Mai','Juni',
        'Juli','August','September','Oktober','November','Dezember'],
        monthNamesShort: ['Jan','Feb','Mär','Apr','Mai','Jun',
        'Jul','Aug','Sep','Okt','Nov','Dez'],
        dayNames: ['Sonntag','Montag','Dienstag','Mittwoch','Donnerstag','Freitag','Samstag'],
        dayNamesShort: ['So','Mo','Di','Mi','Do','Fr','Sa'],
        dayNamesMin: ['So','Mo','Di','Mi','Do','Fr','Sa'],
        showMonthAfterYear: false,
        dateFormat:'d.mm.yy',
        firstDay: 1
    }         
    ).datepicker("show").attr('readonly', 'readonly');
}

/**
 * Show calendar in Modal
 */
function showCalendarInModal(userUid) {
    $("#validTo" + userUid).css('z-index', '55555555555');
    $("#validTo" + userUid).datepicker(
    {
       prevText: '&#x3c;zurück', prevStatus: '',
        prevJumpText: '&#x3c;&#x3c;', prevJumpStatus: '',
        nextText: 'Vor&#x3e;', nextStatus: '',
        nextJumpText: '&#x3e;&#x3e;', nextJumpStatus: '',
        currentText: 'heute', currentStatus: '',
        todayText: 'heute', todayStatus: '',
        clearText: '-', clearStatus: '',
        closeText: 'schließen', closeStatus: '',
        monthNames: ['Januar','Februar','März','April','Mai','Juni',
        'Juli','August','September','Oktober','November','Dezember'],
        monthNamesShort: ['Jan','Feb','Mär','Apr','Mai','Jun',
        'Jul','Aug','Sep','Okt','Nov','Dez'],
        dayNames: ['Sonntag','Montag','Dienstag','Mittwoch','Donnerstag','Freitag','Samstag'],
        dayNamesShort: ['So','Mo','Di','Mi','Do','Fr','Sa'],
        dayNamesMin: ['So','Mo','Di','Mi','Do','Fr','Sa'],
        showMonthAfterYear: false,
        dateFormat:'d.mm.yy',
        firstDay: 1
    }         
    ).datepicker("show").attr('readonly', 'readonly');
    
}