<?php  

namespace App\Models;

use Corcel\Model\User as Model;

class User extends Model
{   
     /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $fillable = [
        'user_login',
        'user_email',
        'user_nicename',
        'user_pass', 
        'user_url', 
        'user_registered', 
        'user_activation_key', 
        'user_status', 
        'display_name', 
    ];

    protected $hidden = array('user_pass', 'user_activation_key' );

    // const CREATED_AT = 'created_at';
    // const UPDATED_AT = 'updated_at';
    
    public function setPassword($password)
    {   
        $passwordService = new  \Corcel\Services\PasswordService;
        $this->update([
            'user_pass' =>   $passwordService->makeHash( $password )
        ]);
    }

}