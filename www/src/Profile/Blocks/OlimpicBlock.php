<?php 

namespace App\Profile\Blocks;

use App\Html\HtmlSelect;
use App\Profile\ProfileBlockBase;
use App\Models\OlimpicPredmet;
use App\Models\OlimpicUser;
use Exception;

// Блок который собирает информацию о достижениях олимпийцев

class OlimpicBlock extends ProfileBlockBase
{
    // Здесь можно добавить методы и свойства, специфичные для блока олимпийских достижений
    protected $data;
    protected $user;
    protected $profile;
    
    // Здесь можно добавить методы и свойства, специфичные для блока ФИО
    static public function code() 
    {
        return 'olimpic';
    }

    static public function title() 
    {
        return 'Олимпиада';
    }

    static public function description() 
    {
        return 'Блок для отображения и редактирования олимпиаду пользователя';
    }

    public function read($profile)
    {
        // Логика для чтения данных даты рождения из профиля
        $this->profile = $profile;
        $olimpic_user = (new \App\Models\OlimpicUser())->findWhere(
            ['user_id' => $profile['user_id']]);
        $this ->user = [
        'id' => $profile['user_id'],
        'predmet_id' => '',
        'olimpic_user_id' => null,
        ];
        $this->data = [];
        if (!empty($olimpic_user)) {
            $this->user['predmet_id'] = reset($olimpic_user)['olimpic_predmet_id'];
            $this->user['olimpic_user_id'] = reset($olimpic_user)['id'];
            $this->data = (new \App\Models\OlimpicPredmet())->findWhere(
                ['id' => reset($olimpic_user)['olimpic_predmet_id']]
            );
        }
        return true;
    }


    public function view()
    {
        if (!empty($this->user['predmet_id'])) {
            $this->data = (new \App\Models\OlimpicPredmet())->findWhere(
                ['id' => $this->user['predmet_id']]
            );
        }
        // echo $this->user['predmet_id'];
        
        $html = '';
        $html .= 'Олимпиада: ' . (reset($this->data)['name'] ?? 'Не указано') . '<br>';
        
        return '<div class="' . static::code() . '-block">' . $html . '</div>';
    }

    /**
     * Метод для обработки данных блока профиля
     */
    public function handle() {
        $olimpic_predmet = null;
        if (isset($this->loadedData['olimpic']) && !empty($this->loadedData['olimpic'])) {
            $olimpic_predmet = trim($this->loadedData['olimpic']);
        } else {
            return;
        }
        if (empty($this->errors)) {
            echo $olimpic_predmet;
            if (empty($this->data)) {
                (new \App\Models\OlimpicUser)->insert([
                    'user_id' => $this->user['id'],
                    'olimpic_predmet_id' => $olimpic_predmet
                ]);
            }
            else {
                (new \App\Models\OlimpicUser)->update(
                    $this->user['olimpic_user_id'],
                    [
                        'olimpic_predmet_id' => $olimpic_predmet
                    ]
                );
            }
        }
        (new \App\Models\Profile())->update(
            $this->profile['id'], 
            [
                'olimpic_block' => empty($olimpic_predmet) ? 0 : 1,
            ]
        );
    }

    public function properties(): array
    {
        $predmets = (new OlimpicPredmet) -> all();
        $items = [];
        foreach($predmets as $item) {
            $items[$item['id']] = $item['name'];
        }
        return [
            [
                'name' => 'olimpic',
                'label' => 'Олимпиада',
                'html' => HtmlSelect::class,
                'form_asset' => function() use ($items) {
                    return [
                        'items' => $items,
                        'placeholder' => 'Выберите олимпиаду',
                    ];
                }
            ],
        ];
    }
    
}