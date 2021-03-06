<?php

namespace Modules\Auth\Controllers;
use App\Models\User;
use App\Models\ResetPassword;
use Respect\Validation\Validator as v;

use Exception;

class PasswordController extends Controller
{	 
	public function getChangePassword($request, $response)
	{
		return $this->view->render($response, '@Auth\password\change.twig');
	}

	public function postChangePassword($request, $response)
	{	
		$validation = $this->validator->validate($request,[
			'password'		=>	v::noWhiteSpace()->notEmpty()->length(6, 50)->passwordCheck($this->auth->user())->setName('Current password'),
			'new_password'	=>	v::noWhiteSpace()->notEmpty()->length(6, 50)->setName('New password'),
			// 'user_pass_again'	=>	v::noWhiteSpace()->notEmpty(),
		]);

		if ($validation->failed()) {
			return $response->withRedirect($this->router->pathFor('auth.password.change'));
		}

		$this->auth->user()->setPassword($request->getParam('new_password'));

		$this->flash->addMessage('info', 'Your password was changed!');

		return $response->withRedirect($this->router->pathFor('auth.home'));

	}

	public function getPasswordRecover($request, $response)
	{	
 		return $this->view->render($response, '@Auth\password\recover.twig');
	}

	public function postPasswordEmail($request, $response)
	{	

		$validation = $this->validator->validate($request,[
			'email'				=>	v::noWhiteSpace()->notEmpty()->email()->setName('E-mail'),
		]);

		if ($validation->failed()) {
			return $response->withRedirect($this->router->pathFor('auth.password.recover'));
		}

		if( $user = $this->userProvider->retrieveByCredentials($request->getParams()) ) {

	 

			$token = bin2hex(random_bytes(100));

			$user->user_activation_key = password_hash( $token , PASSWORD_DEFAULT);
 			$user->save();


			try {
				$app = $this->container['settings']['app'];

				$url = $app['url'].$this->router->pathFor('auth.password.reset').'?email='.$user->user_email .'&identifier='.$token;

				$sendEmail = $this->mail->send('@Auth/email/recoverpassword.twig', ['url' => $url , 'app' => $app ] , function($message) use ($user) {
					$message->to(  $user->user_email  );
					$message->subject('Password Recovery');
				}); 

				if(!$sendEmail ) {
					$this->flash->addMessage('danger',  'Unexpected error!' );
			    	return $response->withRedirect($this->router->pathFor('auth.password.recover'));
				}

				$this->flash->addMessage('info',  'Password recovery e-mail has been sent! Please check your e-mail' );
			    return $response->withRedirect($this->router->pathFor('auth.login'));

			} catch (Exception $e) {
			    // die(var_dump($e->getMessage()));
			    $this->flash->addMessage('danger',  $e->getMessage() );
			    return $response->withRedirect($this->router->pathFor('auth.password.recover'));
			}

		}else {

			$this->flash->addMessage('danger', 'No email found!');
			return $response->withRedirect($this->router->pathFor('auth.password.recover'));
		}

	}

	public function getPasswordReset($request, $response)
	{	

		$email 		= $request->getParam('email');
		$identifier = $request->getParam('identifier');

		$validation = $this->validator->validate($request,[
			'email'			=>	v::noWhiteSpace()->notEmpty()->email(),
			'identifier'	=>	v::noWhiteSpace()->notEmpty(),
		]);

		if ($validation->failed()) {
			return $this->view->render($response, 'errors/404.twig');
		}

 		return $this->view->render($response, '@Auth\password\reset.twig');
	}

	public function postPasswordReset($request, $response)
	{
		$email 		= $request->getParam('email');
		$identifier = $request->getParam('identifier');

		$validation = $this->validator->validate($request,[
			'email'				=>	v::noWhiteSpace()->notEmpty()->email(),
			'identifier'		=>	v::noWhiteSpace()->notEmpty(),
			'password'			=>	v::noWhiteSpace()->notEmpty()->length(6, 50)->setName('Password'),
			'password_again'	=>	v::noWhiteSpace()->notEmpty()->length(6, 50)->setName('Password')->passwordMatch( $request->getParam('password')),
		]);

		if ($validation->failed()) {
			$this->flash->addMessage('danger', 'Validation failed!');
			return $response->withRedirect($this->router->pathFor('auth.login'));
		}

		if( !$user = $this->userProvider->retrieveByCredentials($request->getParams()) ) {
			$this->flash->addMessage('danger', 'Entity not found!');
			return $response->withRedirect($this->router->pathFor('auth.login'));
		}

		// if( ! $lastReset = $user->resetPassword()->orderBy('id', 'desc')->first() ) {
		// 	$this->flash->addMessage('danger', 'Password reset not found!');
		// 	return $response->withRedirect($this->router->pathFor('auth.login'));
		// }

		// if($lastReset->expires < time() ) {
		// 	$this->flash->addMessage('danger', 'Token expired!');
		// 	return $response->withRedirect($this->router->pathFor('auth.login'));
		// }

		if(password_verify( $identifier , $user->user_activation_key))
		{	
			$user->user_pass = $this->passwordService->makeHash($request->getParam('password'));
			$token = bin2hex(random_bytes(100));
			$user->user_activation_key = password_hash( $token , PASSWORD_DEFAULT);
			$user->save();
			$this->flash->addMessage('success', 'Your password successfuly changed!');
			return $response->withRedirect($this->router->pathFor('auth.login'));
		}
 
		$this->flash->addMessage('danger', 'Unexpected error!');
		return $response->withRedirect($this->router->pathFor('auth.login'));

	}

	
}