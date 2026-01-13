<?php 

namespace App\Html;

class HtmlDate
{
    protected $name;
    protected $value;
    protected $formAsset = [];

    /*
     'form_asset' => function() {
                    return [
                        'placeholder' => 'Введите Имя',
                    ];
                }
    */

    public function __construct($name, $value = '', $formAsset)
    {
        $this->name = $name;
        $this->value = $value;
        $this->formAsset = $formAsset;
    }

    public function render()
    {
        return "<input type=\"text\" 
            name=\"{$this->name}\" 
            value=\"{$this->value}\"  
            placeholder=\"{$this->formAsset['placeholder']}\"  
            class=\"form-control datepicker\" 
            />";
    }
}