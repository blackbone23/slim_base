<?php

namespace App\Controllers\Auth;

use App\Models\User;
use App\Controllers\Controller;
use Respect\Validation\Validator as v;
//use App\Auth\Auth;

class PasswordController extends Controller {
    public function getChangePassword($request, $response) {
        return $this->view->render($response, 'auth/password/change.twig');
    }
    
    public function postChangePassword($request, $response) {
        
        //validate password
        $validation = $this->validator->validate($request, [
            'password_old' => v::noWhitespace()->notEmpty()->matchesPassword($this->auth->user()->password),
            'password' => v::noWhitespace()->notEmpty(),
        ]);
        
        // if validation failed
        if ($validation->failed()) {
            return $response->withRedirect($this->router->pathFor('auth.password.change'));
        }
        
        //change password (see Models/User.php)
        $this->auth->user()->setPassword($request->getParam('password'));
        
        //flash message
        $this->flash->addMessage('info', 'Your password has been changed.');
        
        //redirect
        return $response->withRedirect($this->router->pathFor('home'));
    }
 
}
