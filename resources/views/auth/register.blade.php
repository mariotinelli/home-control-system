<x-guest-layout>
    <form method="POST"
          action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-wireui-input
                label="Nome"
                id="name"
                class="block mt-1 w-full"
                type="text"
                name="name"
                :value="old('name')"
                autofocus
                autocomplete="name"
            />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-wireui-input
                label="E-mail"
                id="email"
                class="block mt-1 w-full"
                type="text"
                name="email"
                :value="old('email')"
                autofocus
                autocomplete="email"
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
                autocomplete="new-password"
            />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-wireui-input
                label="Confirmar senha"
                id="password_confirmation"
                class="block mt-1 w-full"
                type="password"
                name="password_confirmation"
                autocomplete="new-password"
            />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
               href="{{ route('login') }}">
                {{ __('Já possui cadastro?') }}
            </a>

            <x-primary-button class="ml-4">
                {{ __('Cadastrar') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
