<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserslistController extends Controller
{
    public function index()
    {
        $users = User::select('first_name', 'last_name', 'email')->paginate(5);

        return view('management.users_list', compact('users'));
    }
}
