<div class="form-group {{ $errors->has($field->getFieldName()) ? 'has-error' : '' }}">
    {!! Form::label($field->getFieldName(), $field->getLabel()) !!}
    <?php 
        
        $rating = floor($field->getValue()); 
        $string = str_repeat('&#9733;', $rating);
        $string .= str_repeat('&#9734;', 5 - $rating);
    ?>        

    <p style="font-size: 22px">
        {!! $string  !!}
    </p>
    @if($field->getHelp())
        <p class="help-text">
            {{$field->getHelp()}}
        </p>
    @endif
    <div class="text-red">
        {{ join($errors->get($field->getFieldName()), '<br />') }}
    </div>
</div>
