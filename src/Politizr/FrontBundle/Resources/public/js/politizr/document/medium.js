// beta

// description
var descriptionEditor = new MediumEditor('.editable.description', {
    toolbar: {
        buttons: ['bold', 'italic', 'header1', 'header2', 'unorderedlist', 'quote', 'anchor'],
        buttonLabels: {
            'bold': '<i class="icon-bold"></i>',
            'italic': '<i class="icon-italic"></i>',
            'header1': '<i class="icon-h1"></i>',
            'header2': '<i class="icon-h2"></i>',
            'unorderedlist': '<i class="icon-timeline"></i>',
            'quote': '<i class="icon-quote"></i>',
            'anchor': '<i class="icon-link"></i>'
        },
        placeholder: {
            text: 'Cliquez ici pour saisir le texte de votre publication.'
        },
        anchor: {
            placeholderText: 'Saississez une adresse internet'
        },
        anchorPreview: false,
        firstHeader:'h1',
        secondHeader:'h2',
        autoLink: true
    }
});
