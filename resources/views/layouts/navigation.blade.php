<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-sm sticky top-0 z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-2">
                        <span class="flex size-9 items-center justify-center rounded-xl bg-blue-600 text-white shadow-sm font-bold">
                            PK
                        </span>
                        <span class="font-bold text-slate-800 text-lg hidden sm:inline">Perpus <span class="text-blue-600">Kampus</span></span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-6 sm:-my-px sm:ms-8 sm:flex items-center text-sm">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                        {{ __('Beranda') }}
                    </x-nav-link>

                    <x-nav-link :href="route('buku.index')" :active="request()->routeIs('buku.*')">
                        {{ __('Katalog Buku') }}
                    </x-nav-link>

                    @auth
                        @can('akses dashboard admin')
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                                {{ __('Dashboard Admin') }}
                            </x-nav-link>
                        @endcan

                        @can('akses dashboard petugas')
                            <x-nav-link :href="route('petugas.dashboard')" :active="request()->routeIs('petugas.dashboard')">
                                {{ __('Dashboard Petugas') }}
                            </x-nav-link>
                        @endcan

                        @can('kelola data pengguna')
                            <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                                {{ __('Kelola Pengguna') }}
                            </x-nav-link>
                        @endcan

                        @can('kelola peminjaman')
                            <x-nav-link :href="route('peminjaman.index')" :active="request()->routeIs('peminjaman.index')">
                                {{ __('Peminjaman & Kembali') }}
                            </x-nav-link>
                        @endcan

                        @can('melihat riwayat peminjaman sendiri')
                            @if(auth()->user()->hasRole('siswa'))
                                <x-nav-link :href="route('riwayat.index')" :active="request()->routeIs('riwayat.*')">
                                    {{ __('Riwayat Saya') }}
                                </x-nav-link>
                            @endif
                        @endcan

                        @can('lihat laporan perpustakaan')
                            <x-nav-link :href="route('laporan.index')" :active="request()->routeIs('laporan.*')">
                                {{ __('Laporan') }}
                            </x-nav-link>
                        @endcan
                    @endauth
                </div>
            </div>

            <!-- Settings / Auth Buttons -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center gap-2 px-3 py-2 border border-slate-200 text-sm leading-4 font-semibold rounded-lg text-slate-700 bg-white hover:bg-slate-50 focus:outline-none transition ease-in-out duration-150">
                                <span>{{ Auth::user()->name }}</span>
                                <span class="text-xs px-2 py-0.5 rounded-full font-bold uppercase {{ Auth::user()->hasRole('admin') ? 'bg-purple-100 text-purple-700' : (Auth::user()->hasRole('petugas') ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700') }}">
                                    {{ Auth::user()->roles->pluck('name')->first() ?? 'Member' }}
                                </span>
                                <svg class="fill-current h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profil Saya') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Keluar (Log Out)') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="flex items-center gap-3">
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-600 hover:text-blue-600 px-3 py-2">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-lg shadow-sm">
                            Daftar
                        </a>
                    </div>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
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
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-t border-gray-100">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                {{ __('Beranda') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('buku.index')" :active="request()->routeIs('buku.*')">
                {{ __('Katalog Buku') }}
            </x-responsive-nav-link>

            @auth
                @can('akses dashboard admin')
                    <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                        {{ __('Dashboard Admin') }}
                    </x-responsive-nav-link>
                @endcan

                @can('akses dashboard petugas')
                    <x-responsive-nav-link :href="route('petugas.dashboard')" :active="request()->routeIs('petugas.dashboard')">
                        {{ __('Dashboard Petugas') }}
                    </x-responsive-nav-link>
                @endcan

                @can('kelola data pengguna')
                    <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                        {{ __('Kelola Pengguna') }}
                    </x-responsive-nav-link>
                @endcan

                @can('kelola peminjaman')
                    <x-responsive-nav-link :href="route('peminjaman.index')" :active="request()->routeIs('peminjaman.index')">
                        {{ __('Peminjaman & Kembali') }}
                    </x-responsive-nav-link>
                @endcan

                @can('melihat riwayat peminjaman sendiri')
                    <x-responsive-nav-link :href="route('riwayat.index')" :active="request()->routeIs('riwayat.*')">
                        {{ __('Riwayat Peminjaman Saya') }}
                    </x-responsive-nav-link>
                @endcan

                @can('lihat laporan perpustakaan')
                    <x-responsive-nav-link :href="route('laporan.index')" :active="request()->routeIs('laporan.*')">
                        {{ __('Laporan Perpustakaan') }}
                    </x-responsive-nav-link>
                @endcan
            @endauth
        </div>

        @auth
            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profil Saya') }}
                    </x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Keluar (Log Out)') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @else
            <div class="p-4 border-t border-gray-200 space-y-2">
                <a href="{{ route('login') }}" class="block text-center font-medium text-blue-600 bg-blue-50 py-2 rounded-lg">Masuk</a>
                <a href="{{ route('register') }}" class="block text-center font-medium text-white bg-blue-600 py-2 rounded-lg">Daftar</a>
            </div>
        @endauth
    </div>
</nav>
