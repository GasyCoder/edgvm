@component('mail::message')
# Nouveau message de contact – EDGVM

Un nouveau message a été envoyé depuis le formulaire de contact du site de l’École Doctorale EDGVM.

@component('mail::panel')
**Nom :** {{ $data['full_name'] }}  
**E-mail :** {{ $data['email'] }}  

@isset($data['phone'])
**Téléphone :** {{ $data['phone'] }}  
@endisset

**Objet :** {{ $data['subject_label'] }}
@endcomponent

@component('mail::panel')
**Message :**  

{{ $data['message'] }}
@endcomponent

@component('mail::subcopy')
Ce message vous est transmis automatiquement par la plateforme numérique de l’EDGVM.
@endcomponent
@endcomponent
