
@props([
    'project' => $project
]);
@auth
    {{-- if is member --}}
    @if($project->users->contains(auth()->user()))
        <div class="ml-auto">
            <div class="flex items-center gap-1 rounded-full bg-primary/10 px-3 py-1">
                <svg stroke="currentColor" viewBox="0 0 24 24" fill="none" class="h-4 w-4 text-primary">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2" stroke-linejoin="round"
                        stroke-linecap="round"></path>
                </svg>
                {{-- Todo display specific role --}}
                <span class="text-xs font-medium text-primary">Project Member</span>
            </div>
        </div>
@elseif (auth()->user()->is($project->user))
        {{-- if is owner --}}
@endif
        <div class="ml-auto">
            <div class="flex items-center gap-1 rounded-full bg-primary/10 px-3 py-1">
                <svg stroke="currentColor" viewBox="0 0 24 24" fill="none" class="h-4 w-4 text-primary">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2" stroke-linejoin="round"
                        stroke-linecap="round"></path>
                </svg>
                {{-- Todo display specific role --}}
                <span class="text-xs font-medium text-primary">Project Owner</span>
            </div>
        </div>
@endauth
