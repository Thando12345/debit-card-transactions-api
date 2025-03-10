<x-app-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md sm:rounded-lg p-6">
                <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
                    <i class="fas fa-credit-card text-blue-500"></i>
                    Add New Debit Card
                </h2>
                
                <form action="{{ route('debit-cards.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="space-y-4">
                        <!-- Card Number -->
                        <div>
                            <label for="card_number" class="block text-gray-700 mb-2 font-medium">
                                <i class="fas fa-credit-card mr-2 text-gray-500"></i>
                                Card Number
                            </label>
                            <div class="relative">
                                <input type="text" name="card_number" id="card_number" 
                                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="4242 4242 4242 4242"
                                       required>
                                <i class="fas fa-credit-card absolute right-3 top-3 text-gray-400"></i>
                            </div>
                        </div>

                        <!-- Expiry and CVV -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="expiry_date" class="block text-gray-700 mb-2 font-medium">
                                    <i class="fas fa-calendar-alt mr-2 text-gray-500"></i>
                                    Expiry Date
                                </label>
                                <input type="month" name="expiry_date" id="expiry_date" 
                                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       required>
                            </div>
                            
                            <div>
                                <label for="cvv" class="block text-gray-700 mb-2 font-medium">
                                    <i class="fas fa-lock mr-2 text-gray-500"></i>
                                    CVV
                                </label>
                                <div class="relative">
                                    <input type="text" name="cvv" id="cvv" 
                                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           placeholder="123"
                                           required>
                                    <i class="fas fa-question-circle absolute right-3 top-3 text-gray-400 cursor-help"
                                       title="3-digit code on back of card"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Daily Limit -->
                        <div>
                            <label for="daily_limit" class="block text-gray-700 mb-2 font-medium">
                                <i class="fas fa-money-bill-wave mr-2 text-gray-500"></i>
                                Daily Limit
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-3 text-gray-500">$</span>
                                <input type="number" name="daily_limit" id="daily_limit" step="0.01" 
                                       class="w-full pl-8 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="500.00"
                                       required>
                            </div>
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-gray-700 mb-2 font-medium">
                                <i class="fas fa-info-circle mr-2 text-gray-500"></i>
                                Status
                            </label>
                            <select name="status" id="status" 
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="blocked">Blocked</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" 
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-all">
                        <i class="fas fa-save mr-2"></i>
                        Save Card
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>