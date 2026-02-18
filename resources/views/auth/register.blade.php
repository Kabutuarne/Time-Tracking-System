<x-layout>
    <x-slot:title>Register an Account</x-slot:title>
    <div class="flex items-center justify-center min-h-[80vh]">
        <article class="w-full max-w-md group/card">
            <div class="relative rounded-xl bg-slate-950/40 p-8 shadow-xl transition-all duration-300">
                <h2
                    class="text-2xl font-bold text-center mb-8 bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">
                    Become a Member
                </h2>

                <form method="POST" action="/register" class="space-y-5">
                    @csrf

                    {{-- first name --}}
                    <div>
                        <x-forms.input id="first_name" type="text" name="first_name" :value="old('first_name')" required
                            autofocus autocomplete="firstname" placeholder="First Name" />
                        <x-forms.input-error :messages="$errors->get('first_name')" class="mt-2" />
                    </div>
                    {{-- last name --}}
                    <div>
                        <x-forms.input id="last_name" type="text" name="last_name" :value="old('last_name')" required
                            autofocus autocomplete="lastname" placeholder="Last Name" />
                        <x-forms.input-error :messages="$errors->get('last_name')" class="mt-2" />
                    </div>
                    {{-- username --}}
                    <div>
                        <x-forms.input id="username" type="text" name="username" :value="old('username')" required
                            autofocus autocomplete="username" placeholder="Username" />
                        <x-forms.input-error :messages="$errors->get('username')" class="mt-2" />
                    </div>

                    {{-- email --}}
                    <div>
                        <x-forms.input id="email" type="email" name="email" :value="old('email')" required autofocus
                            autocomplete="email" placeholder="example@email.com" />
                        <x-forms.input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    {{-- password --}}
                    <div>
                        <x-forms.input id="password" type="password" name="password" required
                            autocomplete="current-password" placeholder="••••••••" />
                        <x-forms.input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>
                    {{-- repeat password --}}
                    <div>
                        <x-forms.input id="password_confirmation" type="password" name="password_confirmation" required
                            autocomplete="current-password" placeholder="••••••••" />
                        <x-forms.input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>
                    {{-- remember me --}}
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember_me" type="checkbox" name="remember"
                                class="w-4 h-4 rounded bg-darker border-textcol/20 text-primary accent-primary focus:ring-primary cursor-pointer" />
                            <label for="remember_me"
                                class="ms-2 text-sm text-textcol/80 cursor-pointer">{{ __('Remember me') }}</label>
                        </div>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                                class="text-sm text-primary hover:text-secondary transition-colors">
                                Forgot password?
                            </a>
                        @endif
                    </div>

                    <!-- Buttons -->
                    <div class="space-y-3 pt-2">
                        <x-forms.button class="w-full">
                            Register
                        </x-forms.button>

                        <x-forms.button href="{{ route('login') }}" class="w-full" :secondary="true">
                            Already a Member?
                        </x-forms.button>
                    </div>
                </form>
            </div>
        </article>
    </div>
</x-layout>