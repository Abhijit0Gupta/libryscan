<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::with('category')->latest();

        // Search by Title or Asset Tag
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('asset_tag', 'like', "%{$search}%");
            });
        }

        // Filter by Status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $items = $query->get();
        $categories = Category::all();

        return view('welcome', compact('items', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'asset_tag' => 'required|string|unique:items,asset_tag',
            'notes' => 'nullable|string',
        ]);

        Item::create([
            'title' => $validated['title'],
            'category_id' => $validated['category_id'],
            'asset_tag' => $validated['asset_tag'],
            'notes' => $validated['notes'],
            'status' => 'available',
        ]);

        return back()->with('success', 'New asset added successfully!');
    }
}