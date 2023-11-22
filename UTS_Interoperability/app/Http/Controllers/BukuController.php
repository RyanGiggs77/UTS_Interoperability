<?php

namespace App\Http\Controllers;
use App\Models\Buku;
use App\Models\User;



use Illuminate\Http\Request;

class BukuController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

   public function getUserPosts($id)
    {
        $user = User::find($id)->buku()->get();
        return ($user);
        
    }
}