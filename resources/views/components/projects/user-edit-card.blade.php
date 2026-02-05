@props([
    'user',
    'userStats',
    'role',
    'project',
])
<div class="group relative overflow-hidden rounded-xl bg-slate-950/40 ring-1 ring-white/5 transition-all duration-300 hover:ring-primary/30">
    <div class="absolute -right-8 -top-8 h-24 w-24 rounded-full bg-gradient-to-br from-primary/10 to-secondary/0 blur-2xl transition-all duration-500 group-hover:scale-150 group-hover:opacity-70"></div>
    
    <div class="relative p-4">
        <div class="flex items-start justify-between gap-3">
            <div class="flex items-center gap-2 flex-1">
                <div class="h-8 w-8 rounded-full bg-primary/10 flex items-center justify-center">
                                <span class="text-xs font-semibold text-primary">
                        {{ substr($user->username, 0, 2) }}
                    </span>
                </div>                <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-textcol truncate">{{ $user->username }}</p>
                        <p class="text-xs text-slate-400 truncate">{{ $user->first_name }} {{ $user->last_name }}</p>
                </div>
    </div>    
        @if ($role != 'owner' )
            <form method="POST" action="{{ route('projects.users.update', [$project, $user]) }}" class="">
            @csrf
            @method('PUT')
            <x-forms.select-dropdown
            name="role"
            :selected="$role"
            :options="['manager' => 'Manager', 'member' => 'Member']"
            onchange="this.form.submit()"/>
            </form>
        @endif                       
        </div>
            <div class="mt-3 flex items-center justify-between border-t border-white/5 pt-3">
                <div class="flex items-center gap-4 text-xs">
                    <div class="flex items-center gap-1.5">
                    <i class="fa-solid fa-clock text-primary text-xs"></i>
                    <span class="text-sm font-semibold text-primary">{{ $userStats[$user->id]->entry_count ?? 0 }}</span>
                </div>
    <div class="flex items-center gap-1.5">
        <i class="fa-solid fa-hourglass-half text-primary text-xs"></i>
                    <span class="text-sm font-semibold text-primary"><x-minutes-to-hours minutes="{{ $userStats[$user->id]->total_minutes ?? 0 }}" /></span>
                </div>
            </div>
            <div class="flex gap-3">
                <x-forms.sm-button :href="route('users.show', $user)">View</x-forms.sm-button>
                <form method="POST" action="{{ route('projects.users.destroy', [$project, $user]) }}">
                    @csrf
                    @method('DELETE')
                    <x-forms.sm-button :secondary="true">Kick</x-forms.sm-button>
                </form>
            </div>
        </div>
    </div>
</div>