<?php 

namespace App\Profile\Blocks;

use App\Html\HtmlInput;
use App\Html\HtmlEmail;
use App\Html\HtmlSelect;
use App\Models\User;
use App\Profile\ProfileBlockBase;

// Базовый класс для блоков профиля

class ClassBlock extends ProfileBlockBase
{
    protected $data;
    protected $user;
    protected $profile;
    
    // Здесь можно добавить методы и свойства, специфичные для блока ФИО
    static public function code() 
    {
        return 'class';
    }

    static public function title() 
    {
        return 'Класс';
    }

    static public function description() 
    {
        return 'Блок для отображения и редактирования Класса пользователя';
    }
    
    public function read($profile)
    {
        // Логика для чтения данных ФИО из профиля
        // return $profile->fio ?? '';
        $this->profile = $profile;
        $this->user = (new \App\Models\User())->find($profile['user_id']); // Assuming profile has a user_id property
        $this->data = [
            'class' => $this->user['class'] ?? '',
        ];
        return true;
    }

    public function view()
    {
        $props = $this->properties(); 
        $html = ''; 
        $html .= 'Класс: ' . ($this->data['class'] ?? 'Не указано') . '<br>'; 
        return '<div class="' . static::code() . '-block">' . $html . '</div>'; 
    }

    /**
     * Метод для обработки данных блока профиля
     */
    public function handle() {
        if (isset($this->loadedData['class'])) {
            $class = trim($this->loadedData['class']);
        } 
        if (empty($this->errors)) {
            // Если ошибок нет, можно сохранить данные в профиль
            (new User())->update(
                $this->user['id'], 
                [
                    'class' => $class,
                ]
            ); // Assuming update method exists
        }
    }

    public function properties(): array
    {
        // Возвращает свойства блока ФИО
        return [
            [
                'name' => 'class',
                'label' => 'Класс',
                'html' => HtmlSelect::class,
                'form_asset' => function() {
                    return [
                        'items' => [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9, 10 => 10],
                        'placeholder' => 'Выберите класс',
                    ];
                }
            ], 
        ];
    }
    
}