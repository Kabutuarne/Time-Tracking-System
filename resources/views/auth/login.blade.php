<x-guest-layout>
    <div class="flex items-center justify-center min-h-[80vh]">
        <article class="w-full max-w-md group/card">
            <div class="relative rounded-xl bg-slate-950/40 p-8 shadow-xl transition-all duration-300">
                <h2
                    class="text-2xl font-bold text-center mb-8 bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent">
                    Welcome Back
                </h2>

                <form method="POST" action="/login" class="space-y-5">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <x-forms.input id="email" type="email" name="email" :value="old('email')" required autofocus
                            autocomplete="username" placeholder="example@email.com" />
                        <x-forms.input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div>
                        <x-forms.input id="password" type="password" name="password" required
                            autocomplete="current-password" placeholder="••••••••" />
                        <x-forms.input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me -->
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
                            Log in
                        </x-forms.button>

                        <x-forms.button href="{{ route('register') }}" class="w-full" :secondary="true">
                            Not a User?
                        </x-forms.button>
                    </div>
                </form>
            </div>
        </article>
    </div>
</x-guest-layout>