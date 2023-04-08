<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4"
                           :status="session('status')"/>

    <form method="POST"
          action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-wireui-input
                label="E-mail"
                id="email"
                class="block mt-1 w-full"
                type="email"
                name="email"
                :value="old('email')"
                autocomplete="username"
            />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-wireui-input
                label="Senha"
                id="password"
                class="block mt-1 w-full"
                type="password"
                name="password"
                autocomplete="current-password"
            />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me"
                   class="inline-flex items-center">
                <input id="remember_me"
                       type="checkbox"
                       class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                       name="remember">
                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400"> Lembrar-me </span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                   href="{{ route('password.request') }}">
                    Esqueceu sua senha?
                </a>
            @endif

            <x-primary-button class="ml-3">
                Entrar
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
