<?php 

namespace App\Html;

class HtmlSelect
{
    protected $name;
    protected $value;
    protected $formAsset = [];

    /*
     'form_asset' => function() {
                    return [
                        'items' => [1 => 'Item 1', 2 => 'Item 2'],
                        'placeholder' => 'Выберите элемент',
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
        return "<select name=\"{$this->name}\" 
                class=\"form-control\"  >" . 
               "<option value=\"\">{$this->formAsset['placeholder']}</option>" .
               $this->renderOptions() .
               "</select>";
    }

    protected function renderOptions()
    {
        $options = '';
        foreach ($this->formAsset['items'] as $id => $item) {
            $selected = ($id == $this->value) ? ' selected' : '';
            $options .= "<option value=\"{$id}\"{$selected}>{$item}</option>";
        }
        return $options;
    }

}
