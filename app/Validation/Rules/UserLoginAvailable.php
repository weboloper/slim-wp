<?php

namespace App\Validation\Rules;
use Respect\Validation\Rules\AbstractRule;
use App\Models\User;

class UserLoginAvailable extends AbstractRule
{
	public function validate($input)
	{
		return User::where('user_login', $input)->count() === 0;
	}
}