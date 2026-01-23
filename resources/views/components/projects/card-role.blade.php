@props([
    'project' => $project
])
@auth
    @if($project->users->contains(auth()->user()))
        <div class="flex items-center gap-1 rounded-full bg-green-500/10 px-2.5 py-1 shrink-0">
            <i class="fa-solid fa-user-check text-green-400 text-xs"></i>
            <span class="text-xs font-medium text-green-400">Member</span>
        </div>
    @elseif(auth()->user()->is($project->user))
        <div class="flex items-center gap-1 rounded-full bg-indigo-500/10 px-2.5 py-1 shrink-0">
            <i class="fa-solid fa-crown text-indigo-400 text-xs"></i>
            <span class="text-xs font-medium text-indigo-400">Owner</span>
        </div>
    @endif
@endauth