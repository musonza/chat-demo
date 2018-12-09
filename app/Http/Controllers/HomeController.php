<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Chat;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $conversations = Chat::conversation(Chat::conversations()->conversation)
          ->for(auth()->user())
          ->get()
          ->toArray()['data'];

        $conversations = array_pluck($conversations, 'id');

        return view('home', compact('conversations'));
    }
}
