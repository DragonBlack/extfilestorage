var uploadUrl = {};
var successFunc = {};
var cancelFunc = {};
var linktype = 'direct';
var afterUpload = {};
var multiselect = true;
var extensions = [];

$(document).on('click', '*[data-id=dropbox]', function(e){
    var id = $(this).attr('id');
    Dropbox.choose({
        success: function(files) {
            var filesMapped = [];
            files.forEach(function(e, i){
                var tmp = {
                    path: e.link,
                    name: e.name,
                    size: e.bytes,
                    type: ''
                };
                filesMapped.push(tmp);
            });

            if(successFunc[id]){
                successFunc[id](filesMapped);
                return;
            }

            $.post({
                url: uploadUrl[id],
                data: {file: filesMapped},
                dataType: 'json',
                success: function(response){
                    if(afterUpload[id]){
                        afterUpload[id](response);
                    }
                }
            });
        },
        cancel: cancelFunc[id] || function(){

        },
        linkType: linktype,
        multiselect: multiselect,
        extensions: extensions
    });
});
