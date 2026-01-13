<?php 

namespace App\Profile\Blocks;

use App\Html\HtmlDate;
use App\Models\User;
use App\Profile\ProfileBlockBase;

class BirthdateBlock extends ProfileBlockBase
{
    
    protected $data;
    protected $user;
    protected $profile;
    
    // Здесь можно добавить методы и свойства, специфичные для блока ФИО
    static public function code() 
    {
        return 'Birthdate';
    }

    static public function title() 
    {
        return 'Дата рождения';
    }

    static public function description() 
    {
        return 'Блок для отображения и редактирования даты рождения пользователя';
    }
    
    public function read($profile)
    {
        // Логика для чтения данных даты рождения из профиля
        $this->profile = $profile;
        $this->user = (new \App\Models\User())->find($profile['user_id']); // Assuming profile has a user_id property
        $this->data = [
            'birthdate' => $this->user['birthdate'] ?? '',
        ];
        return true;
    }


    public function view()
    {
        $html = '';
        $html .= 'Дата рождения: ' . ($this->data['birthdate'] ?? 'Не указано') . '<br>';
        
        return '<div class="' . static::code() . '-block">' . $html . '</div>';
    }

    /**
     * Метод для обработки данных блока профиля
     */
    public function handle() {
        $birthdate = null;
        if (isset($this->loadedData['birthdate']) && !empty($this->loadedData['birthdate'])) {
            $birthdate = trim($this->loadedData['birthdate']);
        } 
        if (empty($this->errors)) {
            // Если ошибок нет, можно сохранить данные в профиль
            (new User())->update(
                $this->user['id'], 
                [
                    'birthdate' => $birthdate,
                ]
            ); 
        }
        // Обновляем профиль, чтобы отметить, что блок день рождения был изменен
        (new \App\Models\Profile())->update(
            $this->profile['id'], 
            [
                'birthdate_block' => empty($birthdate) ? 0 : 1,
            ]
        );
    }

    public function properties(): array
    {
        // Возвращает свойства блока ФИО
        return [
            [
                'name' => 'birthdate',
                'label' => 'Дата рождения',
                'html' => HtmlDate::class,
                'form_asset' => function() {
                    return [
                        'placeholder' => 'Введите дату рождения',
                    ];
                }
            ], 



        ];
    }
    
}