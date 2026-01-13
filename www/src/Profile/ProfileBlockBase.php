<?php 

namespace App\Profile;

// Базовый класс для блоков профиля
abstract class ProfileBlockBase
{
    protected $loadedData;

    protected $errors = [];


    abstract static public function code();


    abstract static public function title();
    

    abstract static public function description();
    
    abstract public function read($profile);

    
    public function render()
    {
        $props = $this->properties();
        $html = '';
        foreach ($props as $prop) {

            $html .= "<div class=\"mb-3 row\">";
                $html .= "<label  class=\"col-sm-3 col-form-label\">{$prop['label']}</label>";
                $html .= "<div class=\"col-sm-9\">";
                    $html .= (new $prop['html']($prop['name'], $this->data[$prop['name']] ?? '', $prop['form_asset']()))->render();       
                $html .= "</div>";
            $html .= "</div>";
        }
        return '<div class="'.static::code().'-block">' . $html . '</div>';
    }

    public function load($data)
    {
        // Метод для загрузки данных в блок профиля
        $this->loadedData = $data;
        return $this;
    }

    /**
     * Метод для получения свойств блока профиля
     */
    abstract public function properties(): array;
    
    /**
     * Метод для обработки данных блока профиля
     */
    abstract public function handle();

    /**
     * Метод для получения ошибок после обработки блока профиля
     */
    public function errors()
    {
        return $this->errors; 
    }

}
