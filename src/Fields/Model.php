<?php

namespace Despark\Cms\Fields;

class Model extends Field
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

        return; 
        error_reporting(E_ALL);
        ini_set("display_errors", 1);
        $baby = new \Despark\Model\Baby;
        $baby = $baby->findOrFail("21711");

    	return $this->entityManager->getForm("baby");
        //dd($this);
    }    
}
