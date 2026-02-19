<nav class="bg-darker shadow-lg border-b border-white/5">
    <div class="container mx-auto px-6">
        <div class="flex items-center justify-between h-20">
            {{-- Logo --}}
            <div class="flex items-center">
                <x-logo />
            </div>

            @auth
                {{-- Navigation Links --}}
                <div class="flex items-center gap-3">
                    <x-forms.sm-button href="{{ route('users.show', auth()->user()) . '?tab=projects' }}">
                        <i class="fa-solid fa-folder-open mr-1"></i>
                        Your Projects
                    </x-forms.sm-button>

                    <x-forms.sm-button href="{{ route('projects.create') }}">
                        <i class="fa-solid fa-plus mr-1"></i>
                        Create Project
                    </x-forms.sm-button>

                    <x-forms.sm-button href="{{ route('projects.index') ?? '#' }}" :secondary="true">
                        <i class="fa-solid fa-compass mr-1"></i>
                        Find Projects
                    </x-forms.sm-button>
                </div>

                {{-- User Menu --}}
                <div class="flex items-center gap-3">
                    {{-- Username Display --}}
                    <a href="{{ route('users.show', auth()->user()) }}">
                        <div class="flex items-center gap-2 px-4 py-2 rounded-lg bg-white/5 border border-white/10">
                            <div class="h-8 w-8 rounded-full bg-primary/10 flex items-center justify-center">
                                <span class="text-xs font-semibold text-primary">
                                    {{ substr(auth()->user()->username, 0, 2) }}
                                </span>
                            </div>
                            <span class="text-textcol text-sm font-medium">
                                {{ auth()->user()->username }}
                            </span>
                        </div>
                    </a>

                    {{-- Logout Button --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-forms.sm-button type="submit">
                            <i class="fa-solid fa-right-from-bracket mr-1"></i>
                            Logout
                        </x-forms.sm-button>
                    </form>
                </div>
            @else
                {{-- Guest Actions --}}
                <div class="flex items-center gap-3">
                    <x-forms.sm-button href="{{ route('login') }}">
                        <i class="fa-solid fa-right-to-bracket mr-1"></i>
                        Login
                    </x-forms.sm-button>

                    <x-forms.sm-button href="{{ route('register') }}">
                        <i class="fa-solid fa-user-plus mr-2"></i>
                        Register
                    </x-forms.sm-button>
                </div>
            @endauth
        </div>
    </div>
</nav>