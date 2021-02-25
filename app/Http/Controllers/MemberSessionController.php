<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\MemberAuth;

class MemberSessionController extends Controller
{
    public function create()
    {
        if (MemberAuth::isLoggedIn()){
            return redirect(MemberAuth::HOME);
        }
        
        return view('members.login');
    }

    public function store(Request $request)
    {
        MemberAuth::logIn(
            $request->email, 
            $request->password
        );

        return redirect(MemberAuth::HOME);
    }

    public function delete(Request $request)
    {
        MemberAuth::logOut();
        return redirect(MemberAuth::HOME);
    }
}
