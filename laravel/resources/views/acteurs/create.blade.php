


{{ Form::open(array('url' => '/acteurs/store')) }}
    
{{ Form::label('prénom :') }}
{{ Form::input('text', 'prenom') }}
<br/>
{{ Form::label('nom :') }}
{{ Form::input('text', 'nom') }}
<br/>
{{ Form::label('date embauche:') }}
{{ Form::input('date', 'date_embauche') }}
<br/>
{{ Form::label('date cessation emploi:') }}
{{ Form::input('date', 'date_cessation_emploi') }}
<br/>
{{ Form::label('actif :') }}
{{ Form::input('text', 'actif') }}

{{ Form::submit('Enregistrer') }}

{{ Form::close() }}