<?php 

namespace App\Profile\Units;

use App\Profile\ProfileStruct;

class ProfileSubmit
{
    protected $profileStruct;
    protected $errors = [];

    protected $loadedData = [];

    function __construct($profile = null)
    {
        $this->profileStruct = new ProfileStruct($profile);
    }

    public function getProfile()
    {
        return $this->profileStruct->getProfile();
    }

    /**
     * Constructor to initialize the profile form with a profile object
     * 
     * @param mixed $profile
     */
    public static function new()
    {
        return new self();
    }

    /**
     * @var int $id user id
     * @return ProfileForm
     */
    public static function of($id) 
    {
        $profile = ProfileStruct::findProfile($id);
        if (is_null($profile)) {
            throw new \Exception("Profile not found");
        }
        return new self($profile);
    } 

    public function loadData($data)
    {
        $this->loadedData = $data;
    }

    // сохранение данных профиля отправленных из формы
    public function handle()
    { 
        $profile = $this->profileStruct->getProfile(); 
        if (is_null($profile)) { 
            $profile = $this->profileStruct->createProfile(); // Create a new profile if it doesn't exist 
        } 
        foreach ($this->profileStruct->getBlocks() as $block) {  
            $block->read($profile);  
            $block->load($this->loadedData); 
            $block->handle(); 
            if ($blockErrors = $block->errors()) { 
                $this->errors = array_merge($this->errors, $blockErrors); 
            } 
        } 
        if (!empty($errors)) { 
            throw new \Exception("Errors occurred while handling profile form: " . implode(', ', $errors)); 
        } 
        return true; // or return some success message 
    }

    public function errors()
    {
        return $this->errors;
    }

} 
