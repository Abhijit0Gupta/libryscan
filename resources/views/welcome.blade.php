<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LibryScan - Asset & Equipment Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-100 text-slate-800 font-sans min-h-screen" x-data="{ openModal: false }">

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
        
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-100 border border-emerald-300 text-emerald-800 rounded-lg text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        <!-- Header Summary & Search Bar -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900">Company Inventory Catalog</h1>
                <p class="text-slate-600 text-sm mt-1">Browse and manage internal equipment, devices, and reference books.</p>
            </div>
            <div class="flex gap-3">
                <button @click="openModal = true" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg text-sm font-semibold shadow transition">
                    + Add New Asset
                </button>
            </div>
        </div>

        <!-- Search and Filter Form -->
        <form method="GET" action="{{ route('items.index') }}" class="mb-6 flex flex-col sm:flex-row gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title or asset tag/ISBN..." class="flex-1 px-4 py-2 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <select name="status" class="px-4 py-2 bg-white border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Statuses</option>
                <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Available</option>
                <option value="borrowed" {{ request('status') === 'borrowed' ? 'selected' : '' }}>Borrowed</option>
            </select>
            <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-5 py-2 rounded-lg text-sm font-semibold transition">
                Filter
            </button>
            @if(request('search') || request('status'))
                <a href="{{ route('items.index') }}" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-lg text-sm font-semibold text-center transition">Clear</a>
            @endif
        </form>

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
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Borrowed
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($item->status === 'available')
                                    <form action="{{ route('items.checkout', $item) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold px-3 py-1.5 rounded transition">
                                            Check Out
                                        </button>
                                    </form>
                                @elseif($item->status === 'borrowed')
                                    <form action="{{ route('items.return', $item) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold px-3 py-1.5 rounded transition">
                                            Return Item
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                                No inventory items match your criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>

        <!-- Modal Form with AI Summarizer -->
        <div x-show="openModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4 z-50" x-cloak x-data="{ aiLoading: false, notesText: '' }">
            <div class="bg-white rounded-xl shadow-xl border border-slate-200 max-w-lg w-full p-6" @click.away="openModal = false">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-slate-900">Add New Inventory Asset</h2>
                    <span class="text-xs bg-purple-100 text-purple-700 font-semibold px-2.5 py-1 rounded-full border border-purple-200 flex items-center gap-1">
                        ✨ Gemini AI Enabled
                    </span>
                </div>

                <form action="{{ route('items.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Asset Title / Model</label>
                        <input type="text" id="asset_title" name="title" required placeholder="e.g. Sony WH-1000XM5 Noise Canceling Headphones" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Category</label>
                        <select name="category_id" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Asset Tag / Serial / ISBN</label>
                        <input type="text" name="asset_tag" required placeholder="e.g. RW-HW-0005" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label class="block text-xs font-semibold text-slate-700 uppercase">Location / Notes</label>
                            <button type="button" 
                                @click="
                                    const titleVal = document.getElementById('asset_title').value;
                                    if(!titleVal) { alert('Please fill in the Asset Title first!'); return; }
                                    aiLoading = true;
                                    fetch('{{ route('items.ai_summary') }}', {
                                        method: 'POST',
                                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                        body: JSON.stringify({ query: titleVal })
                                    })
                                    .then(res => res.json())
                                    .then(data => { notesText = data.summary; aiLoading = false; })
                                    .catch(() => { aiLoading = false; alert('AI Request Failed.'); })
                                " 
                                class="text-xs font-semibold text-purple-600 hover:text-purple-800 transition flex items-center gap-1">
                                <span x-show="!aiLoading">✨ Auto-Fill with AI</span>
                                <span x-show="aiLoading" class="animate-pulse">Generating specs...</span>
                            </button>
                        </div>
                        <textarea name="notes" x-model="notesText" rows="3" placeholder="Click 'Auto-Fill with AI' to generate technical notes automatically..." class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" @click="openModal = false" class="px-4 py-2 bg-slate-100 text-slate-700 text-sm font-semibold rounded-lg hover:bg-slate-200 transition">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition">Save Asset</button>
                    </div>
                </form>
            </div>
        </div>