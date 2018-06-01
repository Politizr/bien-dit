Dropzone.autoDiscover = false;
var filePreviewHtml = $('.dz-file-preview').html();
$('.dz-file-preview').hide();
$("#dropzone").dropzone({
    paramName: "file",
    maxFiles: 1,
    acceptedFiles: 'image/*',
    url: $('#dropzone').attr('path'),
    thumbnailWidth: 250,
    thumbnailMethod: 'contain',
    previewTemplate: filePreviewHtml,
    previewsContainer: "#dz-preview-container",
    init: function() {
        this.on("success", function(file, response) {
            this.emit('thumbnail', file, response.filePath + response.thbFileName);

            // upd file name with new one (for removedfile)
            file.previewElement.id = response.filename;

            $('.dz-progress').hide();
            $('.uploadLink').hide();
        });
        this.on("error", function(file, response) {
            msg = response;
            if($.type(response) !== "string") {
                msg = response['error'];
            }
            $('#infoBoxHolder .boxError .notifBoxText').html('Erreur : ' + msg);
            $('#infoBoxHolder .boxError').show();

            $(file.previewElement).remove();
            $('#dropzone').removeClass('dz-max-files-reached');
            this.removeFile(file);

            $('.dz-progress').hide();
        });
        this.on("removedfile", function(file) {
            // upd file name (rename in upload process)
            var name = file.previewElement.id;
            $.ajax({
                url: $('#dropzone').attr('deletePath'),
            });
            $('#dropzone').removeClass('dz-max-files-reached');
            $('.uploadLink').show();
        });
        // preload existing file image cf https://github.com/enyo/dropzone/wiki/FAQ#how-to-show-files-already-stored-on-server
        var currentFileName = $('#dropzone').attr('currentFileName');
        if (typeof currentFileName !== typeof undefined && currentFileName !== false) {
            var currentFile = { name: currentFileName, size: $('#dropzone').attr('fileSize') };
            var uploadPath = $('#dropzone').attr('uploadPath');

            // Call the default addedfile event handler
            this.emit("addedfile", currentFile);

            // And optionally show the thumbnail of the file:
            this.emit("thumbnail", currentFile, uploadPath + currentFileName);

            // Make sure that there is no progress bar, etc...
            this.emit("complete", currentFile);

            // @info pb w. new upload, bad maxFiles management from dropzone
            $('#dropzone').addClass('dz-max-files-reached');
            $('.dz-progress').hide();
            $('.uploadLink').hide();
        }
    }
});
