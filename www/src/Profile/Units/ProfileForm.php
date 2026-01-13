<?php 

namespace App\Profile\Units;

use App\Profile\ProfileStruct;

class ProfileForm
{
    protected $profileStruct;

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

    public function getBlocks()
    {
        return $this->profileStruct->getBlocks();
    }

} 
