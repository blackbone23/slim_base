<?php

namespace App\Controllers\Admin;

use App\Models\User;
use App\Controllers\Controller;

class AdminUserController extends Controller {
   
    public function getUserList($request, $response) {
        $users = User::all();  
        
        foreach ($users as $user):
            $items[] = $user;
        endforeach;
        
        
        return $this->view->render($response, 'admin/users/showlist.twig', array('items' => $items));
    }
}