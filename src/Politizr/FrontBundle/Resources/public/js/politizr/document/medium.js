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
