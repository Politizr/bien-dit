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
        text: 'Texte de votre publication.'
    },
    anchor: {
        placeholderText: 'Saisissez une adresse internet'
    },
    anchorPreview: false,
    autoLink:true
});

// cf. https://github.com/orthes/medium-editor-insert-plugin/wiki/v2.x-Configuration
$(function () {
    $('.editable.description').mediumInsert({
        editor: descriptionEditor,
        addons: {
            images: {
                preview: false,
                deleteScript: $('#postText').attr('delete'),
                fileUploadOptions: { // (object) File upload configuration. See https://github.com/blueimp/jQuery-File-Upload/wiki/Options
                    url: $('#postText').attr('path'),
                    acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i, // (regexp) Regexp of accepted file types
                    maxFileSize: 5000000, // 5MB
                    formData: {
                        uuid: uuid,
                        type: type
                    }
                    // native jquery resize not supported cf https://github.com/orthes/medium-editor-insert-plugin/issues/288
                    // disableImageResize: false,
                },
                styles: {
                    wide: {
                        label: '<span class="fa fa-align-justify"></span>',
                        added: function (el) {
                            triggerSaveDocument();
                        },
                        removed: function (el) {
                            triggerSaveDocument();
                        }
                    },
                    left: {
                        label: '<span class="fa fa-align-left"></span>',
                        added: function (el) {
                            triggerSaveDocument();
                        },
                        removed: function (el) {
                            triggerSaveDocument();
                        }
                    },
                    right: {
                        label: '<span class="fa fa-align-right"></span>',
                        added: function (el) {
                            triggerSaveDocument();
                        },
                        removed: function (el) {
                            triggerSaveDocument();
                        }
                    },
                    grid: {
                        label: '<span class="fa fa-th"></span>',
                        added: function (el) {
                            triggerSaveDocument();
                        },
                        removed: function (el) {
                            triggerSaveDocument();
                        }
                    }
                },
                captions: true,
                captionPlaceholder: 'Légende de l\'image',
                actions: {
                    remove: {
                        label: '<span class="fa fa-times"></span>',
                        clicked: function (el) {
                            // console.log('actions.remove.clicked');

                            var $event = $.Event('keydown');
                            $event.which = 8;
                            $(document).trigger($event);

                            el.remove();
                            triggerSaveDocument();
                        }
                    }
                },
                messages: {
                    acceptFileTypesError: 'Ce format de fichier n\'est pas autorisé: ',
                    maxFileSizeError: 'Ce fichier est trop gros: '
                },
                uploadCompleted: function (el, data) {
                    // console.log('uploadCompleted');
                    // console.log(el);
                    // console.log(data);
                    // console.log(data.result);

                    if ('error' in data.result) {
                        // console.log('Error occurs');
                        el.closest(".medium-insert-images").remove();

                        $('#infoBoxHolder .boxError .notifBoxText').html(data.result['error']);
                        $('#infoBoxHolder .boxError').show();
                    } else {
                        el.find("img").attr('uuid', data.result['media_uuid']);
                        triggerSaveDocument();
                    }
                },
                uploadFailed: function (uploadErrors, data) {
                    // console.log('uploadFailed');
                    // console.log(uploadErrors);
                    // console.log(data);

                    $('#infoBoxHolder .boxError .notifBoxText').html(uploadErrors);
                    $('#infoBoxHolder .boxError').show();
                }
            },
            embeds: {
                label: '<span class="fa fa-youtube-play"></span>',
                placeholder: 'Copiez-collez ici un lien d\'une vidéo issu de YouTube, Vimeo, Facebook, Twitter ou Instagram et appuyez sur "Entrée"',
                captions: true,
                captionPlaceholder: 'Légende',
                oembedProxy: null,
                styles: {
                    wide: {
                        label: '<span class="fa fa-align-justify"></span>',
                        added: function ($el) {
                            triggerSaveDocument();
                        },
                        removed: function ($el) {
                            triggerSaveDocument();
                        }
                    },
                    left: {
                        label: '<span class="fa fa-align-left"></span>'
                    },
                    right: {
                        label: '<span class="fa fa-align-right"></span>'
                    }
                },
                actions: {
                    remove: {
                        label: '<span class="fa fa-times"></span>',
                        clicked: function ($el) {
                            var $event = $.Event('keydown');
                            
                            $event.which = 8;
                            $(document).trigger($event);   
                            
                            triggerSaveDocument();
                        }
                    }
                }
            }
        }
    });
});
