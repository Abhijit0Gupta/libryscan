<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Checkout;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CheckoutController extends Controller
{
    public function store(Request $request, Item $item)
    {
        if ($item->status !== 'available') {
            return back()->with('error', 'Item is not available for checkout.');
        }

        $user = User::first();

        Checkout::create([
            'item_id' => $item->id,
            'user_id' => $user->id,
            'borrowed_at' => Carbon::now(),
            'due_at' => Carbon::now()->addDays(14),
            'status' => 'active',
        ]);

        $item->update(['status' => 'borrowed']);

        return back()->with('success', 'Asset checked out successfully!');
    }

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

        // Check if there is a pending reservation queue for this item
        $nextReservation = Reservation::where('item_id', $item->id)
            ->where('status', 'pending')
            ->oldest()
            ->first();

        if ($nextReservation) {
            // Keep item status as reserved for the waiting user
            $item->update(['status' => 'reserved']);
            $nextReservation->update(['status' => 'fulfilled']);

            return back()->with('success', "Asset returned! Fulfilling reservation queue for Next User in Queue (Reservation #{$nextReservation->id}).");
        }

        $item->update(['status' => 'available']);

        return back()->with('success', 'Asset returned successfully!');
    }

    // Handle placing a hold / queue reservation
    public function reserve(Request $request, Item $item)
    {
        $user = User::first();

        Reservation::create([
            'item_id' => $item->id,
            'user_id' => $user->id,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Placed on waiting list! You will be notified when this asset is returned.');
    }
}