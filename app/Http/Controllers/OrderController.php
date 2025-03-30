<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(){
        $data['orders']= Order::get();
        return view('backend.order.index',$data);
    }
}
