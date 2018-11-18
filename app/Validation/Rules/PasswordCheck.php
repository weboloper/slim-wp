<?php

namespace App\Validation\Rules;
use Respect\Validation\Rules\AbstractRule;
use App\Models\User;

class PasswordCheck extends AbstractRule
{
	protected $user_pass;

	public function __construct($user)
	{
	 	$this->user = $user;
	}

	public function validate($input)
	{	
		$userProvider = new \Corcel\Laravel\Auth\AuthUserProvider;
		return $userProvider->validateCredentials( $this->user , ['password' => $input] );

		if(!is_null($user) && $userProvider->validateCredentials( $this->user , ['password' => $input] )) {
		    // successfully login
		    $_SESSION['user'] = $user->ID;
		    return true;
		}

		return  password_verify(  $input , $this->user->password  ); 

	}
}