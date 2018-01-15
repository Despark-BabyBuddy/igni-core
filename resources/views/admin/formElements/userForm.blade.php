{{-- TODO action verb --}}
{!!  Form::open([
    'url' => $form->getAction(),
    'method' => $form->getMethod(),
    'role' => $form->getRole(),
    'enctype'=> $form->getEnctype() ?? 'multipart/form-data', ]
) !!}

<?php 
	//$baby_id = $form->getFields()[0]->getOptions()['parent']->baby->id; //Duuuuuh! 

	//dd($baby_id->roles());
	// If you are having troubles with the view that means the users doesnt have
	//	a baby, which should be a MAJOR db inconsistency !
?>


{!! $form->renderFields() !!} 


<button type="submit" class="btn btn-primary">Save</button>

<?php $resourceConfig = $controller->getResourceConfig() ?>
@if(isset($resourceConfig['parentModel']) AND request()->has($resourceConfig['parentModel']['foreignKey']))
   <a href="{{ route($resourceConfig['id'].'.index').'?'.$resourceConfig['parentModel']['foreignKey'].'='.request()->query($resourceConfig['parentModel']['foreignKey']) }}" class="back-to-filtered-listing">Back to listing</a> 
@endif
{{-- {!! $record->adminPreviewButton() !!} --}}
{!! Form::close() !!}