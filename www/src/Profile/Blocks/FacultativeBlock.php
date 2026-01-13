<?php 

namespace App\Profile\Blocks;

use App\Html\HtmlCheckboxList;
use App\Models\FacultativePredmet;
use App\Profile\ProfileBlockBase;

// блок, который собирает информацию о факультативных занятиях
class FacultativeBlock extends ProfileBlockBase
{
    protected $data;
    protected $user;
    protected $profile;
    
    static public function code() 
    {
        return 'facultative';
    }

    static public function title() 
    {
        return 'Факультативные занятия';
    }

    static public function description() 
    {
        return 'Блок для отображения и редактирования факультативных занятий пользователя';
    }
    
    public function read($profile)
    {
        // Логика для чтения данных факультативных занятий из профиля
        $this->profile = $profile;
        $facultative_users = (new \App\Models\FacultativeUser())->findWhere(
            ['user_id' => $profile['user_id']]); // Assuming profile has a user_id property 
        $this->data = [
            'facultative_predmet_ids' => array_column($facultative_users ?? [], 'facultative_predmet_id') ?? [],
        ];
        return true;
    }


    public function view()
    {
        $html = ''; 
        $comma = "";
        $allPredmets = (new FacultativePredmet())->all();
        foreach ($allPredmets as $predmet) {
            if (in_array($predmet['id'], $this->data['facultative_predmet_ids'])) {
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
        // Логика для обработки данных ФИО
         
        $facultative_predmet_ids =[];
        if (isset($this->loadedData['facultative_predmet_ids'])) {
            $facultative_predmet_ids = $this->loadedData['facultative_predmet_ids'];
        } 

        if (empty($this->errors)) {
            $insertedIds = array_diff($facultative_predmet_ids, $this->data['facultative_predmet_ids']);
            $deleteIds = array_diff($this->data['facultative_predmet_ids'], $facultative_predmet_ids);
            // Если ошибок нет, можно сохранить данные в профиль
            foreach ($insertedIds as $id) {
                (new \App\Models\FacultativeUser())->insert([
                    'user_id' => $this->profile['user_id'],
                    'facultative_predmet_id' => $id,
                ]);
            }
            foreach ($deleteIds as $id) {
                (new \App\Models\FacultativeUser())->deleteWhere([
                    'user_id' => $this->profile['user_id'],
                    'facultative_predmet_id' => $id,
                ]);
            }
        }
        // Обновляем профиль, чтобы отметить, что блок факультативов был изменен
        $facultative_predmet_ids = (new \App\Models\FacultativeUser())->findWhere(
            ['user_id' => $this->profile['user_id']]);
        $facultIsset = empty($facultative_predmet_ids) ? 0 : 1;
        (new \App\Models\Profile())->update(
            $this->profile['id'], 
            [
                'facult_block' => $facultIsset,
            ]
        );
    }

    public function properties(): array
    {
        return [
            [
                'name' => 'facultative_predmet_ids',
                'label' => 'Факультативные занятия',
                'html' => HtmlCheckboxList::class,
                'form_asset' => function() {
                    return [
                        'items' => (new FacultativePredmet)->all(), // Assuming this returns an associative array of items
                        'placeholder' => 'Выберите факультативные занятия',
                    ];
                }
            ],
        ];
    }
    
}
