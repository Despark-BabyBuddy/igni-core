<?php

namespace Despark\Cms\Fields;

use Despark\Helpers\Facund;

class Nested extends Field
{
	public $parent;

    public function __construct($fieldName, array $options, $value = null)
    {
        $options['attributes']['placeholder'] = $options['label'];
        $options['attributes']['id'] = $fieldName;
        parent::__construct($fieldName, $options, $value);
    }

    public function afterRender() 
    {   

        $model = $this->parent;
        /*
        $model->name = "asdasd";
        $model->save();
        dd($model);
        */
        $nested_parent = $model->nested_parent();
        if($nested_parent) {
            $attribute = $this->options['title'];
            $this->value = $nested_parent->$attribute;
            $this->nest_id = $nested_parent->id;
        } else $this->value = '';

    }

    function getSelectOptions() {return array();}

}
