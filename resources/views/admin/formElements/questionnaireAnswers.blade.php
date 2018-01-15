<?php
	$answers = $field->parent->{$field->getOptions('collection')};

?><div class="form-group {{ $errors->has($field->getOptions('validateName')) ? 'has-error' : '' }}">
    <label for="{{ $fieldName }}">{{ $field->getLabel() }}</label>
    <ul class='answers form-group'>
    @foreach($answers as $answer)
    	<li class='form-group'>
    		<span class="title">{{ $answer->type == 'answer' ? "Answer" : "Section header" }}</span><input class="form-control" name="answers[{{ $answer->id }}]" type="text" value="{{ $answer-> name }}">
    		<a href='#' class="delete_answer btn btn-danger">REMOVE</a>
    	</li>
    @endforeach
    </ul>
		<A href="#" class="add_answer answer btn btn-success">add answer</A>
		<A href="#" class="add_answer header btn btn-success">add section header</A>
    	<br>
    
    <div class="text-red">
        {{ join($errors->get($field->getOptions('validateName')), '<br />') }}
    </div>
</div>
<style type="text/css">
	
	.answers li {
		list-style: none;
	}
	.answers input {
		width:60%!important;
		display: inline;
	}

	.delete_answer {
		margin:0px 20px!important;
	}

	.answers .title {
		width:200px;
		display: inline-block;
	} 
</style>
@push('additionalScripts')
    <script type="text/javascript">
        $(".select2").select2({
            placeholder: 'Select {{ $field->getLabel() }}'
        });

        $(".delete_answer").click(function() {
        	$(this).parent().remove();
        });        

        $(".add_answer").click(function() {
        	var r = Math.ceil(Math.random() * 1000);
        	if($(this).hasClass("answer")) 
        		$(".answers").append('<li class="form-group"><span class="title">Answer</span><input class="form-control" name="answers[answer_' + r + '] type="text" value=""><a href="#" class="delete_answer btn btn-danger">REMOVE</a></li>');
        	else
        		$(".answers").append('<li class="form-group"><span class="title">Section header</span><input class="form-control" name="answers[header_' + r + '] type="text" value=""><a href="#" class="delete_answer btn btn-danger">REMOVE</a></li>');

        });

    </script>
@endpush
