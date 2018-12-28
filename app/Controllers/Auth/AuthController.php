<?php

namespace App\Controllers\Auth;

use App\Models\User;
use App\Controllers\Controller;
use Respect\Validation\Validator as v;
//use App\Auth\Auth;

class AuthController extends Controller {
    
    public function getSignOut($request, $response) {
        //signout
        $this->auth->logout();
        
        //redirect
        return $response->withRedirect($this->router->pathFor('home'));
    }
    
    public function getSignIn($request, $response) {
        return $this->view->render($response, 'auth/signin.twig');
    }
    
    public function postSignIn($request, $response) {
        $auth = $this->auth->attempt(
            $request->getParam('email'),
            $request->getParam('password')
        );
        
        if(!$auth) {
            
            // add flash message failed to sign in
            $this->flash->addMessage('error', 'Could not sign you in with those details.');
            
            //Redirect to signin page failed to sign in
            return $response->withRedirect($this->router->pathFor('auth.signin'));
        }
        
        return $response->withRedirect($this->router->pathFor('home'));
    }
    
    public function getSignUp($request, $response) {
        return $this->view->render($response, 'auth/signup.twig');
    }
    
    public function postSignUp($request, $response) {
        $validation = $this->validator->validate($request, [
            'email' => v::noWhitespace()->notEmpty()->email()->emailAvailable(),
            'name' => v::notEmpty()->alpha(),
            'password' => v::noWhitespace()->notEmpty(),
        ]);
        
        if ($validation->failed()) {
            return $response->withRedirect($this->router->pathFor('auth.signup'));
        }
        
        User::create([
            'email' => $request->getParam('email'),
            'name' => $request->getParam('name'),
            'password' => password_hash($request->getParam('password'), PASSWORD_DEFAULT),
            
        ]);
        
        $this->flash->addMessage('info', 'You have been signed up!');
        
        $this->auth->attempt($request->getParam('email'), $request->getParam('password'));
        
        return $response->withRedirect($this->router->pathFor('home'));
    }
}
