<?php
namespace App\Http\Controllers;

use App\Models\User; 

class ManagerUserController extends Controller
{
    public function index()
    {
        $users = User::all(); 
        return view('ManagerUser.index', compact('users')); 
    }
}

