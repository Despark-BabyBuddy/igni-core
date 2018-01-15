<div class="form-group {{ $errors->has($fieldName) ? 'has-error' : '' }}">
    {!! Form::label($elementName, $field->getOptions('label')) !!}
    {!! Form::textarea($elementName, $field->getValue(), [
        'id' =>  $elementName,
        'class' => "form-control wysiwyg",
        'placeholder' => $field->getOptions('label'),
    ] ) !!}
    <div class="text-red">
        {{ join($errors->get($fieldName), '<br />') }}
    </div>
</div>

@push('additionalScripts')
<script src="{{ asset('/admin_assets/plugins/tinymce/tinymce.min.js') }}"></script>

    @include('ignicms::admin.formElements.wysiwygscripts');
@endpush
