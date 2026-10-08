<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Checkout;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CheckoutController extends Controller
{
    // Handle checking out an asset
    public function store(Request $request, Item $item)
    {
        if ($item->status !== 'available') {
            return back()->with('error', 'Item is not available for checkout.');
        }

        // Get default admin user for demo purposes
        $user = User::first();

        // 1. Create Checkout Record
        Checkout::create([
            'item_id' => $item->id,
            'user_id' => $user->id,
            'borrowed_at' => Carbon::now(),
            'due_at' => Carbon::now()->addDays(14), // 2-week loan period
            'status' => 'active',
        ]);

        // 2. Update Item Status
        $item->update(['status' => 'borrowed']);

        return back()->with('success', 'Asset checked out successfully!');
    }

    // Handle returning an asset
    public function update(Request $request, Item $item)
    {
        $checkout = Checkout::where('item_id', $item->id)
            ->where('status', 'active')
            ->latest()
            ->first();

        if ($checkout) {
            $checkout->update([
                'returned_at' => Carbon::now(),
                'status' => 'returned',
            ]);
        }

        // Reset Item Status to available
        $item->update(['status' => 'available']);

        return back()->with('success', 'Asset returned successfully!');
    }
}