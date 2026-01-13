<?php 

namespace App\Profile\Units;

use App\Profile\ProfileStruct;

class ProfileView
{
    protected $profileStruct;

    protected $loadedData = [];

    function __construct($profile)
    {
        $this->profileStruct = new ProfileStruct($profile);
    }

    public function getProfile()
    {
        return $this->profileStruct->getProfile();
    }

    /**
     * @var int $id user id
     * @return ProfileView
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
