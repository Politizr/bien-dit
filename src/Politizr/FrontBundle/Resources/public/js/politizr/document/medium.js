// description
var descriptionEditor = new MediumEditor('.editable.description', {    
    buttons: ['bold', 'italic', 'header1', 'header2', 'unorderedlist', 'quote', 'anchor'],
    buttonLabels: {
        'bold': '<span style=\"font-family: \'Merriweather Bold\'\">B</span>',
        'italic': '<span style=\"font-family: \'Merriweather Bold Italic\'\">i</span>',
        'header1': '<span style=\"font-family: \'Merriweather\'\">H1</span>',
        'header2': '<span style=\"font-family: \'Merriweather\'\">H2</span>',
        'unorderedlist': '<i class="iconTimeline"></i>',
        'quote': '<i class="iconQuote"></i>',
        'anchor': '<i class="iconLink"></i>'
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
});     

// copyright
var copyrightEditor = new MediumEditor('.editable.copyright', {
    buttons: ['bold', 'italic', 'anchor'],
    buttonLabels: {
        'bold': '<span style=\"font-family: \'Merriweather Bold\'\">B</span>',
        'italic': '<span style=\"font-family: \'Merriweather Bold Italic\'\">i</span>',
        'anchor': '<i class="iconLink"></i>'
    },
    placeholder: {
        text: 'Crédits photo'
    },
    anchor: {
        placeholderText: 'Saississez une adresse internet'
    },
    anchorPreview: false,
    firstHeader:'h1',
    secondHeader:'h2',
    autoLink: true
});

// subtitle
var subtitleEditor = new MediumEditor('.editable.subtitle', {
    buttons: ['bold', 'anchor'],
    buttonLabels: {
        'bold': '<span style=\"font-family: \'Merriweather Bold\'\">B</span>',
        'anchor': '<i class="iconLink"></i>'
    },
    placeholder: {
        text: 'Cliquez ici pour saisir votre texte de présentation en quelques mots'
    },
    anchor: {
        placeholderText: 'Saississez une adresse internet'
    },
    anchorPreview: false,
    autoLink: true
});

// biography
var biographyEditor = new MediumEditor('.editable.biography', {    
    buttons: ['bold', 'italic', 'header1', 'header2', 'unorderedlist', 'quote', 'anchor'],
    buttonLabels: {
        'bold': '<span style=\"font-family: \'Merriweather Bold\'\">B</span>',
        'italic': '<span style=\"font-family: \'Merriweather Bold Italic\'\">i</span>',
        'header1': '<span style=\"font-family: \'Merriweather\'\">H1</span>',
        'header2': '<span style=\"font-family: \'Merriweather\'\">H2</span>',
        'unorderedlist': '<i class="iconTimeline"></i>',
        'quote': '<i class="iconQuote"></i>',
        'anchor': '<i class="iconLink"></i>'
    },
    placeholder: {
        text: 'Cliquez ici pour saisir le texte de votre biographie.'
    },
    anchor: {
        placeholderText: 'Saississez une adresse internet'
    },
    anchorPreview: false,
    firstHeader:'h1',
    secondHeader:'h2',
    autoLink: true
});     

