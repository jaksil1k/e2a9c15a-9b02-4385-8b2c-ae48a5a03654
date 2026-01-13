<?php 

namespace App\Profile\Blocks;

use App\Html\HtmlCheckboxList;
use App\Profile\ProfileBlockBase;
use App\Models\SportItem;
use App\Models\SportUser;

// Блок, который собирает информацию о спорте
class SportBlock extends ProfileBlockBase
{
    // Здесь можно добавить методы и свойства, специфичные для блока спорта
    protected $data;
    protected $user;
    protected $profile;
    
    static public function code() 
    {
        return 'sport';
    }

    static public function title() 
    {
        return 'Спортивные занятия';
    }

    static public function description() 
    {
        return 'Блок для отображения и редактирования спортивных занятий пользователя';
    }
    
    public function read($profile)
    {
        // Логика для чтения данных факультативных занятий из профиля
        $this->profile = $profile;
        $sport_users = (new \App\Models\SportUser())->findWhere(
            ['user_id' => $profile['user_id']]); // Assuming profile has a user_id property 
        $this->data = [
            'sport_item_ids' => array_column($sport_users ?? [], 'sport_item_id') ?? [],
        ];
        return true;
    }


    public function view()
    {
        $html = ''; 
        $comma = "";
        $allPredmets = (new SportItem())->all();
        foreach ($allPredmets as $predmet) {
            if (in_array($predmet['id'], $this->data['sport_item_ids'])) {
                $html .= $comma . '<span class="badge bg-primary">' . $predmet['name'] . '</span> ';
                $comma = ", ";
            }
        }
        return '<div class="' . static::code() . '-block">' . $html . '</div>';
    }

    /**
     * Метод для обработки данных блока профиля
     */
    public function handle() {
         
        $sport_item_ids =[];
        if (isset($this->loadedData['sport_item_ids'])) {
            $sport_item_ids = $this->loadedData['sport_item_ids'];
        } 

        if (empty($this->errors)) {
            $insertedIds = array_diff($sport_item_ids, $this->data['sport_item_ids']);
            $deleteIds = array_diff($this->data['sport_item_ids'], $sport_item_ids);
            // Если ошибок нет, можно сохранить данные в профиль
            foreach ($insertedIds as $id) {
                (new \App\Models\SportUser())->insert([
                    'user_id' => $this->profile['user_id'],
                    'sport_item_id' => $id,
                ]);
            }
            foreach ($deleteIds as $id) {
                (new \App\Models\SportUser())->deleteWhere([
                    'user_id' => $this->profile['user_id'],
                    'sport_item_id' => $id,
                ]);
            }
        }
        // Обновляем профиль, чтобы отметить, что блок факультативов был изменен
        $sport_item_ids = (new \App\Models\SportUser())->findWhere(
            ['user_id' => $this->profile['user_id']]);
        $facultIsset = empty($sport_item_ids) ? 0 : 1;
        (new \App\Models\Profile())->update(
            $this->profile['id'], 
            [
                'sport_block' => $facultIsset,
            ]
        );
    }

    public function properties(): array
    {
        return [
            [
                'name' => 'sport_item_ids',
                'label' => 'Спортивные занятия',
                'html' => HtmlCheckboxList::class,
                'form_asset' => function() {
                    return [
                        'items' => (new SportItem)->all(), // Assuming this returns an associative array of items
                        'placeholder' => 'Выберите спортивное занятия',
                    ];
                }
            ],
        ];
    }
}