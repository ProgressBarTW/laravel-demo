<?php

namespace App\Http\Controllers\Controls;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return view('controls.users.index');
    }

    public function create()
    {
        return view('controls.users.create');
    }

    public function store(Request $request)
    {
        return redirect()
            ->route('controls.users.index');
    }

    public function edit(Request $request)
    {
        return view('controls.users.edit');
    }

    public function update(Request $request)
    {
        return redirect()
            ->route('controls.users.index');
    }

    public function destroy(Request $request)
    {
        return redirect()
            ->route('controls.users.index');
    }
}
