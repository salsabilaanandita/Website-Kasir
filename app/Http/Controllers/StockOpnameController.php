<?php

namespace App\Http\Controllers;

use App\Models\StockOpname;
use Illuminate\Http\Request;

class StockOpnameController extends Controller
{
    public function index()
    {
        $opnames = StockOpname::with('user')->latest()->paginate(15);
        return view('inventory.opname', compact('opnames'));
    }
}
