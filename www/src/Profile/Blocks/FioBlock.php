<?php 

namespace App\Profile\Blocks;

use App\Html\HtmlInput;
use App\Html\HtmlEmail;
use App\Html\HtmlSelect;
use App\Models\User;
use App\Profile\ProfileBlockBase;

// Базовый класс для блоков профиля

class FioBlock extends ProfileBlockBase
{
    protected $data;
    protected $user;
    protected $profile;
    
    // Здесь можно добавить методы и свойства, специфичные для блока ФИО
    static public function code() 
    {
        return 'fio';
    }

    static public function title() 
    {
        return 'ФИО';
    }

    static public function description() 
    {
        return 'Блок для отображения и редактирования ФИО пользователя';
    }
    
    public function read($profile)
    {
        // Логика для чтения данных ФИО из профиля
        // return $profile->fio ?? '';
        $this->profile = $profile;
        $this->user = (new \App\Models\User())->find($profile['user_id']); // Assuming profile has a user_id property
        $this->data = [
            'surname' => $this->user['surname'] ?? '',
            'name' => $this->user['name'] ?? '',
            'lastname' => $this->user['lastname'] ?? '',
        ];
        return true;
    }


    public function view()
    {
        $props = $this->properties();
        $html = '';
        $html .= 'Фамилия: ' . ($this->data['surname'] ?? 'Не указано') . '<br>';
        $html .= 'Имя: ' . ($this->data['name'] ?? 'Не указано') . '<br>';
        $html .= 'Отчество: ' . ($this->data['lastname'] ?? 'Не указано') . '<br>';
        // Здесь можно добавить другие поля
        
        return '<div class="' . static::code() . '-block">' . $html . '</div>';
    }

    /**
     * Метод для обработки данных блока профиля
     */
    public function handle() {
        // Логика для обработки данных ФИО
         

        if (isset($this->loadedData['surname'])) {
            $surname = trim($this->loadedData['surname']);
            if (empty($surname)) {
                $this->errors[] = 'Фамилия не может быть пустым';
            } 
        } else {
            $this->errors[] = 'Данные Фамилии не загружены';
        }

        if (isset($this->loadedData['name'])) {
            $name = trim($this->loadedData['name']);
            if (empty($name)) {
                $this->errors[] = 'Имя не может быть пустым';
            } 
        } else {
            $this->errors[] = 'Данные Имени не загружены';
        }

        if (isset($this->loadedData['lastname'])) {
            $lastname = trim($this->loadedData['lastname']);            
        } 

        if (empty($this->errors)) {
            // Если ошибок нет, можно сохранить данные в профиль
            (new User())->update(
                $this->user['id'], 
                [
                    'surname' => $surname,
                    'name' => $name,
                    'lastname' => $lastname,
                    // 'email' => $email
                ]
            ); // Assuming update method exists
        }

        // Обновляем профиль, чтобы отметить, что блок фио был изменен
        (new \App\Models\Profile())->update(
            $this->profile['id'], 
            [
                'fio_block' => empty($surname) && empty($name) ? 0 : 1,
            ]
        );
    }

    public function properties(): array
    {
        // Возвращает свойства блока ФИО
        return [
            [
                'name' => 'surname',
                'label' => 'Фамилия',
                'html' => HtmlInput::class,
                'form_asset' => function() {
                    return [
                        'placeholder' => 'Введите Фамилию',
                    ];
                }
            ], 
            [
                'name' => 'name',
                'label' => 'Имя',
                'html' => HtmlInput::class,
                'form_asset' => function() {
                    return [
                        'placeholder' => 'Введите Имя',
                    ];
                }
            ], 
            [
                'name' => 'lastname',
                'label' => 'Отчество',
                'html' => HtmlInput::class,
                'form_asset' => function() {
                    return [
                        'placeholder' => 'Введите Отчество',
                    ];
                }
            ], 

        ];
    }
    
}