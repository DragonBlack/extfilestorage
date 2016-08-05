/*// The Browser API key obtained from the Google Developers Console.
 var developerKey = 'AIzaSyBzJWBq1ic-XKk6ynGmkDVYXibcAz4UPQg';

 // The Client ID obtained from the Google Developers Console. Replace with your own Client ID.
 var clientId = "11834363688-4bpakiq2vpv2mug5rgi7esd1csa77idk.apps.googleusercontent.com";

 // Scope to use to access user's docs.
 var scope = ['https://www.googleapis.com/auth/drive.readonly.metadata'];*/

//button for picker call
// var pickButton = $('<button/>',{
//     id: 'pick',
//     text: 'Choose from GoogleDrive',
//     click: function () { onApiLoad();}
// });
//
// $('#container1').append(pickButton);


// global variables for php
var uploadUrl = {};
var successFunc = {};
var afterUpload = {};
var google_access_token;
var downloadUrl = 'unknown';
var elementId;

var developerKey;

// The Client ID obtained from the Google Developers Console. Replace with your own Client ID.
var clientId;

// Scope to use to access user's docs.
var scope;

var pickerApiLoaded = false;
var oauthToken;

// Use the API Loader script to load google.picker and gapi.auth.
function onApiLoad(elem) {
    elementId = elem.target.id;
    gapi.load('auth', {'callback': onAuthApiLoad});
    gapi.load('picker', {'callback': onPickerApiLoad});
}

function onAuthApiLoad() {
    window.gapi.auth.authorize(
        {
            'client_id': clientId,
            'scope': scope,
            'immediate': false
        },
        handleAuthResult);
}

function onPickerApiLoad() {
    pickerApiLoaded = true;
    createPicker();
}

function handleAuthResult(authResult) {
    if (authResult && !authResult.error) {
        oauthToken = authResult.access_token;
        createPicker();
    }
}

// Create and render a Picker object for picking user docs.
function createPicker() {
    if (pickerApiLoaded && oauthToken) {
        var picker = new google.picker.PickerBuilder().
        addView(google.picker.ViewId.DOCS).
        setOAuthToken(oauthToken).
        setDeveloperKey(developerKey).
        setCallback(pickerCallback).
        build();
        picker.setVisible(true);
    }
}

// A simple callback implementation.
// contains EXPORTLINKS!!!
function pickerCallback(data) {
    var googleSelectedFiles = [];

    if (data[google.picker.Response.ACTION] == google.picker.Action.PICKED) {
        var doc = data[google.picker.Response.DOCUMENTS][0];

        gapi.client.request({
            'path': '/drive/v2/files/' + doc.id,
            'method': 'GET',
            callback: function (responsejs, responsetxt) {
                var files = [
                    {
                        name: responsejs.title,
                        path: responsejs.alternateLink,
                        size: responsejs.fileSize,
                        type: responsejs.mimeType ? responsejs.mimeType : ''
                    }
                ];

                if(successFunc[elementId]){
                    successFunc[elementId](files);
                }
            }
        });
    }
}