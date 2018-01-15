<div class="form-group {{ $errors->has($field->getFieldName()) ? 'has-error' : '' }}">
    {!! Form::label($field->getFieldName(), $field->getLabel()) !!}
    <?php 
        
        $function = $field->getOptions('functionToCall');
        $model = $field->parent;

        $string = $model->{$function}();
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
