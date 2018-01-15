<?php 
    //dd($field->getFieldName());     
    $values = $field->getValue(); 

?>
<div class="form-group {{ $errors->has($elementName) ? 'has-error' : '' }}" id="cloud_{{ $field->getFieldName() }}">
    {!! Form::label($elementName, $field->getLabel()) !!}
    {!! Form::text($elementName, "", $field->getAttributes()) !!}
    <ul class="cloud" id="">
    @if($values)
    @foreach( $values as $point )
        <li>
            <a href="#">{{  $point-> name }}</a>
            {!! Form::hidden("title", $point->id, $field->getChildrenAttributes($loop->iteration, "id", Array(), $field->getFieldName() )) !!}            

            {!! Form::hidden("title", $point->name, $field->getChildrenAttributes($loop->iteration, "name", Array(), $field->getFieldName() )) !!}
        </li>
    @endforeach
    @endif
    </ul>   

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
<script type="text/javascript">
    $("#cloud_{{ $field->getFieldName() }}").keydown(function(e){
        if(e.keyCode == 13)
        {

            var val = $(this).find("input[type=text]");
            var list = $(this).find("ul.cloud");
            var next_node = "{{ $field->getFieldName() }}";
            var next_node = next_node + "[" + (list.find("li").size() + 1) + "][name]";


            tag = jQuery('<li>', {
            });
            link = jQuery('<a>', {
                href: '#',
                title: val.val(),
                text: val.val()
            });
            name_input = jQuery('<input>', {
                type: 'hidden',
                name:  next_node,
                value: val.val()
            });


            tag.append(link);
            tag.append(name_input);

            list.append(tag);
            
            //list.append("<li><a href='#'>" + val.val() + "</a></li>");
            val.val("");
            return false;
        }
    });

    $(".cloud li").click(function() {
            $(this).remove();
    });
</script>
@endpush
