<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    function deposit(Request $request) {

        return view('payment.deposit');
    }
}
