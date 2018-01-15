<?php 
    $className = $field->getSourceModel(); 
    $sourceModel = new $className; 
?>


<div class="form-group {{ $errors->has($elementName) ? 'has-error' : '' }} nested_parent">
    {!! Form::label($elementName, $field->getLabel()) !!}
        <a class="form-control " href='{{ URL::to('admin/videocategories/' . $field->getOptions('nest_id') . '/edit') }}'>
            {{ $field->getValue() }}
        </a>
       {!! Form::select($field->getFieldName(), $sourceModel->toOptionsArray(), $field->getValue(), $field->getAttributes()) !!}

       {{ Form::button('Remove parent', array('class' => 'form-control btn btn-primary remove-parent')) }}

    @if($field->getHelp())
        <p class="help-text">
            {{$field->getHelp()}}
        </p>
    @endif
    <div class="text-red">
        {{ join($errors->get($elementName), '<br />') }}
    </div>
</div>

@push('additionalScripts')
<script>
    @if(!$field->getValue())
        $(document).ready(function() {
            $(".nested_parent .form-control").toggleClass("hidden");

        });
    @endif
    $("#parent_id").val({{ $field->getOptions('nest_id') }});

    $(".remove-parent").click(function() {
         $(".nested_parent .form-control").toggleClass("hidden");
    });

    var config = {
        tags: true,
        containerCssClass: 'form-control hidden',
        width: '100%',
        placeholder: '{!! $field->getOptions('attributes')['placeholder'] !!}',
        createTag: function (params) {
            var term = $.trim(params.term);

            if (term === '') {
                return null;
            }
            
            return {
                id: term,
                text: term,
                newTag: true // add additional parameters
            }
        },
    };
    $('#{{$field->getAttributes()['id']}}').select2(config);
</script>
@endpush
