<?php 

namespace App\Profile;

use App\Profile\Blocks\BirthdateBlock;
use App\Profile\Blocks\ClassBlock;
use App\Profile\Blocks\EmailBlock;
use App\Profile\Blocks\FioBlock;
use App\Profile\Blocks\FacultativeBlock;
use App\Profile\Blocks\ChosenPredmetBlock;
use App\Profile\Blocks\OlimpicBlock;
use App\Profile\Blocks\SportBlock;

/** 
 * стркуктура профиля пользователя (школьника) 
 */ 
class ProfileStruct 
{ 
    protected $user; 
    protected $profile; 
    protected $blocks = []; 

    protected $blocks_classes = [ 
        FioBlock::class, 
        BirthdateBlock::class,
        EmailBlock::class,
        ClassBlock::class,
        FacultativeBlock::class,
        ChosenPredmetBlock::class,
        OlimpicBlock::class,
        SportBlock::class,
        // \App\Profile\Blocks\BirthdateBlock::class, 
        // \Profile\Blocks\FacultativeBlock::class, 
        // \Profile\Blocks\SportBlock::class, // задание 
        // \Profile\Blocks\ChosenPredmetBlock::class, 
        // \Profile\Blocks\OlimpicBlock::class, // задание 
    ]; 

    public function __construct($profile = null) 
    { 
        $this->profile = $profile; 
        if (!is_null($profile)) { 
            // $this->createProfile(); // создаем профиль если он не передан 
            $this->user = (new \App\Models\User())->find($profile['user_id']); // Assuming profile has a user_id property 
        } 
        $this->initBlocks(); 
    } 

    /** 
     * creates new user and new profile 
     */ 
    public function createProfile() 
    { 
        $newUserId = (new \App\Models\User())->insert(['name' => uniqid(), 'email' => uniqid()]); // Create a new user 
        $this->user = (new \App\Models\User())->find($newUserId); // Fetch the newly created user 
        $profileId = (new \App\Models\Profile())->insert(['user_id' => $newUserId]); // Create a new profile 
        $this->profile = (new \App\Models\Profile())->find($profileId); // Fetch the newly created profile 
        return $this->profile; 
    }

    protected function initBlocks() 
    { 
        foreach ($this->blocks_classes as $blockClass) {
            if (class_exists($blockClass)) {
                $newBlock = new $blockClass($this->profile);
                $this->blocks[$newBlock->code()] = $newBlock;
            } else {
                throw new \Exception("Block class $blockClass does not exist");
            }
        }
    }

    public function getBlocks() 
    { 
        return $this->blocks; 
    }

    public static function findProfile($id) 
    {
        return (new \App\Models\Profile())->find($id);
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getProfile() 
    { 
        return $this->profile; 
    }
}
