<?php

namespace App\Http\Controllers;

use App\Models\StockAdjustment;
use Illuminate\Http\Request;

class StockAdjustmentController extends Controller
{
    public function index()
    {
        $adjustments = StockAdjustment::with(['product', 'user'])->latest()->paginate(15);
        return view('inventory.adjustment', compact('adjustments'));
    }
}
