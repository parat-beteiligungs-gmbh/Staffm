/* 
 * Copyright (C) 2020 PARAT Beteiligungs GmbH
 * Markus Blöchl <mbloechl@parat.eu>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Loads a action in a controller.
 * 
 * @param string action
 * @param string controller
 */
function loadSite(action, controller) {
    document.getElementById("ladebild").setAttribute("style", "display:block;margin-left:auto;margin-right:auto;opacity: 1; ");
    var actionUrl = 'tx_staffm_createuser[action]=' + action;
    var controllerUrl = 'tx_staffm_createuser[controller]=' + controller;
    var typeUrl = 'type=587463';
    
    var data = actionUrl + '&' + controllerUrl + '&' + typeUrl;
    
    $.ajax({
       url: window.location.href,
       type: "GET",
       data: data,
       dataType: 'HTML',
       cache: false,
       success: function (result) {
           $("#ajaxPlace").html(result).fadeIn('fast');
       },
       error: function (jqXHR) {
           $("#ajaxPlace").html("Es ist ein Fehler aufgetreten. Siehe unten." + jqXHR.responseText).fadeIn('fast');
       }
    });
}

/**
 * Loads a site with a action, controller and userUid.
 * 
 * @param string userUid
 * @param string action
 * @param string controller
 */
function loadSiteWithUserUid(userUid, action, controller) {
    document.getElementById("ladebild").setAttribute("style", "display:block;margin-left:auto;margin-right:auto;opacity: 1; ");
    var userUidUrl = 'tx_staffm_createuser[userUid]=' + userUid;
    var actionUrl = 'tx_staffm_createuser[action]=' + action;
    var controllerUrl = 'tx_staffm_createuser[controller]=' + controller;
    var typeUrl = 'type=587463';
    
    var data = userUidUrl + '&' + actionUrl + '&' + controllerUrl + '&' + typeUrl;
    
    $.ajax({
       url: window.location.href,
       type: "GET",
       data: data,
       dataType: 'HTML',
       cache: false,
       success: function (result) {
           $("#ajaxPlace").html(result).fadeIn('fast');
       },
       error: function (jqXHR) {
           $("#ajaxPlace").html("Es ist ein Fehler aufgetreten. Siehe unten." + jqXHR.responseText).fadeIn('fast');
       }
    });
}

/**
 * Writes the mail in the mail field and closes the modal.
 */
function setMail() {
    var mail = document.getElementById('mail').value;
    
    // Check mail
    const reg = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    var isMail = reg.test(mail);
    if(isMail) {
        document.getElementById('oldMail').innerHTML = mail;
        $('#addMail').modal('hide');
    } else {
        document.getElementById('exampleModalLabel').innerHTML = "E-Mailadresse ungültig";
        document.getElementById('exampleModalLabel').style.color = "red";
    }
}

/**
 * Saves the user with the given data.
 * 
 * @param string username
 * @param string password
 * @param string userUid
 */
function saveUserData(username, password, userUid) {
    var mail = document.getElementById('oldMail').innerHTML;
    
    var mailUrl = 'tx_staffm_createuser[mail]=' + mail;
    var usernameUrl = 'tx_staffm_createuser[username]=' + username;
    var passwordUrl = 'tx_staffm_createuser[password]=' + password;
    var userUidUrl = 'tx_staffm_createuser[userUid]=' + userUid;
    
    var actionUrl = 'tx_staffm_createuser[action]=saveUserData';
    var controllerUrl = 'tx_staffm_createuser[controller]=Mitarbeiter';
    var typeUrl = 'type=587463';
    
    if(mail.includes('@')) {
        var data = mailUrl + '&' + usernameUrl + '&' + passwordUrl + '&' + userUidUrl + '&' + actionUrl + '&' + controllerUrl + '&' + typeUrl;
    } else {
        var data = usernameUrl + '&' + passwordUrl + '&' + userUidUrl + '&' + actionUrl + '&' + controllerUrl + '&' + typeUrl;
    }
    
    $.ajax({
       url: window.location.href,
       type: "GET",
       data: data,
       dataType: 'HTML',
       cache: false,
       success: function (result) {
           $("#ajaxPlace").html(result).fadeIn('fast');
       },
       error: function (jqXHR) {
           $("#ajaxPlace").html("Es ist ein Fehler aufgetreten. Siehe unten." + jqXHR.responseText).fadeIn('fast');
       }
    });
}

/**
 * Searches in the user list with the entered params.
 */
function search() {
    var pnr = document.getElementById("searchPnr").value;
    var lastName = document.getElementById("searchLastName").value;
    var firstName = document.getElementById("searchFirstName").value;
    
    var pnrFilter = pnr.toUpperCase();
    var lastNameFilter = lastName.toUpperCase();
    var firstNameFilter = firstName.toUpperCase();
    
    var table = document.getElementById("userTable");
    var tr = table.getElementsByTagName("tr");
    
    for (i = 0; i < tr.length; i++) {
        var tdPnr = tr[i].getElementsByTagName("td")[0];
        var tdLastName = tr[i].getElementsByTagName("td")[1];
        var tdFirstName = tr[i].getElementsByTagName("td")[2];
        
        if (tdPnr || tdLastName || tdFirstName) {
            var txtValuePnr = tdPnr.textContent || tdPnr.innerText;
            var txtValueLastName = tdLastName.textContent || tdLastName.innerText;
            var txtValueFirstName = tdFirstName.textContent || tdFirstName.innerText;
            
            if (txtValuePnr.toUpperCase().indexOf(pnrFilter) > -1 && txtValueLastName.toUpperCase().indexOf(lastNameFilter) > -1 && txtValueFirstName.toUpperCase().indexOf(firstNameFilter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}

/**
 * Assigns the user the new e-mail address.
 * 
 * @param string userUid
 */
function assignNewMail(userUid) {
    var mail = document.getElementById('newMail').value;
    
    const reg = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    var isMail = reg.test(mail);
    
    if(isMail) {
        document.getElementById("ladebild").setAttribute("style", "display:block;margin-left:auto;margin-right:auto;opacity: 1; ");
        var userUidUrl = 'tx_staffm_createuser[userUid]=' + userUid;
        var mailUrl = 'tx_staffm_createuser[mail]=' + mail;
        var actionUrl = 'tx_staffm_createuser[action]=assignNewMail';
        var controllerUrl = 'tx_staffm_createuser[controller]=Mitarbeiter';
        var typeUrl = 'type=587463';

        var data = userUidUrl + '&' + mailUrl + '&' + actionUrl + '&' + controllerUrl + '&' + typeUrl;
        
        $('#assignMail' + userUid).modal('hide');
        
        $.ajax({
           url: window.location.href,
           type: "GET",
           data: data,
           dataType: 'HTML',
           cache: false,
           success: function (result) {
               $("#ajaxPlace").html(result).fadeIn('fast');
           },
           error: function (jqXHR) {
               $("#ajaxPlace").html("Es ist ein Fehler aufgetreten. Siehe unten." + jqXHR.responseText).fadeIn('fast');
           }
        });
    } else {
        document.getElementById("titleMail").innerHTML = "E-Mailadresse ungültig";
        document.getElementById('titleMail').style.color = "red";
    }
}

/**
 * Creates a random string.
 * 
 * @param int length
 * @param string userUid
 * @returns {String}
 */
function randomString(length, userUid) {
    var result           = '';
    var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var charactersLength = characters.length;
    for(var i = 0; i < length; i++ ) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    document.getElementById('newPassword' + userUid).innerHTML = result;
}

/**
 * Assigns the user a new password.
 * 
 * @param string userUid
 */
function assignNewPassword(userUid) {
    document.getElementById("ladebild").setAttribute("style", "display:block;margin-left:auto;margin-right:auto;opacity: 1; ");
    var password = document.getElementById('newPassword' + userUid).innerHTML;
    
    var userUidUrl = 'tx_staffm_createuser[userUid]=' + userUid;
    var passwordUrl = 'tx_staffm_createuser[password]=' + password;
    var actionUrl = 'tx_staffm_createuser[action]=assignNewPassword';
    var controllerUrl = 'tx_staffm_createuser[controller]=Mitarbeiter';
    var typeUrl = 'type=587463';
    
    var data = userUidUrl + '&' + passwordUrl + '&' + actionUrl + '&' + controllerUrl + '&' + typeUrl;
    
    $.ajax({
        url: window.location.href,
        type: "GET",
        data: data,
        dataType: 'HTML',
        cache: false,
        success: function (result) {
            $("#ajaxPlace").html(result).fadeIn('fast');
        },
        error: function (jqXHR) {
            $("#ajaxPlace").html("Es ist ein Fehler aufgetreten. Siehe unten." + jqXHR.responseText).fadeIn('fast');
        }
    });
}

/**
 * Creates the username.
 */
function setUsername() {
    var pnr = document.getElementById('pnr').value;
    var firstName = document.getElementById('firstName').value;
    var lastName = document.getElementById('lastName').value;
    
    var beginFirst = firstName.substring(0,2);
    var beginLast = lastName.substring(0,2);
    
    var username = pnr + beginFirst + beginLast;
    username = username.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
    document.getElementById('username').innerHTML = username;
}

/**
 * Creates random string
 * 
 * @param int length
 */
function randomStringCreate(length) {
    var result           = '';
    var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var charactersLength = characters.length;
    for(var i = 0; i < length; i++ ) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    document.getElementById('password').innerHTML = result;
}

function saveNewUser() {
    document.getElementById("ladebild").setAttribute("style", "display:block;margin-left:auto;margin-right:auto;opacity: 1; ");
    document.getElementById('alertDanger').setAttribute("style", "display:none;");
    // Get entered data
    var username = document.getElementById('username').innerHTML;
    var password = document.getElementById('password').innerHTML;
    var firstName = document.getElementById('firstName').value;
    var lastName = document.getElementById('lastName').value;
    var pnr = document.getElementById('pnr').value;
    var kostenstelle = document.getElementById('kostenstelle').value;
    var kostenstelleApps = document.getElementById('kostenstelleApps').value;
    var dateExpiry = document.getElementById('validTo').value;
    var title = document.getElementById('title').value;
    var position = document.getElementById('position').value;
    var firma = document.getElementById('firma').value;
    var email = document.getElementById('email').value;
    
    // validate required fields
    if(firstName == '' || lastName == '' || pnr == '' || kostenstelle == '') {
        document.getElementById('alertDanger').innerHTML = document.getElementById('required').value;
        document.getElementById('alertDanger').setAttribute("style", "display:block;");
        document.getElementById("ladebild").setAttribute("style", "display:none;margin-left:auto;margin-right:auto;opacity: 1; ");
        document.getElementById("saveNewUser").disabled = false;
        return;
    }
    // validate pnr
    if(!/^\d+$/.test(pnr) || pnr.length != 4) {
        document.getElementById('alertDanger').innerHTML = document.getElementById('fourDigit').value;
        document.getElementById('alertDanger').setAttribute("style", "display:block;");
        document.getElementById("ladebild").setAttribute("style", "display:none;margin-left:auto;margin-right:auto;opacity: 1; ");
        document.getElementById("saveNewUser").disabled = false;
        return;
    }
    // validate cost center
    var centerData = 'tx_staffm_createuser[action]=getAllCostCenters&tx_staffm_createuser[controller]=Mitarbeiter&type=587463';
    $.ajax({
        url: window.location.href,
        type: "GET",
        data: centerData,
        dataType: 'HTML',
        cache: false,
        success: function (result) {
            var allCostCentersString = result;
            var allCostCenters = allCostCentersString.split(',');
            if(!allCostCenters.includes(kostenstelle)) {
                document.getElementById('alertDanger').innerHTML = document.getElementById('costMessage').value;
                document.getElementById('alertDanger').setAttribute("style", "display:block;");
                document.getElementById("ladebild").setAttribute("style", "display:none;margin-left:auto;margin-right:auto;opacity: 1; ");
                document.getElementById("saveNewUser").disabled = false;
                return;
            }
            if(kostenstelleApps != '' && !allCostCenters.includes(kostenstelleApps)) {
                document.getElementById('alertDanger').innerHTML = document.getElementById('costAppsMessage').value;
                document.getElementById('alertDanger').setAttribute("style", "display:block;");
                document.getElementById("ladebild").setAttribute("style", "display:none;margin-left:auto;margin-right:auto;opacity: 1; ");
                document.getElementById("saveNewUser").disabled = false;
                return;
            }
            // validate date of expiry
            if((kostenstelleApps != '' && dateExpiry == '')) {
                document.getElementById('alertDanger').innerHTML = document.getElementById('dateExpiryMessage').value;
                document.getElementById('alertDanger').setAttribute("style", "display:block;");
                document.getElementById("ladebild").setAttribute("style", "display:none;margin-left:auto;margin-right:auto;opacity: 1; ");
                document.getElementById("saveNewUser").disabled = false;
                return;
            } else {
                var today = new Date();
                var day = dateExpiry.substr(0,2);
                var month = dateExpiry.substr(3,2);
                var year = dateExpiry.substr(6,4);
                var newDate = month + '.' + day + '.' + year;
                if(kostenstelleApps != '' && Date.parse(newDate) <= today) {
                    document.getElementById('alertDanger').innerHTML = document.getElementById('dateExpiryMessage2').value;
                    document.getElementById('alertDanger').setAttribute("style", "display:block;");
                    document.getElementById("ladebild").setAttribute("style", "display:none;margin-left:auto;margin-right:auto;opacity: 1; ");
                    document.getElementById("saveNewUser").disabled = false;
                    return;
                }
            }
            // validate position
            var positionData = 'tx_staffm_createuser[action]=getAllPositions&tx_staffm_createuser[controller]=Mitarbeiter&type=587463';
            $.ajax({
                url: window.location.href,
                type: "GET",
                data: positionData,
                dataType: 'HTML',
                cache: false,
                success: function (result) {
                    var allPositionsString = result;
                    var allPositions = allPositionsString.split(',');
                    if(!allPositions.includes(position)) {
                        document.getElementById('alertDanger').innerHTML = document.getElementById('positionMessage').value;
                        document.getElementById('alertDanger').setAttribute("style", "display:block;");
                        document.getElementById("ladebild").setAttribute("style", "display:none;margin-left:auto;margin-right:auto;opacity: 1; ");
                        document.getElementById("saveNewUser").disabled = false;
                        return;
                    }
                    // validate firma
                    var positionData = 'tx_staffm_createuser[action]=getAllCompanies&tx_staffm_createuser[controller]=Mitarbeiter&type=587463';
                    $.ajax({
                        url: window.location.href,
                        type: "GET",
                        data: positionData,
                        dataType: 'HTML',
                        cache: false,
                        success: function (result) {
                            var allCompaniesString = result;
                            var allCompanies = allCompaniesString.split(',');
                            if(!allCompanies.includes(firma)) {
                                document.getElementById('alertDanger').innerHTML = document.getElementById('companyMessage').value;
                                document.getElementById('alertDanger').setAttribute("style", "display:block;");
                                document.getElementById("ladebild").setAttribute("style", "display:none;margin-left:auto;margin-right:auto;opacity: 1; ");
                                document.getElementById("saveNewUser").disabled = false;
                                return;
                            }
                            // validate email
                            const reg = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                            var isMail = reg.test(email);
                            if(!isMail && email != '') {
                                document.getElementById('alertDanger').innerHTML = document.getElementById('mailMessage').value;
                                document.getElementById('alertDanger').setAttribute("style", "display:block;");
                                document.getElementById("ladebild").setAttribute("style", "display:none;margin-left:auto;margin-right:auto;opacity: 1; ");
                                document.getElementById("saveNewUser").disabled = false;
                                return;
                            }

                            // all data is correct
                            var usernameUrl = 'tx_staffm_createuser[username]=' + username;
                            var passwordUrl ='tx_staffm_createuser[password]=' + password;
                            var firstNameUrl = 'tx_staffm_createuser[firstName]=' + firstName;
                            var lastNameUrl = 'tx_staffm_createuser[lastName]=' + lastName;
                            var pnrUrl = 'tx_staffm_createuser[pnr]=' + pnr;
                            var kostenstelleUrl = 'tx_staffm_createuser[kostenstelle]=' + kostenstelle;
                            var kostenstelleAppsUrl = 'tx_staffm_createuser[kostenstelleApps]=' + kostenstelleApps;
                            var dateExpiryUrl = 'tx_staffm_createuser[dateExpiry]=' + dateExpiry;
                            var titleUrl = 'tx_staffm_createuser[title]=' + title;
                            var positionUrl = 'tx_staffm_createuser[position]=' + position;
                            var firmaUrl = 'tx_staffm_createuser[firma]=' + firma;
                            var mailUrl = 'tx_staffm_createuser[mail]=' + email;

                            var actionUrl = 'tx_staffm_createuser[action]=createUser';
                            var controllerUrl = 'tx_staffm_createuser[controller]=Mitarbeiter';
                            var typeUrl = 'type=587463';

                            var completeData = usernameUrl + '&' + passwordUrl + '&' + firstNameUrl + '&' + lastNameUrl + '&' + pnrUrl + '&' + kostenstelleUrl + '&' + kostenstelleAppsUrl + '&' + dateExpiryUrl + '&' + titleUrl + '&' + positionUrl + '&' + firmaUrl + '&' + mailUrl + '&'+ actionUrl + '&' + controllerUrl + '&' + typeUrl;
                            $.ajax({
                                url: window.location.href,
                                type: "GET",
                                data: completeData,
                                dataType: 'HTML',
                                cache: false,
                                success: function (result) {
                                    $("#ajaxPlace").html(result).fadeIn('fast');
                                },
                                error: function (jqXHR) {
                                    $("#ajaxPlace").html("Es ist ein Fehler aufgetreten. Siehe unten." + jqXHR.responseText).fadeIn('fast');
                                }
                            });
                        },
                        error: function (jqXHR) {
                            $("#ajaxPlace").html("Es ist ein Fehler aufgetreten. Siehe unten." + jqXHR.responseText).fadeIn('fast');
                        }
                    });
                },
                error: function (jqXHR) {
                    $("#ajaxPlace").html("Es ist ein Fehler aufgetreten. Siehe unten." + jqXHR.responseText).fadeIn('fast');
                }
            });
        },
        error: function (jqXHR) {
            $("#ajaxPlace").html("Es ist ein Fehler aufgetreten. Siehe unten." + jqXHR.responseText).fadeIn('fast');
        }
    });
}


function saveExistUser(userUid) {
    document.getElementById("ladebild").setAttribute("style", "display:block;margin-left:auto;margin-right:auto;opacity: 1; ");
    document.getElementById('alertDanger').setAttribute("style", "display:none;")
    // Get entered data
    var username = document.getElementById('username').innerHTML;
    var password = document.getElementById('password').innerHTML;
    var kostenstelleApps = document.getElementById('kostenstelleApps').value;
    var dateExpiry = document.getElementById('validTo').value;
    var title = document.getElementById('title').value;
    var position = document.getElementById('position').value || document.getElementById('position').innerHTML;
    var email = document.getElementById('email').value || document.getElementById('email').innerHTML;
    
    // validate required fields
    if(firstName == '' || lastName == '' || pnr == '' || kostenstelle == '') {
        document.getElementById('alertDanger').innerHTML = document.getElementById('required').value;
        document.getElementById('alertDanger').setAttribute("style", "display:block;")
        return;
    }
    // validate cost center
    var centerData = 'tx_staffm_createuser[action]=getAllCostCenters&tx_staffm_createuser[controller]=Mitarbeiter&type=587463';
    $.ajax({
        url: window.location.href,
        type: "GET",
        data: centerData,
        dataType: 'HTML',
        cache: false,
        success: function (result) {
            var allCostCentersString = result;
            var allCostCenters = allCostCentersString.split(',');
            
            if(kostenstelleApps != '' && !allCostCenters.includes(kostenstelleApps)) {
                document.getElementById('alertDanger').innerHTML = document.getElementById('costAppsMessage').value;
                document.getElementById('alertDanger').setAttribute("style", "display:block;");
                document.getElementById("ladebild").setAttribute("style", "display:none;margin-left:auto;margin-right:auto;opacity: 1; ");
                document.getElementById("saveExistUser").disabled = false;
                return;
            }
            // validate date of expiry
            if((kostenstelleApps != '' && dateExpiry == '')) {
                document.getElementById('alertDanger').innerHTML = document.getElementById('dateExpiryMessage').value;
                document.getElementById('alertDanger').setAttribute("style", "display:block;");
                document.getElementById("ladebild").setAttribute("style", "display:none;margin-left:auto;margin-right:auto;opacity: 1; ");
                document.getElementById("saveExistUser").disabled = false;
                return;
            } else {
                var today = new Date();
                var day = dateExpiry.substr(0,2);
                var month = dateExpiry.substr(3,2);
                var year = dateExpiry.substr(6,4);
                var newDate = month + '.' + day + '.' + year;
                if(kostenstelleApps != '' && Date.parse(newDate) <= today) {
                    document.getElementById('alertDanger').innerHTML = document.getElementById('dateExpiryMessage2').value;
                    document.getElementById('alertDanger').setAttribute("style", "display:block;");
                    document.getElementById("ladebild").setAttribute("style", "display:none;margin-left:auto;margin-right:auto;opacity: 1; ");
                    document.getElementById("saveExistUser").disabled = false;
                    return;
                }
            }
            // validate position
            var positionData = 'tx_staffm_createuser[action]=getAllPositions&tx_staffm_createuser[controller]=Mitarbeiter&type=587463';
            $.ajax({
                url: window.location.href,
                type: "GET",
                data: positionData,
                dataType: 'HTML',
                cache: false,
                success: function (result) {
                    var allPositionsString = result;
                    var allPositions = allPositionsString.split(',');
                    if(!allPositions.includes(position)) {
                        document.getElementById('alertDanger').innerHTML = document.getElementById('positionMessage').value;
                        document.getElementById('alertDanger').setAttribute("style", "display:block;");
                        document.getElementById("ladebild").setAttribute("style", "display:none;margin-left:auto;margin-right:auto;opacity: 1; ");
                        document.getElementById("saveExistUser").disabled = false;
                        return;
                    }
                    // validate email
                    const reg = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                    var isMail = reg.test(email);
                    if(!isMail && email != '') {
                        document.getElementById('alertDanger').innerHTML = document.getElementById('mailMessage').value;
                        document.getElementById('alertDanger').setAttribute("style", "display:block;");
                        document.getElementById("ladebild").setAttribute("style", "display:none;margin-left:auto;margin-right:auto;opacity: 1; ");
                        document.getElementById("saveExistUser").disabled = false;
                        return;
                    }
                    // all data is correct
                    var usernameUrl = 'tx_staffm_createuser[username]=' + username;
                    var passwordUrl ='tx_staffm_createuser[password]=' + password;
                    var kostenstelleAppsUrl = 'tx_staffm_createuser[kostenstelleApps]=' + kostenstelleApps;
                    var dateExpiryUrl = 'tx_staffm_createuser[dateExpiry]=' + dateExpiry;
                    var titleUrl = 'tx_staffm_createuser[title]=' + title;
                    var positionUrl = 'tx_staffm_createuser[position]=' + position;
                    var mailUrl = 'tx_staffm_createuser[mail]=' + email;
                    var userUidUrl = 'tx_staffm_createuser[userUid]=' + userUid;

                    var actionUrl = 'tx_staffm_createuser[action]=saveUserData';
                    var controllerUrl = 'tx_staffm_createuser[controller]=Mitarbeiter';
                    var typeUrl = 'type=587463';

                    var completeData = userUidUrl + '&' + usernameUrl + '&' + passwordUrl + '&' + kostenstelleAppsUrl + '&' + dateExpiryUrl + '&' + titleUrl + '&' + positionUrl + '&' + mailUrl + '&'+ actionUrl + '&' + controllerUrl + '&' + typeUrl;
                    $.ajax({
                        url: window.location.href,
                        type: "GET",
                        data: completeData,
                        dataType: 'HTML',
                        cache: false,
                        success: function (result) {
                            $("#ajaxPlace").html(result).fadeIn('fast');
                        },
                        error: function (jqXHR) {
                            $("#ajaxPlace").html("Es ist ein Fehler aufgetreten. Siehe unten." + jqXHR.responseText).fadeIn('fast');
                        }
                    });
                },
                error: function (jqXHR) {
                    $("#ajaxPlace").html("Es ist ein Fehler aufgetreten. Siehe unten." + jqXHR.responseText).fadeIn('fast');
                }
            });
        },
        error: function (jqXHR) {
            $("#ajaxPlace").html("Es ist ein Fehler aufgetreten. Siehe unten." + jqXHR.responseText).fadeIn('fast');
        }
    });
}

function saveEditedUser(userUid) {
    document.getElementById("ladebild").setAttribute("style", "display:block;margin-left:auto;margin-right:auto;opacity: 1; ");
    document.getElementById('alertDanger').setAttribute("style", "display:none;")
    // Get entered data
    var kostenstelleApps = document.getElementById('kostenstelleApps').value;
    var dateExpiry = document.getElementById('validTo').value;
    var title = document.getElementById('title').value;
    var position = document.getElementById('position').value;
    var email = document.getElementById('email').value;
    
    // validate required fields
    if(firstName == '' || lastName == '' || pnr == '' || kostenstelle == '') {
        document.getElementById('alertDanger').innerHTML = document.getElementById('required').value;
        document.getElementById('alertDanger').setAttribute("style", "display:block;")
        document.getElementById("saveEditedUser").disabled = false;
        return;
    }
    // validate cost center
    var centerData = 'tx_staffm_createuser[action]=getAllCostCenters&tx_staffm_createuser[controller]=Mitarbeiter&type=587463';
    $.ajax({
        url: window.location.href,
        type: "GET",
        data: centerData,
        dataType: 'HTML',
        cache: false,
        success: function (result) {
            var allCostCentersString = result;
            var allCostCenters = allCostCentersString.split(',');
            
            if(kostenstelleApps != '' && !allCostCenters.includes(kostenstelleApps)) {
                document.getElementById('alertDanger').innerHTML = document.getElementById('costAppsMessage').value;
                document.getElementById('alertDanger').setAttribute("style", "display:block;");
                document.getElementById("ladebild").setAttribute("style", "display:none;margin-left:auto;margin-right:auto;opacity: 1; ");
                document.getElementById("saveEditedUser").disabled = false;
                return;
            }
            // validate date of expiry
            if((kostenstelleApps != '' && dateExpiry == '')) {
                document.getElementById('alertDanger').innerHTML = document.getElementById('dateExpiryMessage').value;
                document.getElementById('alertDanger').setAttribute("style", "display:block;");
                document.getElementById("ladebild").setAttribute("style", "display:none;margin-left:auto;margin-right:auto;opacity: 1; ");
                document.getElementById("saveEditedUser").disabled = false;
                return;
            } else {
                var today = new Date();
                var day = dateExpiry.substr(0,2);
                var month = dateExpiry.substr(3,2);
                var year = dateExpiry.substr(6,4);
                var newDate = month + '.' + day + '.' + year;
                if(kostenstelleApps != '' && Date.parse(newDate) <= today) {
                    document.getElementById('alertDanger').innerHTML = document.getElementById('dateExpiryMessage2').value;
                    document.getElementById('alertDanger').setAttribute("style", "display:block;");
                    document.getElementById("ladebild").setAttribute("style", "display:none;margin-left:auto;margin-right:auto;opacity: 1; ");
                    document.getElementById("saveEditedUser").disabled = false;
                    return;
                }
            }
            // validate position
            var positionData = 'tx_staffm_createuser[action]=getAllPositions&tx_staffm_createuser[controller]=Mitarbeiter&type=587463';
            $.ajax({
                url: window.location.href,
                type: "GET",
                data: positionData,
                dataType: 'HTML',
                cache: false,
                success: function (result) {
                    var allPositionsString = result;
                    var allPositions = allPositionsString.split(',');
                    if(!allPositions.includes(position)) {
                        document.getElementById('alertDanger').innerHTML = document.getElementById('positionMessage').value;
                        document.getElementById('alertDanger').setAttribute("style", "display:block;");
                        document.getElementById("ladebild").setAttribute("style", "display:none;margin-left:auto;margin-right:auto;opacity: 1; ");
                        document.getElementById("saveEditedUser").disabled = false;
                        return;
                    }
                    // validate email
                    const reg = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                    var isMail = reg.test(email);
                    if(!isMail && email != '') {
                        document.getElementById('alertDanger').innerHTML = document.getElementById('mailMessage').value;
                        document.getElementById('alertDanger').setAttribute("style", "display:block;");
                        document.getElementById("ladebild").setAttribute("style", "display:none;margin-left:auto;margin-right:auto;opacity: 1; ");
                        document.getElementById("saveEditedUser").disabled = false;
                        return;
                    }
                    // all data is correct
                    var kostenstelleAppsUrl = 'tx_staffm_createuser[kostenstelleApps]=' + kostenstelleApps;
                    var dateExpiryUrl = 'tx_staffm_createuser[dateExpiry]=' + dateExpiry;
                    var titleUrl = 'tx_staffm_createuser[title]=' + title;
                    var positionUrl = 'tx_staffm_createuser[position]=' + position;
                    var mailUrl = 'tx_staffm_createuser[mail]=' + email;
                    var userUidUrl = 'tx_staffm_createuser[userUid]=' + userUid;

                    var actionUrl = 'tx_staffm_createuser[action]=updateCreatedUser';
                    var controllerUrl = 'tx_staffm_createuser[controller]=Mitarbeiter';
                    var typeUrl = 'type=587463';

                    var completeData = userUidUrl + '&' + kostenstelleAppsUrl + '&' + dateExpiryUrl + '&' + titleUrl + '&' + positionUrl + '&' + mailUrl + '&'+ actionUrl + '&' + controllerUrl + '&' + typeUrl;
                    $.ajax({
                        url: window.location.href,
                        type: "GET",
                        data: completeData,
                        dataType: 'HTML',
                        cache: false,
                        success: function (result) {
                            $("#ajaxPlace").html(result).fadeIn('fast');
                        },
                        error: function (jqXHR) {
                            $("#ajaxPlace").html("Es ist ein Fehler aufgetreten. Siehe unten." + jqXHR.responseText).fadeIn('fast');
                        }
                    });
                },
                error: function (jqXHR) {
                    $("#ajaxPlace").html("Es ist ein Fehler aufgetreten. Siehe unten." + jqXHR.responseText).fadeIn('fast');
                }
            });
        },
        error: function (jqXHR) {
            $("#ajaxPlace").html("Es ist ein Fehler aufgetreten. Siehe unten." + jqXHR.responseText).fadeIn('fast');
        }
    });
}

function updateAppCostCenter(userUid) {
    var appCostCenter = document.getElementById('appCostCenterValue' + userUid).value;
    var dateExpiry = document.getElementById('validTo' + userUid).value;
    // validate cost center
    var centerData = 'tx_staffm_createuser[action]=getAllCostCenters&tx_staffm_createuser[controller]=Mitarbeiter&type=587463';
    $.ajax({
        url: window.location.href,
        type: "GET",
        data: centerData,
        dataType: 'HTML',
        cache: false,
        success: function (result) {
            var allCostCentersString = result;
            var allCostCenters = allCostCentersString.split(',');
            
            if(appCostCenter != '' && !allCostCenters.includes(appCostCenter)) {
                document.getElementById('exampleModalLabel' + userUid).innerHTML = document.getElementById('costAppsMessage').value;
                document.getElementById('exampleModalLabel' + userUid).style.color = '#d00';
                document.getElementById("ladebild").setAttribute("style", "display:none;margin-left:auto;margin-right:auto;opacity: 1; ");
                document.getElementById("updateAppCostCenter" + userUid).disabled = false;
                return;
            }
            // validate date of expiry
            if((appCostCenter != '' && dateExpiry == '')) {
                document.getElementById('exampleModalLabel' + userUid).innerHTML = document.getElementById('dateExpiryMessage').value;
                document.getElementById('exampleModalLabel' + userUid).style.color = '#d00';
                document.getElementById("ladebild").setAttribute("style", "display:none;margin-left:auto;margin-right:auto;opacity: 1; ");
                document.getElementById("updateAppCostCenter" + userUid).disabled = false;
                return;
            } else {
                var today = new Date();
                var day = dateExpiry.substr(0,2);
                var month = dateExpiry.substr(3,2);
                var year = dateExpiry.substr(6,4);
                var newDate = month + '.' + day + '.' + year;
                if(appCostCenter != '' && Date.parse(newDate) <= today) {
                    document.getElementById('exampleModalLabel' + userUid).innerHTML = document.getElementById('dateExpiryMessage2').value;
                    document.getElementById('exampleModalLabel' + userUid).style.color = '#d00';
                    document.getElementById("ladebild").setAttribute("style", "display:none;margin-left:auto;margin-right:auto;opacity: 1; ");
                    document.getElementById("updateAppCostCenter" + userUid).disabled = false;
                    return;
                }
            }
            // input is correct
            $('#appCostCenter' + userUid).modal('hide');
            document.getElementById("ladebild").setAttribute("style", "display:block;margin-left:auto;margin-right:auto;opacity: 1; ");
            var userUidUrl = 'tx_staffm_createuser[userUid]=' + userUid;
            var appCostCenterUrl = 'tx_staffm_createuser[appCostCenter]=' + appCostCenter;
            var expiryDateUrl = 'tx_staffm_createuser[expiryDate]=' + dateExpiry;
            var actionUrl = 'tx_staffm_createuser[action]=updateAppCostCenter';
            var controllerUrl = 'tx_staffm_createuser[controller]=Mitarbeiter';
            var typeUrl = 'type=587463';
            
            var data = userUidUrl + '&' + appCostCenterUrl + '&' + expiryDateUrl + '&' + actionUrl + '&' + controllerUrl + '&' + typeUrl;
            
            $.ajax({
                url: window.location.href,
                type: "GET",
                data: data,
                dataType: 'HTML',
                cache: false,
                success: function (result) {
//                    $("#ajaxPlace").html(result).fadeIn('fast');
                    document.getElementById('successAlert').innerHTML = result;
                    document.getElementById('successAlert').setAttribute("style", "display:block;");
                    document.getElementById("ladebild").setAttribute("style", "display:none;margin-left:auto;margin-right:auto;opacity: 1; ");
                    var modalData = 'tx_staffm_createuser[userUid]=' + userUid + '&' + 'tx_staffm_createuser[action]=listAllUserModal&tx_staffm_createuser[controller]=Mitarbeiter&type=587463';
                    $.ajax({
                        url: window.location.href,
                        type: "GET",
                        data: modalData,
                        dataType: 'HTML',
                        cache: false,
                        success: function (result) {
                            document.getElementById("appCostCenter" + userUid).innerHTML = result;
                        },
                        error: function (jqXHR) {
                            $("#ajaxPlace").html("Es ist ein Fehler aufgetreten. Siehe unten." + jqXHR.responseText).fadeIn('fast');
                        }
                    });
                },
                error: function (jqXHR) {
                    $("#ajaxPlace").html("Es ist ein Fehler aufgetreten. Siehe unten." + jqXHR.responseText).fadeIn('fast');
                }
            });
        },
        error: function (jqXHR) {
            $("#ajaxPlace").html("Es ist ein Fehler aufgetreten. Siehe unten." + jqXHR.responseText).fadeIn('fast');
        }
    });
}

function setModalTitle(userUid) {
    document.getElementById('exampleModalLabel' + userUid).innerHTML = document.getElementById('headline').innerHTML;
}