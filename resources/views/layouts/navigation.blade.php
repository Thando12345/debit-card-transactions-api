<!-- Navigation -->
<nav class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('debit-cards.index') }}">
                        <i class="fas fa-credit-card text-blue-500 text-2xl"></i>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <a href="{{ route('debit-cards.index') }}"
                       class="inline-flex items-center px-1 pt-1 border-b-2 border-blue-500 text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-blue-700 transition duration-150 ease-in-out">
                        Debit Cards
                    </a>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="flex items-center">
                <form method="POST" action="{{ route('logout') }}" class="ml-3">
                    @csrf
                    <button type="submit"
                            class="text-gray-500 hover:text-gray-700 focus:outline-none">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>