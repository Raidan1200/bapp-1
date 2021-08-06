<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @livewireStyles

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
  </head>
  <body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
      @include('layouts.navigation')

      <main class="max-w-7xl mx-auto sm:p-6 lg:p-8">

        <x-session-status :status="session('status')"></x-session-status>
        <x-session-error :status="session('error')"></x-session-error>

      	<div class="bg-white sm:rounded-xl p-1 sm:p-4 lg:p-6">
          {{ $slot }}
        </div>
      </main>
    </div>

    <div
      x-data="{
        isOpen: false,
        route: '',
        entity: '',
        text: '',
      }"
      @open-delete-modal.window="
        isOpen = true
        route = $event.detail.route
        entity = $event.detail.entity
        subText = $event.detail.text
      "
    >
      <x-confirm />
    </div>

    @livewireScripts
  </body>
</html>
