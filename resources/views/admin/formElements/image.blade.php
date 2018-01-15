<?php

    $options = $field->getOptions();
    $fieldname = $options['field'];
    $avatar = $field->getModel()->avatar_url();
    $original = $field->getModel()->avatar_url();
    $filename = $field->getModel()->{$fieldname}; 

?>
{{-- Image --}}
<div class="form-group {{ $errors->has($fieldName) ? 'has-error' : '' }}">
    {!! Form::label($elementName, $options['label']) !!}
    <br>
    @if ($field->getValue())
        <a href="{{ $original }}">
            <img src="{{ $avatar  }}">
        </a>
        <br>
    @endif
    {!! Form::file($elementName,  [
        'id' => $elementName,
        'class' => "form-control",
        'placeholder' => $options['label'],
    ] ) !!}

    <div>{{ $options['help'] or '' }}</div>

</div>
