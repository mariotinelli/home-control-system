<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1">
    <meta name="csrf-token"
          content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect"
          href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap"
          rel="stylesheet"/>

    <link rel="stylesheet"
          href="https://unpkg.com/tippy.js@6/dist/tippy.css"/>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles

    @wireUiScripts

    @livewireScripts

    @stack('scripts')
</head>

<body
    class="font-sans antialiased"
    x-data
    :class="$store.darkMode.on ? 'dark bg-gray-900' : 'bg-gray-100'"
>

<div class="min-h-screen min-w-screen flex">

    <livewire:app.sidebar/>

    <main class="flex flex-col flex-1 h-fit max-h-screen">

        <livewire:app.navbar/>

        <div class="p-6 w-full h-[calc(100vh-64px)] overflow-y-auto">
            {{ $slot }}
        </div>

    </main>

</div>

@livewire('notifications')

</body>

</html>
