<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LibryScan - Asset & Equipment Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 text-slate-800 font-sans min-h-screen">

    <!-- Top Navigation Bar -->
    <header class="bg-slate-900 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <span class="text-2xl font-bold tracking-wider text-blue-400">LibryScan</span>
                <span class="text-xs bg-slate-800 text-slate-300 px-2.5 py-1 rounded-full border border-slate-700">RidgeWorks Co., Ltd.</span>
            </div>
            <div class="text-sm text-slate-400">
                In-House Asset & Library Management
            </div>
        </div>
    </header>

    <!-- Main Container -->
    <main class="max-w-7xl mx-auto px-6 py-8">
        
        <!-- Header Summary Section -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900">Company Inventory Catalog</h1>
                <p class="text-slate-600 text-sm mt-1">Browse and manage internal equipment, devices, and reference books.</p>
            </div>
            <div class="flex gap-3">
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg text-sm font-semibold shadow transition">
                    + Add New Asset
                </button>
            </div>
        </div>

        <!-- Inventory Data Table -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 text-xs uppercase font-semibold tracking-wider">
                        <th class="px-6 py-4">Asset Tag / ISBN</th>
                        <th class="px-6 py-4">Title & Details</th>
                        <th class="px-6 py-4">Category</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-sm">
                    @forelse($items as $item)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="px-6 py-4 font-mono text-xs text-blue-600 font-medium">
                                {{ $item->asset_tag }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-900">{{ $item->title }}</div>
                                <div class="text-xs text-slate-500 mt-0.5">{{ $item->notes ?? 'No additional location notes.' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-slate-100 text-slate-700 px-2.5 py-1 rounded text-xs font-medium border border-slate-200">
                                    {{ $item->category->name }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($item->status === 'available')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Available
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> {{ ucfirst($item->status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <button class="text-blue-600 hover:text-blue-800 font-medium text-xs hover:underline">
                                    Check Out →
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                                No inventory items found in database.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>

</body>
</html>