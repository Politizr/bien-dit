// beta

$(document).ready(function(){ $("#subForm").validate(); }); 

jQuery.extend(jQuery.validator.messages, {
    required: "Ce champ est obligatoire.",
    email: "Merci de renseigner une adresse email valide."
});
