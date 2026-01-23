<nav class="bg-darker shadow-lg">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center justify-between w-full">
                {{-- nav links --}}
                <div class="flex items-center">
                    <x-logo />
                </div>
                @auth
                    <div>
                        <x-forms.button href="{{ route('projects.index') }}"><i class="fa-solid fa-user"></i>
                        </x-forms.button>
                        <x-forms.button href="{{ route('projects.index') }}">Your Tasks</x-forms.button>
                        <x-forms.button href="{{ route('projects.create') }}">Create a Project</x-forms.button>
                    </div>
                @else
                    <div>
                        <x-forms.button href="{{ route('projects.index') }}">Home</x-forms.button>
                    </div>
                @endauth
                {{-- search bar --}}
                <x-forms.search-input placeholder="Find public projects..." />
                @auth
                    <div class="flex items-center space-x-4">
                        <span class="text-secondary text-sm">
                            {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}
                        </span>
                        {{-- logout --}}
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <x-forms.button>Logout</x-forms.button>
                        </form>
                    </div>
                @else
                    {{-- login / register --}}
                    <div class="flex items-center space-x-4">
                        {{-- :active="request()->routeIs('login')" to do later--}}
                        <x-forms.button :active='true' href="{{ route('login') }}">Login</x-forms.button>
                        <x-forms.button href="{{ route('register') }}">Register</x-forms.button>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</nav>