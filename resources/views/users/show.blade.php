<x-layout>
    <x-slot:title>{{ $user->username }}</x-slot:title>

    <div class="relative min-h-screen w-full bg-darker overflow-hidden">
        {{-- ambient blobs, because aesthetics --}}
        <div
            class="absolute -left-32 -top-32 h-96 w-96 rounded-full bg-gradient-to-br from-indigo-500/20 to-purple-500/0 blur-3xl">
        </div>
        <div
            class="absolute -right-32 -bottom-32 h-96 w-96 rounded-full bg-gradient-to-br from-purple-500/20 to-indigo-500/0 blur-3xl">
        </div>

        <div class="relative mx-auto max-w-7xl px-6 py-16">
            <div class="rounded-3xl bg-darker shadow-2xl ring-1 ring-white/5">

                {{-- Header --}}
                <div class="border-b border-white/5 p-10">
                    <div class="flex items-center gap-4">
                        <div class="h-14 w-14 rounded-full bg-primary/10 flex items-center justify-center">
                            <span class="text-lg font-bold text-primary">
                                {{ substr($user->username, 0, 2) }}
                            </span>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-textcol">
                                {{ $user->username }}
                            </h1>
                        </div>
                    </div>
                </div>

                {{-- Tabs --}}
                <div x-data="{ tab: 'info' }" class="p-10 space-y-8">

                    {{-- Tab buttons --}}
                    <div class="flex gap-3 border-b border-white/5 pb-4">
                        <button @click="tab = 'info'"
                            :class="tab === 'info' ? 'text-primary border-primary' : 'text-textcol2 border-transparent'"
                            class="border-b-2 pb-2 font-semibold transition">
                            User Info
                        </button>

                        <button @click="tab = 'projects'"
                            :class="tab === 'projects' ? 'text-primary border-primary' : 'text-textcol2 border-transparent'"
                            class="border-b-2 pb-2 font-semibold transition">
                            Projects
                        </button>

                        <button @click="tab = 'summary'"
                            :class="tab === 'summary' ? 'text-primary border-primary' : 'text-textcol2 border-transparent'"
                            class="border-b-2 pb-2 font-semibold transition">
                            Statistics
                        </button>
                    </div>

                    {{-- USER INFO --}}
                    <div x-show="tab === 'info'" x-cloak class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="rounded-xl bg-slate-950/40 p-6 ring-1 ring-white/5">
                                <h3 class="text-lg font-semibold text-textcol mb-4">Account</h3>
                                <ul class="space-y-2 text-sm text-textcol2">
                                    <li><strong class="text-textcol">Full name </strong> {{ $user->first_name }}
                                        {{ $user->last_name }}
                                    </li>
                                    <li><strong class="text-textcol">Email </strong> {{ $user->email }}</li>
                                    <li><strong class="text-textcol">Joined </strong>
                                        {{ $user->created_at->toFormattedDateString() }}</li>
                                </ul>
                            </div>

                            <div class="rounded-xl bg-slate-950/40 p-6 ring-1 ring-white/5">
                                <h3 class="text-lg font-semibold text-textcol mb-4">Actions</h3>
                                <div class="flex flex-wrap gap-3">
                                    <x-forms.button href="#">
                                        Edit Profile
                                    </x-forms.button>
                                    <x-forms.button :secondary="true" href="#">
                                        Change Password
                                    </x-forms.button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- PROJECTS --}}
                    <div x-show="tab === 'projects'" x-cloak class="space-y-8">

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                            {{-- Owned projects --}}
                            <div class="space-y-4">
                                <h2 class="text-2xl font-bold text-textcol">Owned Projects</h2>

                                @forelse ($ownedProjects as $project)
                                    <x-users.compact-project-card :project="$project" />
                                @empty
                                    <div class="rounded-xl bg-slate-950/40 p-6 text-center ring-1 ring-white/5">
                                        <p class="text-slate-400 text-sm">You have not created any projects.</p>
                                    </div>
                                @endforelse
                            </div>

                            {{-- Member of projects --}}
                            <div class="space-y-4">
                                <h2 class="text-2xl font-bold text-textcol">Part of Projects </h2>

                                @forelse ($memberProjects as $project)
                                    <x-users.compact-project-card :project="$project" />
                                @empty
                                    <div class="rounded-xl bg-slate-950/40 p-6 text-center ring-1 ring-white/5">
                                        <p class="text-slate-400 text-sm">You are not part of any projects.</p>
                                    </div>
                                @endforelse
                            </div>

                        </div>
                    </div>

                    {{-- SUMMARY --}}
                    <div x-show="tab === 'summary'" id="app" x-cloak>
                        <weekly-worked-time :weekly-work='@json($weeklyWork)' />

                        {{-- <div class="rounded-xl bg-slate-950/40 p-10 text-center ring-1 ring-white/5">
                            <i class="fa-solid fa-chart-line text-4xl text-slate-600 mb-4"></i>
                            <p class="text-slate-400">
                                Statistics and stuff.
                            </p>
                        </div> --}}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-layout>