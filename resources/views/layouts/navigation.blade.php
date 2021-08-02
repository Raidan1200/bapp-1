<nav x-data="{ open: false }" class="bg-white">
  <!-- Primary Navigation Menu -->
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between h-8 sm:h-12 lg:h-16">
      <div class="flex">
        <!-- Logo -->
        <div class="flex-shrink-0 flex items-center">
          <a
            href="{{ route('dashboard') }}"
            title="Übersicht"
          >
            <x-application-logo class="{{ request()->routeIs('dashboard') ? 'text-indigo-600' : 'text-gray-600' }} block h-6 sm:h-8 lg:h-10 w-auto fill-current" />
          </a>
        </div>

        <!-- Navigation Links -->
        <div class="flex space-x-6 sm:space-x-8 sm:ml-6 lg:ml-10">
          <x-nav-link
            class="hidden sm:flex"
            :href="route('dashboard')"
            :active="request()->routeIs('dashboard')"
            title="Übersicht"
          >
            Übersicht
          </x-nav-link>

          @can('modify venues')
            <x-nav-link
              :href="route('venues.index')"
              :active="request()->routeIs('venues.index')"
              title="Orte"
            >
              Orte
            </x-nav-link>
          @endcan

          @can('modify users')
            <x-nav-link
              :href="route('users.index')"
              :active="request()->routeIs('users.index')"
              title="Benutzer"
            >
              Benutzer
            </x-nav-link>
          @endcan
        </div>
      </div>

      <!-- Settings Dropdown -->
      <div class="hidden sm:flex sm:items-center sm:ml-6">
        <x-dropdown align="right" width="48">
          <x-slot name="trigger">
            <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
              <div>{{ Auth::user()->name }}</div>

              <div class="ml-1">
                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
              </div>
            </button>
          </x-slot>

          <x-slot name="content">
            <x-dropdown-link
              :href="route('profile.show')"
              title="Profil"
            >
              Profil
            </x-dropdown-link>

            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <x-dropdown-link
                :href="route('logout')"
                onclick="event.preventDefault(); this.closest('form').submit();"
                title="Abmelden"
              >
                Abmelden
              </x-dropdown-link>
            </form>
          </x-slot>
        </x-dropdown>
      </div>

      <!-- Hamburger -->
      <div class="flex sm:hidden items-center -mr-2">
        <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
          <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </div>
  </div>

  <!-- Responsive Navigation Menu -->
  <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">

    <!-- Responsive Settings Options -->
    <div class="border-t border-gray-200">

      <div class="px-4 my-2">
        <span class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</span>
        <span class="font-medium text-sm text-gray-500">({{ Auth::user()->email }})</span>
      </div>

      <div class="space-y-1">
        <x-responsive-nav-link
          :href="route('dashboard')"
          :active="request()->routeIs('dashboard')"
          title="Profil"
        >
          Profil
        </x-responsive-nav-link>
        <form method="POST" action="{{ route('logout') }}">
          @csrf

          <x-responsive-nav-link
            :href="route('logout')"
            onclick="event.preventDefault(); this.closest('form').submit();"
            title="Abmelden"
          >
            Abmelden
          </x-responsive-nav-link>
        </form>
      </div>
    </div>
  </div>
</nav>
