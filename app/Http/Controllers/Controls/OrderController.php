<?php

namespace App\Http\Controllers\Controls;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return view('controls.orders.index');
    }

    public function edit(Request $request)
    {
        return view('controls.orders.edit');
    }

    public function update(Request $request)
    {
        return redirect()
            ->route('controls.orders.index');
    }

    public function destroy(Request $request)
    {
        return redirect()
            ->route('controls.orders.index');
    }
}
