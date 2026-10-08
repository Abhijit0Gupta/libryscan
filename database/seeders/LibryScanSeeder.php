<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LibryScanSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create a Test Admin User
        $user = User::firstOrCreate(
            ['email' => 'admin@ridgeworks.co.jp'],
            [
                'name' => 'RidgeWorks Admin',
                'password' => Hash::make('password123'),
            ]
        );

        // 2. Create Categories
        $bookCategory = Category::create([
            'name' => 'Technical Books & Docs',
            'code' => 'BOOK',
            'description' => 'Programming manuals, architecture references, and Japanese engineering books.'
        ]);

        $hardwareCategory = Category::create([
            'name' => 'IT Hardware & Devices',
            'code' => 'HW',
            'description' => 'Development laptops, external monitors, and testing devices.'
        ]);

        // 3. Create Sample Items
        Item::create([
            'category_id' => $bookCategory->id,
            'title' => 'Laravel 11 Development Guide',
            'asset_tag' => 'ISBN-9784774198765',
            'status' => 'available',
            'notes' => 'Located on Library Shelf A-3'
        ]);

        Item::create([
            'category_id' => $bookCategory->id,
            'title' => 'Clean Code: Handbook of Agile Software Craftsmanship',
            'asset_tag' => 'ISBN-9780132350884',
            'status' => 'available',
            'notes' => 'Located on Library Shelf A-1'
        ]);

        Item::create([
            'category_id' => $hardwareCategory->id,
            'title' => 'MacBook Pro 16" (M2 Max - 32GB)',
            'asset_tag' => 'RW-HW-0001',
            'status' => 'available',
            'notes' => 'Stored in Equipment Cabinet 2'
        ]);

        Item::create([
            'category_id' => $hardwareCategory->id,
            'title' => 'Dell UltraSharp 27" 4K Monitor',
            'asset_tag' => 'RW-HW-0002',
            'status' => 'available',
            'notes' => 'Stored in Desk Storage Bay B'
        ]);
    }
}