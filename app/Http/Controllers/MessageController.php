<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function dashboard()
    {
        $users = User::whereNotIn('id', [auth()->user()->id])->get();
        return view('dashboard', ['users' => $users]);
    }

    public function chat($id){
        return view('chat', ['id' => $id]);
    }
}
