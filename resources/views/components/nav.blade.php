<nav class="bg-darker shadow-lg">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center">
                <a href="{{ route('projects.index') }}" class="text-2xl font-bold text-primary">
                    TimeBase
                    {{-- work on name later --}}
                </a>
            </div>

            <div class="flex items-center space-x-6">
                <a href="{{ route('projects.index') }}" class="text-secondary hover:text-primary transition">
                    Find projects
                </a>
                <a href="#" class="text-secondary hover:text-primary transition">
                    Tasks
                </a>

                @auth
                    <div class="flex items-center space-x-4">
                        <span class="text-secondary text-sm">
                            {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}
                        </span>
                        {{-- logout --}}
                        <form method="POST" action="" class="inline">
                            @csrf
                            <button type="submit" class="text-secondary hover:text-primary transition text-sm">
                                Logout
                            </button>
                        </form>
                    </div>
                @else
                    {{-- login / regster --}}
                    <a href="" class="text-secondary hover:text-primary transition">
                        Login
                    </a>
                    <a href="" class="text-secondary hover:text-primary transition">
                        Register
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>