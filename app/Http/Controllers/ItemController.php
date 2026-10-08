<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Services\GeminiService;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::with('category')->latest();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('asset_tag', 'like', "%{$search}%");
            });
        }

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

    // AI Summarizer Endpoint
    public function generateAiSummary(Request $request, GeminiService $gemini)
    {
        $request->validate(['query' => 'required|string']);

        $summary = $gemini->generateAssetSummary($request->input('query'));

        return response()->json(['summary' => $summary ?? 'Could not generate AI summary.']);
    }

    // AI Chatbot Assistant Endpoint
    public function chat(Request $request, GeminiService $gemini)
    {
        $request->validate(['message' => 'required|string']);

        // Retrieve current database snapshot for prompt context
        $items = Item::with('category')->get(['id', 'title', 'asset_tag', 'status', 'notes']);
        
        $inventoryContext = $items->map(function ($item) {
            return "Asset: {$item->title} | Tag: {$item->asset_tag} | Category: {$item->category->name} | Status: {$item->status}";
        })->implode("\n");

        $prompt = "You are LibryScan AI, an internal IT asset management assistant.\n" .
                  "Here is the live inventory context:\n{$inventoryContext}\n\n" .
                  "User Question: {$request->input('message')}\n" .
                  "Answer concisely and professionally based ONLY on the provided context.";

        $response = $gemini->generateAssetSummary($prompt);

        return response()->json(['reply' => $response ?? 'Sorry, I could not process your query right now.']);
    }
}