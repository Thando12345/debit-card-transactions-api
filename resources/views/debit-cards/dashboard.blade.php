<x-app-layout>
    <!-- Page Header -->
    <header class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-12 shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex items-center gap-4">
                <i class="fas fa-tachometer-alt text-4xl"></i>
                <div>
                    <h1 class="text-3xl font-bold">Dashboard</h1>
                    <p class="mt-2 text-lg opacity-90">Manage your finances at a glance</p>
                </div>
            </div>
            <nav class="flex gap-4 mt-4">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-200">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a href="{{ route('debit-cards.create') }}" class="hover:text-blue-200">
                    <i class="fas fa-plus-circle"></i> Add Card
                </a>
                <a href="{{ route('transactions') }}" class="hover:text-blue-200">
                    <i class="fas fa-plus-circle"></i> View Transactions
                </a>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            <p>{{ session('success') }}</p>
        </div>
        @endif
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($cards as $card)
                <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <div class="flex items-center gap-2 mb-2">
                                    <i class="fas fa-credit-card text-2xl text-blue-500"></i>
                                    <h3 class="text-xl font-semibold">
                                        **** {{ substr($card->card_number, -4) }}
                                    </h3>
                                </div>
                                <p class="text-sm text-gray-500">
                                    Expires {{ $card->expiry_date->format('m/Y') }}
                                </p>
                            </div>
                            
                            <span class="px-3 py-1 rounded-full text-sm font-medium
                                @if($card->status == 'active') bg-green-100 text-green-800 
                                @elseif($card->status == 'inactive') bg-yellow-100 text-yellow-800 
                                @else bg-red-100 text-red-800 @endif">
                                <i class="fas fa-circle mr-1 text-xs"></i>
                                {{ ucfirst($card->status) }}
                            </span>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <p class="text-sm text-gray-500">Daily Limit</p>
                                <p class="font-medium">${{ number_format($card->daily_limit, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Current Balance</p>
                                <p class="font-medium">${{ number_format($card->balance, 2) }}</p>
                            </div>
                        </div>

                        <div class="mt-4 pt-4 border-t border-gray-100 flex justify-end">
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="p-2 hover:bg-gray-100 rounded-full">
                                    <i class="fas fa-ellipsis-v text-gray-500"></i>
                                </button>
                                <div x-show="open" 
                                     @click.away="open = false"
                                     class="absolute right-0 mt-2 w-48 bg-white shadow-lg rounded-lg py-2 z-10">
                                    <a href="{{ route('debit-cards.transactions', $card) }}" 
                                       class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-receipt mr-2"></i>View Transactions
                                    </a>
                                    <a href="{{ route('debit-cards.edit', $card) }}" 
                                       class="block px-4 py-2 text-blue-600 hover:bg-gray-100">
                                        <i class="fas fa-edit mr-2"></i>Edit Card
                                    </a>
                                    <form action="{{ route('debit-cards.destroy', $card) }}" method="POST" class="block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100">
                                            <i class="fas fa-trash mr-2"></i>Delete Card
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($cards->isEmpty())
            <div class="text-center py-12">
                <i class="fas fa-credit-card text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">No debit cards found. Add your first card!</p>
            </div>
        @endif
    </main>
</x-app-layout>