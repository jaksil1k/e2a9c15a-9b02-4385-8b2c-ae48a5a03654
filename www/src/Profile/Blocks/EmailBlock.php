<?php 

namespace App\Profile\Blocks;

use App\Html\HtmlEmail;
use App\Models\User;
use App\Profile\ProfileBlockBase;

class EmailBlock extends ProfileBlockBase
{
    protected $data;
    protected $user;
    protected $profile;
    
    // Здесь можно добавить методы и свойства, специфичные для блока ФИО
    static public function code() 
    {
        return 'email';
    }

    static public function title() 
    {
        return 'Электронная почта';
    }

    static public function description() 
    {
        return 'Блок для отображения и редактирования Электронно почты пользователя';
    }
    
    public function read($profile)
    {
        $this->profile = $profile;
        $this->user = (new \App\Models\User())->find($profile['user_id']); // Assuming profile has a user_id property
        $this->data = [
            'email' => $this->user['email'] ?? '',
        ];
        return true;
    }


    public function view()
    {
        $html = '';
        $html .= 'Электронная почта: ' . ($this->data['email'] ?? 'Не указано') . '<br>';
        
        return '<div class="' . static::code() . '-block">' . $html . '</div>';
    }

    /**
     * Метод для обработки данных блока профиля
     */
    public function handle() {
        if (isset($this->loadedData['email'])) {
            $email = trim($this->loadedData['email']);
        } 
        if (empty($this->errors)) {
            // Если ошибок нет, можно сохранить данные в профиль
            (new User())->update(
                $this->user['id'], 
                [
                    'email' => $email,
                ]
            ); // Assuming update method exists
        }
    }

    public function properties(): array
    {
        // Возвращает свойства блока ФИО
        return [
            [
                'name' => 'email',
                'label' => 'Эл. почта',
                'html' => HtmlEmail::class,
                'form_asset' => function() {
                    return [
                        'placeholder' => 'Введите почту',
                    ];
                }
            ], 
        ];
    }
    
}