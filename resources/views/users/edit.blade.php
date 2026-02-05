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
                <div class="p-10 space-y-8">

                    {{-- Tab buttons --}}
                    <div class="flex gap-3 border-b border-white/5 pb-4">
                        <button class="text-primary border-primary border-b-2 pb-2 font-semibold transition">
                            Edit Profile
                        </button>
                    </div>

                    {{-- USER INFO --}}
                    <div>
                        <div class="grid grid-cols-2 md:grid-cols-2 gap-20">
                            <form method="POST" action="{{ route('users.update', $user) }}">
                                @csrf
                                @method('PUT')
                                <div>
                                    {{-- description --}}
                                    <div class="mt-4 w-full">
                                        <label class="block text-sm font-semibold text-textcol2 mb-2">
                                            Username
                                        </label>
                                        <x-forms.input name="username" required maxlength="100"
                                            value="{{ old('username', $user->username) }}" />
                                        <x-forms.input-error :messages="$errors->get('username')" class="mt-2" />
                                    </div>

                                    <div class="mt-4 w-full">
                                        <label class="block text-sm font-semibold text-textcol2 mb-2">
                                            Name
                                        </label>
                                        <x-forms.input name="first_name" required maxlength="100"
                                            value="{{ old('first_name', $user->first_name) }}" />
                                        <x-forms.input-error :messages="$errors->get('first_name')" class="mt-2" />
                                    </div>
                                    <div class="mt-4 w-full">
                                        <label class="block text-sm font-semibold text-textcol2 mb-2">
                                            Last
                                        </label>
                                        <x-forms.input name="last_name" required maxlength="100"
                                            value="{{ old('last_name', $user->last_name) }}" />
                                        <x-forms.input-error :messages="$errors->get('last_name')" class="mt-2" />
                                    </div>
                                </div>
                                <div class="flex flex-wrap gap-3 justify-start mt-6">
                                    <x-forms.button type="submit">
                                        <i class="fas fa-save mr-2"></i>
                                        Save Changes
                                    </x-forms.button>

                                    <x-forms.button :secondary="true" href="{{ route('users.show', $user) }}">
                                        <i class="fas fa-cancel mr-2"></i>
                                        Cancel
                                    </x-forms.button>

                                </div>
                            </form>
                            <div>
                                <h3 class="text-lg font-semibold text-textcol mb-4">Account Information</h3>
                                <ul class="space-y-2 text-textcol2 mt-4">

                                    <li><strong class="text-textcol">Joined </strong>
                                        {{ $user->created_at->toFormattedDateString() }}</li>
                                    <li><strong class="text-textcol">Email </strong>
                                        {{ $user->email }}</li>
                                    <li><strong class="text-textcol">Last made changes </strong>
                                        {{ $user->updated_at->toFormattedDateString() }}</li>
                                </ul>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>