<?php 

namespace App\Html;

class HtmlCheckboxList
{
    protected $name;
    protected $value;
    protected $formAsset = [];

    public function __construct($name, $value = [], $formAsset)
    {
        $this->name = $name;
        $this->value = $value;
        $this->formAsset = $formAsset;
    }

    public function render()
    {
        $checkboxes = '';
        foreach ($this->formAsset['items'] as $item) {
            $checked = in_array($item['id'], (array)$this->value) ? ' checked' : '';
            $checkboxes .= "<div class=\"form-check\">
                <input type=\"checkbox\" 
                    name=\"{$this->name}[]\" 
                    value=\"{$item['id']}\" 
                    id=\"{$this->name}_{$item['id']}\" 
                    class=\"form-check-input\"{$checked}>
                <label class=\"form-check-label\" for=\"{$this->name}_{$item['id']}\">{$item['name']}</label>
            </div>";
        }
        return $checkboxes;
    }
}