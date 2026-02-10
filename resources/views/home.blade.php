<x-layout>
    <x-slot:title>Welcome to TimeBase</x-slot:title>

    <div class="relative min-h-screen w-full bg-darker overflow-hidden">
        {{-- Background gradients --}}
        <div
            class="absolute -left-32 -top-32 h-96 w-96 rounded-full bg-gradient-to-br from-primary/20 to-secondary/0 blur-3xl">
        </div>
        <div
            class="absolute right-0 top-1/4 h-96 w-96 rounded-full bg-gradient-to-br from-secondary/20 to-primary/0 blur-3xl">
        </div>
        <div
            class="absolute -right-32 -bottom-32 h-96 w-96 rounded-full bg-gradient-to-br from-primary/20 to-secondary/0 blur-3xl">
        </div>

        {{-- Hero Section --}}
        <div class="relative mx-auto max-w-7xl px-6 py-24">
            <div class="text-center">
                <h1 class="text-6xl font-bold text-textcol mb-6">
                    Track Your Time,
                    <span class="bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">
                        Boost Productivity
                    </span>
                </h1>
                <p class="text-xl text-textcol2 max-w-3xl mx-auto mb-10">
                    Manage projects, track tasks, and collaborate with your team all in one place. TimeBase makes time
                    tracking simple and effective.
                </p>

                {{-- CTA Buttons --}}
                <div class="flex items-center justify-center gap-4">
                    @auth
                        <x-forms.button href="{{ route('projects.index') }}">
                            <i class="fa-solid fa-folder-open mr-2"></i>
                            Find Projects
                        </x-forms.button>
                        <x-forms.button :secondary="true" href="{{ route('projects.create') }}">
                            <i class="fa-solid fa-plus mr-2"></i>
                            Create a Project
                        </x-forms.button>
                    @else
                        <x-forms.button href="{{ route('register') }}">
                            <i class="fa-solid fa-rocket mr-2"></i>
                            Get Started
                        </x-forms.button>
                        <x-forms.button :secondary="true" href="{{ route('login') }}">
                            <i class="fa-solid fa-right-to-bracket mr-2"></i>
                            Login
                        </x-forms.button>
                    @endauth
                </div>
            </div>
        </div>

        {{-- Features Section --}}
        <div class="relative mx-auto max-w-7xl px-6 py-16">
            <h2 class="text-4xl font-bold text-textcol text-center mb-12">
                Everything You Need
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Feature 1 --}}
                <div
                    class="group relative overflow-hidden rounded-xl bg-slate-950/40 ring-1 ring-white/5 transition-all duration-300 hover:ring-primary/30 p-8">
                    <div
                        class="absolute -right-8 -top-8 h-24 w-24 rounded-full bg-gradient-to-br from-primary/10 to-secondary/0 blur-2xl transition-all duration-500 group-hover:scale-150 group-hover:opacity-70">
                    </div>

                    <div class="relative">
                        <div class="h-16 w-16 rounded-full bg-primary/10 flex items-center justify-center mb-4">
                            <i class="fa-solid fa-clock text-3xl text-primary"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-textcol mb-3">
                            Time Tracking
                        </h3>
                        <p class="text-textcol2">
                            Track time spent on tasks and projects with precision. Get detailed insights into where your
                            time goes.
                        </p>
                    </div>
                </div>

                {{-- Feature 2 --}}
                <div
                    class="group relative overflow-hidden rounded-xl bg-slate-950/40 ring-1 ring-white/5 transition-all duration-300 hover:ring-secondary/30 p-8">
                    <div
                        class="absolute -right-8 -top-8 h-24 w-24 rounded-full bg-gradient-to-br from-secondary/10 to-primary/0 blur-2xl transition-all duration-500 group-hover:scale-150 group-hover:opacity-70">
                    </div>

                    <div class="relative">
                        <div class="h-16 w-16 rounded-full bg-secondary/10 flex items-center justify-center mb-4">
                            <i class="fa-solid fa-users text-3xl text-secondary"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-textcol mb-3">
                            Team Collaboration
                        </h3>
                        <p class="text-textcol2">
                            Work together seamlessly with your team. Assign roles, share projects, and track team
                            progress.
                        </p>
                    </div>
                </div>

                {{-- Feature 3 --}}
                <div
                    class="group relative overflow-hidden rounded-xl bg-slate-950/40 ring-1 ring-white/5 transition-all duration-300 hover:ring-primary/30 p-8">
                    <div
                        class="absolute -right-8 -top-8 h-24 w-24 rounded-full bg-gradient-to-br from-primary/10 to-secondary/0 blur-2xl transition-all duration-500 group-hover:scale-150 group-hover:opacity-70">
                    </div>

                    <div class="relative">
                        <div class="h-16 w-16 rounded-full bg-primary/10 flex items-center justify-center mb-4">
                            <i class="fa-solid fa-chart-line text-3xl text-primary"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-textcol mb-3">
                            Project Analytics
                        </h3>
                        <p class="text-textcol2">
                            Visualize your productivity with powerful analytics and statistics. Make data-driven
                            decisions.
                        </p>
                    </div>
                </div>

                {{-- Feature 4 --}}
                <div
                    class="group relative overflow-hidden rounded-xl bg-slate-950/40 ring-1 ring-white/5 transition-all duration-300 hover:ring-secondary/30 p-8">
                    <div
                        class="absolute -right-8 -top-8 h-24 w-24 rounded-full bg-gradient-to-br from-secondary/10 to-primary/0 blur-2xl transition-all duration-500 group-hover:scale-150 group-hover:opacity-70">
                    </div>

                    <div class="relative">
                        <div class="h-16 w-16 rounded-full bg-secondary/10 flex items-center justify-center mb-4">
                            <i class="fa-solid fa-list-check text-3xl text-secondary"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-textcol mb-3">
                            Task Management
                        </h3>
                        <p class="text-textcol2">
                            Organize your work with tasks. Create, assign, and track task completion across all
                            projects.
                        </p>
                    </div>
                </div>

                {{-- Feature 5 --}}
                <div
                    class="group relative overflow-hidden rounded-xl bg-slate-950/40 ring-1 ring-white/5 transition-all duration-300 hover:ring-primary/30 p-8">
                    <div
                        class="absolute -right-8 -top-8 h-24 w-24 rounded-full bg-gradient-to-br from-primary/10 to-secondary/0 blur-2xl transition-all duration-500 group-hover:scale-150 group-hover:opacity-70">
                    </div>

                    <div class="relative">
                        <div class="h-16 w-16 rounded-full bg-primary/10 flex items-center justify-center mb-4">
                            <i class="fa-solid fa-folder-open text-3xl text-primary"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-textcol mb-3">
                            Project Organization
                        </h3>
                        <p class="text-textcol2">
                            Keep all your projects organized in one place. Public or private, active or archived.
                        </p>
                    </div>
                </div>

                {{-- Feature 6 --}}
                <div
                    class="group relative overflow-hidden rounded-xl bg-slate-950/40 ring-1 ring-white/5 transition-all duration-300 hover:ring-secondary/30 p-8">
                    <div
                        class="absolute -right-8 -top-8 h-24 w-24 rounded-full bg-gradient-to-br from-secondary/10 to-primary/0 blur-2xl transition-all duration-500 group-hover:scale-150 group-hover:opacity-70">
                    </div>

                    <div class="relative">
                        <div class="h-16 w-16 rounded-full bg-secondary/10 flex items-center justify-center mb-4">
                            <i class="fa-solid fa-pencil-alt text-3xl text-secondary"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-textcol mb-3">
                            Time Entries
                        </h3>
                        <p class="text-textcol2">
                            Log detailed time entries for every task. Keep a complete record of your work history.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- CTA Section --}}
        <div class="relative mx-auto max-w-7xl px-6 py-24">
            <div class="rounded-3xl bg-slate-950/40 ring-1 ring-white/5 p-16 text-center">
                <h2 class="text-4xl font-bold text-textcol mb-6">
                    Ready to Get Started?
                </h2>
                <p class="text-xl text-textcol2 mb-10 max-w-2xl mx-auto">
                    Join TimeBase today and take control of your time. Start tracking, start improving.
                </p>
                @guest
                    <x-forms.button href="{{ route('register') }}">
                        <i class="fa-solid fa-user-plus mr-2"></i>
                        Create a Free Account
                    </x-forms.button>
                @else
                    <x-forms.button href="{{ route('projects.create') }}">
                        <i class="fa-solid fa-plus mr-2"></i>
                        Create Your First Project
                    </x-forms.button>
                @endguest
            </div>
        </div>
    </div>
</x-layout>