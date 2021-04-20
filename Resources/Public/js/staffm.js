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

function disableClickedButton(clickedId) {
    document.getElementById(clickedId).disabled = true;
}

/**
 * Adds the selected user to the table.
 * 
 * @param {string} tableName
 */
function addEmployee(tableName) {
    var source = event.srcElement;
    var employeeName = source.value;
    var opts = document.getElementById('employees').childNodes;
    var selected = false;
    for (var i = 0; i < opts.length; i++) {
        if (opts[i].value === employeeName) {
            // An item was selected from the list
            selected = true;
        }
    }
    if(selected) {
        source.value = '';

        var table = document.getElementById(tableName);

        // Check if user is already added
        for(i = 0; i < table.rows.length; i++) {
            var currentRow = table.rows[i];
            var cell = currentRow.cells[0];
            if(cell.innerHTML.includes(employeeName)) {
                alreadyAdded = true;
            }
        }

        if(alreadyAdded) {
            var alreadyAdded = document.getElementById('employeeAlreadyAdded').value;
            alert(alreadyAdded);
        } else {
            var datalist = document.getElementById("employees");
            var id = datalist.options.namedItem(employeeName).id;
            document.getElementById("memberUids").value = document.getElementById("memberUids").value + "," + id;
            
            var row = table.insertRow(table.rows.length);
            var cell = row.insertCell(0);
            var timeStampInMs = window.performance && window.performance.now && window.performance.timing && window.performance.timing.navigationStart ? window.performance.now() + window.performance.timing.navigationStart : Date.now();
            cell.innerHTML = employeeName + "<div id='" + timeStampInMs + "' style='float:right' onclick='deleteMember(\"" + timeStampInMs + "\", \"" + tableName + "\");'><svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash' viewBox='0 0 16 16'><path d='M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z'/><path fill-rule='evenodd' d='M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z'/></svg></div>";
            for(i = 0; i < table.rows.length; i++) {
                table.rows[i].cells[0].id = i;
            }
            document.getElementById(tableName + 'Tr').style.display = 'table-row';
        }
    }
}

function deleteMember(divId, tableName) {
    var table = document.getElementById(tableName);
    var row = document.getElementById(divId).parentElement.parentElement;
    // update uids
    var datalist = document.getElementById("employees");
    var employeeName = row.cells[0].innerHTML.substr(0, row.cells[0].innerHTML.indexOf('<'));
    employeeName = employeeName.trim();
    var id = datalist.options.namedItem(employeeName).id;
    var addedIds = document.getElementById('memberUids').value;
    document.getElementById('memberUids').value = addedIds.replace("," + id, '');
    
    var rowIndex = row.rowIndex;
    table.deleteRow(rowIndex);
    for(i = 0; i < table.rows.length; i++) {
        table.rows[i].cells[0].id = i;
    }
    if(table.rows.length == 0) {
        document.getElementById(tableName + 'Tr').style.display = 'none';
    }
}

function addNotice(employeeUid) {
    var notice = document.getElementById("bemadd").value;
    var noticesUids = document.getElementById("hiddenNoticeUid").value;
    
    var noticesUidsUrl = "tx_staffm_staffmvorg[noticesUids]=" + noticesUids;
    var noticeUrl = "tx_staffm_staffmvorg[notice]=" + notice;
    var employeeUrl = "tx_staffm_staffmvorg[employeeUid]=" + employeeUid;
    var actionUrl = "tx_staffm_staffmvorg[action]=addNewNotice";
    var controllerUrl = "tx_staffm_staffmvorg[controller]=Training";
    var typeUrl = "type=256987";
    
    var data = noticesUidsUrl + "&" + noticeUrl + "&" + employeeUrl + "&" + actionUrl + "&" + controllerUrl + "&" + typeUrl;
    if(notice != '') {
        document.getElementById("bemadd").placeholder = "Hier Notizen eintragen";
        document.getElementById("bemadd").classList.add('placeholder-grey');
        $.ajax({
            url: window.location.href,
            type: 'GET',
            data: data,
            dataType: "HTML",
            cache: false,
            success: function (result) {
                document.getElementById("hiddenNoticeUid").value = result;
                // Update notice table
                var uidsUrl = "tx_staffm_staffmvorg[uids]=" + result;
                var actionUrl = "tx_staffm_staffmvorg[action]=noticeTable";
                var controllerUrl = "tx_staffm_staffmvorg[controller]=Training";
                var typeUrl = "type=256987";

                var dataTwo = uidsUrl + "&" + actionUrl + "&" + controllerUrl + "&" + typeUrl;

                $.ajax({
                    url: window.location.href,
                    type: 'GET',
                    data: dataTwo,
                    dataType: "HTML",
                    cache: false,
                    success: function (resultNew) {
                        $("#noticeTable").html(resultNew).fadeIn('fast');
                        document.getElementById("bemadd").value = "";
                    },
                    error: function (jqXHR, textStatus, errorThrow) {
                        $("#noticeTable").html('Ajax request - ' + textStatus + ': ' + errorThrow + jqXHR.responseText).fadeIn('fast');
                    }
                });
            },
            error: function (jqXHR, textStatus, errorThrow) {
                $("#noticeTable").html('Ajax request - ' + textStatus + ': ' + errorThrow + jqXHR.responseText).fadeIn('fast');
            }
        });
    } else {
        document.getElementById("bemadd").placeholder = "Bitte geben Sie hier zuerst ihre Notiz ein!";
        document.getElementById("bemadd").classList.add('placeholder-red');
    }
}

function deleteNotice(noticeUid) {
    var noticesUids = document.getElementById("hiddenNoticeUid").value;
    
    var noticesUidsUrl = "tx_staffm_staffmvorg[noticesUids]=" + noticesUids;
    var noticeUrl = "tx_staffm_staffmvorg[noticeUid]=" + noticeUid;
    var actionUrl = "tx_staffm_staffmvorg[action]=removeNotice";
    var controllerUrl = "tx_staffm_staffmvorg[controller]=Training";
    var typeUrl = "type=256987";
    
    var data = noticesUidsUrl + "&" + noticeUrl + "&" + actionUrl + "&" + controllerUrl + "&" + typeUrl;
    
    $.ajax({
        url: window.location.href,
        type: 'GET',
        data: data,
        dataType: "HTML",
        cache: false,
        success: function (result) {
            document.getElementById("hiddenNoticeUid").value = result;
            // Update notice table
            var uidsUrl = "tx_staffm_staffmvorg[uids]=" + result;
            var actionUrl = "tx_staffm_staffmvorg[action]=noticeTable";
            var controllerUrl = "tx_staffm_staffmvorg[controller]=Training";
            var typeUrl = "type=256987";
            
            var dataTwo = uidsUrl + "&" + actionUrl + "&" + controllerUrl + "&" + typeUrl;
            
            $.ajax({
                url: window.location.href,
                type: 'GET',
                data: dataTwo,
                dataType: "HTML",
                cache: false,
                success: function (resultNew) {
                    $("#noticeTable").html(resultNew).fadeIn('fast');
                    document.getElementById("bemadd").value = "";
                },
                error: function (jqXHR, textStatus, errorThrow) {
                    $("#noticeTable").html('Ajax request - ' + textStatus + ': ' + errorThrow + jqXHR.responseText).fadeIn('fast');
                }
            });
        },
        error: function (jqXHR, textStatus, errorThrow) {
            $("#noticeTable").html('Ajax request - ' + textStatus + ': ' + errorThrow + jqXHR.responseText).fadeIn('fast');
        }
    });
}

function editNotice(noticeUid) {
    var oldText = document.getElementById("bemerkung" + noticeUid).value;  
    document.getElementById("text" + noticeUid).innerHTML = "<textarea cols='40' rows='3' id='textToUpdate' class='form-control'>" + oldText + "</textarea>";
    document.getElementById("edit" + noticeUid).innerHTML = "<svg width='1em' height='1em' viewBox='0 0 16 16' class='bi bi-file-post-fill' fill='currentColor' xmlns='http://www.w3.org/2000/svg'><path fill-rule='evenodd' d='M12 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zM4.5 3a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zm0 2a.5.5 0 0 0-.5.5v8a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5v-8a.5.5 0 0 0-.5-.5h-7z'/></svg>";
    document.getElementById("edit" + noticeUid).setAttribute( "onClick", "updateNotice(" + noticeUid + ");return false" );
}

function updateNotice(noticeUid) {
    document.getElementById("edit" + noticeUid).innerHTML = "<svg width='1em' height='1em' viewBox='0 0 16 16' class='bi bi-pen-fill' fill='currentColor' xmlns='http://www.w3.org/2000/svg'><path fill-rule='evenodd' d='M13.498.795l.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001z'/></svg>";
    document.getElementById("edit" + noticeUid).setAttribute( "onClick", "javascript: editNotice(" + noticeUid + ");return false" );
        
    var actionUrl = "tx_staffm_staffmvorg[action]=updateNotice";
    var controllerUrl = "tx_staffm_staffmvorg[controller]=Training";
    var typeUrl = "type=256987";
        
    var noticeUidUrl = "tx_staffm_staffmvorg[noticeUid]=" + noticeUid;
    var textUrl = "tx_staffm_staffmvorg[text]=" + document.getElementById("textToUpdate").value;
        
    var data = actionUrl + "&" + controllerUrl + "&" + noticeUidUrl + "&" + textUrl + "&" + typeUrl;
        
    $.ajax({
        url: window.location.href,
        type: 'GET',
        data: data,
        dataType: "HTML",
        cache: false,
        success: function (result) {
            // Update notice table
            var uidsUrl = "tx_staffm_staffmvorg[uids]=" + document.getElementById("hiddenNoticeUid").value;
            var actionUrl = "tx_staffm_staffmvorg[action]=noticeTable";
            var controllerUrl = "tx_staffm_staffmvorg[controller]=Training";
            var typeUrl = "type=256987";

            var dataTwo = uidsUrl + "&" + actionUrl + "&" + controllerUrl + "&" + typeUrl;

            $.ajax({
                url: window.location.href,
                type: 'GET',
                data: dataTwo,
                dataType: "HTML",
                cache: false,
                success: function (resultNew) {
                    $("#noticeTable").html(resultNew).fadeIn('fast');
                },
                error: function (jqXHR, textStatus, errorThrow) {
                    $("#noticeTable").html('Ajax request - ' + textStatus + ': ' + errorThrow + jqXHR.responseText).fadeIn('fast');
                }
            });
        },
        error: function (jqXHR, textStatus, errorThrow) {
            $("#noticeTable").html('Ajax request - ' + textStatus + ': ' + errorThrow + jqXHR.responseText).fadeIn('fast');
        }
    });
}

function saveNewTraining() {
    // get and check name
    var name = document.getElementById('trainingName').value;
    if(name === '') {
        alert(document.getElementById('noName').value);
    } else {
        var scheduledDate = document.getElementById('trainingScheduledDate').value;
        if(scheduledDate === '') {
            alert(document.getElementById('noScheduledDate').value);
        } else {
            // data is ok
            var qualifications = document.getElementById('qualisUid').value;
            document.getElementById("ladebild").setAttribute("style", "display:block;margin-left:auto;margin-right:auto;opacity: 1; ");
            var members = document.getElementById('memberUids').value;
            if(members !== '') {
                members = members.substr(1, members.length);
            }
            var noticesUids = document.getElementById("hiddenNoticeUid").value;

            // call controller to create objects and redirect
            var nameUrl = 'tx_staffm_staffmvorg[name]=' + name;
            var scheduledDateUrl = 'tx_staffm_staffmvorg[scheduledDate]=' + scheduledDate;
            var qualiUrl = 'tx_staffm_staffmvorg[qualis]=' + qualifications;
            var membersUrl = 'tx_staffm_staffmvorg[members]=' + members;
            var noticesUrl = 'tx_staffm_staffmvorg[noticesUid]=' + noticesUids;

            var actionUrl = 'tx_staffm_staffmvorg[action]=create';
            var controllerUrl = 'tx_staffm_staffmvorg[controller]=Training';
            var typeUrl = "type=256987";

            var data = nameUrl + '&' + scheduledDateUrl + '&' + qualiUrl + '&' + membersUrl + '&' + noticesUrl + '&' + actionUrl + '&' + controllerUrl + '&' + typeUrl;

            $.ajax({
                url: window.location.href,
                type: 'GET',
                data: data,
                dataType: "HTML",
                cache: false,
                success: function (resultNew) {
                    $("#placeAjaxResult").html(resultNew).fadeIn('fast');
                },
                error: function (jqXHR, textStatus, errorThrow) {
                    $("#placeAjaxResult").html('Ajax request - ' + textStatus + ': ' + errorThrow + jqXHR.responseText).fadeIn('fast');
                }
            });
        }
    }
}

function showHistories() {
    var histories = document.getElementById('histories');
    if(histories.style.display === 'none') {
        document.getElementById('buttonHistory').innerHTML = document.getElementById('hide').value;
        histories.style.display = 'block';
    } else {
        document.getElementById('buttonHistory').innerHTML = document.getElementById('show').value;
        histories.style.display = 'none';
    }
}

function addAccomplishedDate() {
    $("#accomplishedModal").modal();
}

function trainingAccomplished(employeeUid) {
    var date = document.getElementById('trainingAccomplishedDate').value;
    if(date === '') {
        alert(document.getElementById('noDate').value);
    } else {
        var effect = document.getElementById('selectedEffect').value;
        var reason = document.getElementById('effectReason').value;
        if(effect !== '1' && reason === '') {
            alert(document.getElementById('reasonMessage').value);
        } else {
            document.getElementById('effectUid').value = effect;
            var changeString = document.getElementById('change').value;
            var changeButton = "<button style='float: right' type='button' class='btn btn-default btn-sm' onclick='addAccomplishedDate();return false'>" + changeString + "</button>"
            document.getElementById('accomplishedDate').innerHTML = date + changeButton;
            document.getElementById('trainingEffect').innerHTML = document.getElementById('selectedEffect').options[document.getElementById('selectedEffect').selectedIndex].text;
            
            if(effect !== '1') {
                // set new notice
                var noticesUids = document.getElementById("hiddenNoticeUid").value;

                var noticesUidsUrl = "tx_staffm_staffmvorg[noticesUids]=" + noticesUids;
                reason = document.getElementById('effectReasonNotice').value + ': ' + reason;
                var noticeUrl = "tx_staffm_staffmvorg[notice]=" + reason;
                var employeeUrl = "tx_staffm_staffmvorg[employeeUid]=" + employeeUid;
                var actionUrl = "tx_staffm_staffmvorg[action]=addNewNotice";
                var controllerUrl = "tx_staffm_staffmvorg[controller]=Training";
                var typeUrl = "type=256987";

                var data = noticesUidsUrl + "&" + noticeUrl + "&" + employeeUrl + "&" + actionUrl + "&" + controllerUrl + "&" + typeUrl;
                $.ajax({
                    url: window.location.href,
                    type: 'GET',
                    data: data,
                    dataType: "HTML",
                    cache: false,
                    success: function (result) {
                        document.getElementById("hiddenNoticeUid").value = result;
                        // Update notice table
                        var uidsUrl = "tx_staffm_staffmvorg[uids]=" + result;
                        var actionUrl = "tx_staffm_staffmvorg[action]=noticeTable";
                        var controllerUrl = "tx_staffm_staffmvorg[controller]=Training";
                        var typeUrl = "type=256987";

                        var dataTwo = uidsUrl + "&" + actionUrl + "&" + controllerUrl + "&" + typeUrl;

                        $.ajax({
                            url: window.location.href,
                            type: 'GET',
                            data: dataTwo,
                            dataType: "HTML",
                            cache: false,
                            success: function (resultNew) {
                                $("#noticeTable").html(resultNew).fadeIn('fast');
                                $("#accomplishedModal").modal('hide');
                            },
                            error: function (jqXHR, textStatus, errorThrow) {
                                $("#noticeTable").html('Ajax request - ' + textStatus + ': ' + errorThrow + jqXHR.responseText).fadeIn('fast');
                            }
                        });
                    },
                    error: function (jqXHR, textStatus, errorThrow) {
                        $("#noticeTable").html('Ajax request - ' + textStatus + ': ' + errorThrow + jqXHR.responseText).fadeIn('fast');
                    }
                });
            } else {
                $("#accomplishedModal").modal('hide');
            }
        }
    }
}

function updateTraining(trainingUid) {
    var name = document.getElementById('trainingName').value;
    if(name === '') {
        alert(document.getElementById('noName').value);
    } else {
        var scheduledDate = document.getElementById('trainingScheduledDate').innerHTML;
        var accomplished = document.getElementById('accomplishedDate').innerHTML;
        accomplished = accomplished.substr(0, accomplished.indexOf('<'));
        var effectUid = document.getElementById('effectUid').value;
        // data ok
        var qualis = document.getElementById('qualisUid').value;
            
        document.getElementById("ladebild").setAttribute("style", "display:block;margin-left:auto;margin-right:auto;opacity: 1; ");
        var employees = document.getElementById('memberUids').value;
        var notices = document.getElementById('hiddenNoticeUid').value;

        var trainingUrl = 'tx_staffm_staffmvorg[training]=' + trainingUid;
        var nameUrl = 'tx_staffm_staffmvorg[name]=' + name;
        var scheduledDateUrl = 'tx_staffm_staffmvorg[scheduledDate]=' + scheduledDate;
        var accomplishedUrl = 'tx_staffm_staffmvorg[accomplished]=' + accomplished;
        var effectUidUrl = 'tx_staffm_staffmvorg[effectUid]=' + effectUid;
        var qualiUrl = 'tx_staffm_staffmvorg[qualis]=' + qualis;
        var employeesUrl = 'tx_staffm_staffmvorg[employees]=' + employees;
        var noticesUrl = 'tx_staffm_staffmvorg[notices]=' + notices;

        var actionUrl = 'tx_staffm_staffmvorg[action]=update';
        var controllerUrl = 'tx_staffm_staffmvorg[controller]=Training';
        var type = 'type=256987';

        var data = trainingUrl + '&' + nameUrl + '&' + scheduledDateUrl + '&' + accomplishedUrl + '&' + effectUidUrl + '&' + qualiUrl + '&' + employeesUrl + '&' + noticesUrl + '&' + actionUrl + '&' + controllerUrl + '&' + type;

        $.ajax({
            url: window.location.href,
            type: "GET",
            data: data,
            dataType: 'HTML',
            cache: false,
            success: function (result) {
                $("#placeAjaxResult").html(result).fadeIn('fast');
            },
            error: function (jqXHR) {
                $("#placeAjaxResult").html("Es ist ein Fehler aufgetreten. Siehe unten." + jqXHR.responseText).fadeIn('fast');
            }
        });
    }
}

function toggleScheduled() {
    $("#scheduledModal").modal();
}

function takeShift(employeeUid) {
    var reason = document.getElementById('shiftReason').value;
    if(reason === '') {
        alert(document.getElementById('reasonMessage').value);
    } else {
        // set date
        var date = document.getElementById('trainingNewScheduledDate').value;
        document.getElementById('trainingScheduledDate').innerHTML = date;
        // set number shifts
        if(document.getElementById('helpShifts').value == '0') {
            document.getElementById('helpShifts').value = '1';
            var actShifts = document.getElementById('numberShiftsTraining').innerHTML;
            var numberShifts = parseInt(actShifts, 10);
            var newNumber = numberShifts + 1;
            document.getElementById('numberShiftsTraining').innerHTML = newNumber;
        } 
        // set new notice
        var noticesUids = document.getElementById("hiddenNoticeUid").value;
    
        var noticesUidsUrl = "tx_staffm_staffmvorg[noticesUids]=" + noticesUids;
        reason = document.getElementById('reasonNotice').value + ': ' + reason;
        var noticeUrl = "tx_staffm_staffmvorg[notice]=" + reason;
        var employeeUrl = "tx_staffm_staffmvorg[employeeUid]=" + employeeUid;
        var actionUrl = "tx_staffm_staffmvorg[action]=addNewNotice";
        var controllerUrl = "tx_staffm_staffmvorg[controller]=Training";
        var typeUrl = "type=256987";
    
        var data = noticesUidsUrl + "&" + noticeUrl + "&" + employeeUrl + "&" + actionUrl + "&" + controllerUrl + "&" + typeUrl;
        $.ajax({
            url: window.location.href,
            type: 'GET',
            data: data,
            dataType: "HTML",
            cache: false,
            success: function (result) {
                document.getElementById("hiddenNoticeUid").value = result;
                // Update notice table
                var uidsUrl = "tx_staffm_staffmvorg[uids]=" + result;
                var actionUrl = "tx_staffm_staffmvorg[action]=noticeTable";
                var controllerUrl = "tx_staffm_staffmvorg[controller]=Training";
                var typeUrl = "type=256987";

                var dataTwo = uidsUrl + "&" + actionUrl + "&" + controllerUrl + "&" + typeUrl;

                $.ajax({
                    url: window.location.href,
                    type: 'GET',
                    data: dataTwo,
                    dataType: "HTML",
                    cache: false,
                    success: function (resultNew) {
                        $("#noticeTable").html(resultNew).fadeIn('fast');
                        $("#scheduledModal").modal('hide');
                    },
                    error: function (jqXHR, textStatus, errorThrow) {
                        $("#noticeTable").html('Ajax request - ' + textStatus + ': ' + errorThrow + jqXHR.responseText).fadeIn('fast');
                    }
                });
            },
            error: function (jqXHR, textStatus, errorThrow) {
                $("#noticeTable").html('Ajax request - ' + textStatus + ': ' + errorThrow + jqXHR.responseText).fadeIn('fast');
            }
        });
    } 
}

function cancelTraining(employeeUid) {
    var reason = document.getElementById('cancelReasonInput').value;
    if(reason === '') {
        alert(document.getElementById('reasonMessage').value);
    } else {
        document.getElementById('trainingScheduledDate').innerHTML = document.getElementById('canceled').value;
        reason = document.getElementById('cancelReason').value + ': ' + reason;
        
        var noticesUids = document.getElementById("hiddenNoticeUid").value;
    
        var noticesUidsUrl = "tx_staffm_staffmvorg[noticesUids]=" + noticesUids;
        var noticeUrl = "tx_staffm_staffmvorg[notice]=" + reason;
        var employeeUrl = "tx_staffm_staffmvorg[employeeUid]=" + employeeUid;
        var actionUrl = "tx_staffm_staffmvorg[action]=addNewNotice";
        var controllerUrl = "tx_staffm_staffmvorg[controller]=Training";
        var typeUrl = "type=256987";
    
        var data = noticesUidsUrl + "&" + noticeUrl + "&" + employeeUrl + "&" + actionUrl + "&" + controllerUrl + "&" + typeUrl;
        $.ajax({
            url: window.location.href,
            type: 'GET',
            data: data,
            dataType: "HTML",
            cache: false,
            success: function (result) {
                document.getElementById("hiddenNoticeUid").value = result;
                // Update notice table
                var uidsUrl = "tx_staffm_staffmvorg[uids]=" + result;
                var actionUrl = "tx_staffm_staffmvorg[action]=noticeTable";
                var controllerUrl = "tx_staffm_staffmvorg[controller]=Training";
                var typeUrl = "type=256987";

                var dataTwo = uidsUrl + "&" + actionUrl + "&" + controllerUrl + "&" + typeUrl;

                $.ajax({
                    url: window.location.href,
                    type: 'GET',
                    data: dataTwo,
                    dataType: "HTML",
                    cache: false,
                    success: function (resultNew) {
                        $("#noticeTable").html(resultNew).fadeIn('fast');
                        $("#cancelModal").modal('hide');
                    },
                    error: function (jqXHR, textStatus, errorThrow) {
                        $("#noticeTable").html('Ajax request - ' + textStatus + ': ' + errorThrow + jqXHR.responseText).fadeIn('fast');
                    }
                });
            },
            error: function (jqXHR, textStatus, errorThrow) {
                $("#noticeTable").html('Ajax request - ' + textStatus + ': ' + errorThrow + jqXHR.responseText).fadeIn('fast');
            }
        });
    }
}

function toggleCancel() {
    $("#cancelModal").modal();
}

function showHideReason() {
    var effect = document.getElementById('selectedEffect').value;
    if(effect !== '1') {
        document.getElementById('effectReasonTr').style.display = 'table-row';
    } else {
        document.getElementById('effectReasonTr').style.display = 'none';
    }
}

/**
 * Adds the selected user to the table.
 * 
 * @param {string} tableName
 */
function addQuali(tableName) {
    var source = event.srcElement;
    var qualiName = source.value;
    var opts = document.getElementById('qualis').childNodes;
    var selected = false;
    for (var i = 0; i < opts.length; i++) {
        if (opts[i].value === qualiName) {
            // An item was selected from the list
            selected = true;
        }
    }
    if(selected) {
        source.value = '';
        var tableTr = document.getElementById(tableName + "Tr");
        tableTr.style.display = 'table-row';
        var table = document.getElementById(tableName);

        // Check if quali is already added
        var alreadyAdded = false;
        for(i = 0; i < table.rows.length; i++) {
            var currentRow = table.rows[i];
            var cell = currentRow.cells[0];
            if(cell.innerHTML.includes(qualiName)) {
                alreadyAdded = true;
            }
        }

        if(alreadyAdded) {
            var alreadyAdded = document.getElementById('qualiAlreadyAdded').value;
            alert(alreadyAdded);
        } else {
            var datalist = document.getElementById("qualis");
            var id = datalist.options.namedItem(qualiName).id;
            document.getElementById("qualisUid").value = document.getElementById("qualisUid").value + "," + id;
            // check if first member
            var row = table.insertRow(table.rows.length);
            cell = row.insertCell(0);
            var timeStampInMs = window.performance && window.performance.now && window.performance.timing && window.performance.timing.navigationStart ? window.performance.now() + window.performance.timing.navigationStart : Date.now();
            cell.innerHTML = qualiName + "<div id='" + timeStampInMs + "' style='float:right' onclick='removeQuali(\"" + timeStampInMs + "\", \"" + tableName + "\");'><svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash' viewBox='0 0 16 16'><path d='M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z'/><path fill-rule='evenodd' d='M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z'/></svg></div>";
            for(i = 0; i < table.rows.length; i++) {
                table.rows[i].cells[0].id = i;
            }
        }
    }
}

function removeQuali(divId, tableName) {
    var table = document.getElementById(tableName);
    var row = document.getElementById(divId).parentElement.parentElement;
    // update uids
    var datalist = document.getElementById("qualis");
    var qualiName = row.cells[0].innerHTML.substr(0, row.cells[0].innerHTML.indexOf('<'));
    // remove spaces
    qualiName = qualiName.trim();
    var id = datalist.options.namedItem(qualiName).id;
    var addedIds = document.getElementById('qualisUid').value;
    document.getElementById('qualisUid').value = addedIds.replace("," + id, '');
    
    var rowIndex = row.rowIndex;
    table.deleteRow(rowIndex);
    for(i = 0; i < table.rows.length; i++) {
        table.rows[i].cells[0].id = i;
    }
    if(table.rows.length == 0) {
        document.getElementById(tableName + "Tr").style.display = "none";
    }
}

function changeColor(uid) {
    var checkbox = document.getElementById("selected" + uid);
    var row = document.getElementById("tr" + uid);
    if(!checkbox.checked) {
        row.style.backgroundColor = "";
    } else {
        row.style.backgroundColor = "#C5C5C5";
    }
    
}