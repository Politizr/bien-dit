// beta

// description
var descriptionEditor = new MediumEditor('.editable.description', {
    toolbar: {
        allowMultiParagraphSelection: false,
        diffTop: -15,
        buttons: [
            {name: 'bold', contentDefault: '<i class="icon-bold"></i>'}, 
            {name: 'italic', contentDefault: '<i class="icon-italic"></i>'}, 
            {name: 'h1', contentDefault: '<i class="icon-h1"></i>'},
            {name: 'h2', contentDefault: '<i class="icon-h2"></i>'},
            {name: 'unorderedlist', contentDefault: '<i class="icon-timeline"></i>'},
            {name: 'quote', contentDefault: '<i class="icon-quote"></i>'},
            {name: 'anchor', contentDefault: '<i class="icon-link"></i>'}
        ]
    },
    placeholder: {
        text: 'Cliquez ici pour saisir le texte de votre publication.'
    },
    anchor: {
        placeholderText: 'Saisissez une adresse internet'
    },
    anchorPreview: false,
    autoLink:true
});

$(function () {
    $('.editable.description').mediumInsert({
        editor: descriptionEditor,
        addons: {
            images: {
                deleteScript: $('#postText').attr('delete'), // (string) A relative path to a delete script
                fileUploadOptions: { // (object) File upload configuration. See https://github.com/blueimp/jQuery-File-Upload/wiki/Options
                    url: $('#postText').attr('path'),
                    acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i, // (regexp) Regexp of accepted file types
                    maxFileSize: 5000000, // 5MB
                    formData: {
                        uuid: $('#formDebateUpdate').attr('uuid'),
                        type: $('#formDebateUpdate').attr('type')
                    },
                    // native jquery resize not supported cf https://github.com/orthes/medium-editor-insert-plugin/issues/288
                    // disableImageResize: false,
                },
                uploadCompleted: function (el, data) {
                    console.log('uploadCompleted');
                    console.log(el);
                    console.log(data);
                },
                uploadFailed: function (uploadErrors, data) {
                    console.log('uploadFailed');
                    console.log(uploadErrors);
                    console.log(data);

                    $('#infoBoxHolder .boxError .notifBoxText').html(uploadErrors);
                    $('#infoBoxHolder .boxError').show();
                }
            }
        }
    });
});
