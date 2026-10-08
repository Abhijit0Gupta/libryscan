<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        // Retrieve all items with their category relationship
        $items = Item::with('category')->latest()->get();

        return view('welcome', compact('items'));
    }
}