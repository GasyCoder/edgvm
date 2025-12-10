@component('mail::message')
# Merci de nous avoir contactés

Bonjour {{ $data['full_name'] }},

Nous vous remercions d’avoir contacté l’École Doctorale EDGVM.  
Votre message a bien été reçu et sera traité dans les meilleurs délais.

@component('mail::panel')
**Récapitulatif de votre demande**

**Objet :** {{ $data['subject_label'] }}

**Message :**  
{{ $data['message'] }}
@endcomponent

Nous vous répondrons à l’adresse suivante : **{{ $data['email'] }}**.  
Si vous avez d’autres éléments à ajouter, vous pourrez les communiquer lors de notre réponse.

Cordialement,  
L’équipe de l’École Doctorale **EDGVM**

@component('mail::subcopy')
Ceci est un message automatique, merci de ne pas y répondre directement.
@endcomponent
@endcomponent
