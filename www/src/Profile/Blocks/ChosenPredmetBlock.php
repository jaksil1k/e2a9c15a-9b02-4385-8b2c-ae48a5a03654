<?php 

namespace App\Profile\Blocks;

use App\Html\HtmlSelect;
use App\Models\ChosenPredmet;
use App\Models\ChosenPredmetUser;
use App\Models\User;
use App\Profile\ProfileBlockBase;
use Exception;

// Блок, который собирает информацию о выбранном предмете
// Этот блок может быть использован для отображения или редактирования информации о предмете, который был выбран пользователем
// четвертный предмет
class ChosenPredmetBlock extends ProfileBlockBase
{
        
    protected $data;
    protected $user;
    protected $profile;
    
    // Здесь можно добавить методы и свойства, специфичные для блока ФИО
    static public function code() 
    {
        return 'chosen_predmet';
    }

    static public function title() 
    {
        return 'Выбранный предмет';
    }

    static public function description() 
    {
        return 'Блок для отображения и редактирования выбранных предметов пользователя';
    }
    
    public function read($profile)
    {
        // Логика для чтения данных даты рождения из профиля
        $this->profile = $profile;
        $chosen_predmet_user = (new \App\Models\ChosenPredmetUser())->findWhere(
            ['user_id' => $profile['user_id']]);
        $this->data = [];
        if (!empty($chosen_predmet_user)) {
            $this ->user = [
            'id' => $profile['user_id'],
            'predmet_id' => reset($chosen_predmet_user)['chosen_predmet_id'],
            'predmet_user_id' => reset($chosen_predmet_user)['id']
            ];
            $this->data = (new \App\Models\ChosenPredmet())->findWhere(
                ['id' => reset($chosen_predmet_user)['chosen_predmet_id']]
            );
        }
        return true;
    }


    public function view()
    {
        if (!empty($this->user)) {
            $this->data = (new \App\Models\ChosenPredmet())->findWhere(
                ['id' => $this->user['predmet_id']]
            );
        }
        $html = '';
        $html .= 'Выбранный предмет: ' . (reset($this->data)['name'] ?? 'Не указано') . '<br>';
        
        return '<div class="' . static::code() . '-block">' . $html . '</div>';
    }

    /**
     * Метод для обработки данных блока профиля
     */
    public function handle() {
        $chosen_predmet = null;
        if (isset($this->loadedData['chosen_predmet']) && !empty($this->loadedData['chosen_predmet'])) {
            $chosen_predmet = trim($this->loadedData['chosen_predmet']);
        } else {
            return;
        }
        if (empty($this->user['id'])) {
            
            $this->user['id'] = $this->profile['user_id'];
        }
        if (empty($this->errors)) {
            
            if (empty($this->data)) {
                (new \App\Models\ChosenPredmetUser)->insert([
                    'user_id' => $this->user['id'],
                    'chosen_predmet_id' => $chosen_predmet
                ]);
            }
            else {
                (new \App\Models\ChosenPredmetUser)->update(
                    $this->user['predmet_user_id'],
                    [
                        'chosen_predmet_id' => $chosen_predmet
                    ]
                );
            }
        }
        (new \App\Models\Profile())->update(
            $this->profile['id'], 
            [
                'chosen_predmet_block' => empty($chosen_predmet) ? 0 : 1,
            ]
        );
    }

    public function properties(): array
    {
        $predmets = (new ChosenPredmet) -> all();
        $items = [];
        foreach($predmets as $item) {
            $items[$item['id']] = $item['name'];
        }
        return [
            [
                'name' => 'chosen_predmet',
                'label' => 'Выбранный предмет',
                'html' => HtmlSelect::class,
                'form_asset' => function() use ($items) {
                    return [
                        'items' => $items,
                        'placeholder' => 'Выберите предмет',
                    ];
                }
            ],
        ];
    }
}