<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    @include('partials.head')

    <style>
        [data-flux-navlist-item]:hover {
            color: #f97316 !important;
        }

        flux-menu a:hover,
        flux-menu button:hover {
            color: #f97316 !important;
        }

        flux-menu a:hover span,
        flux-menu button:hover span {
            color: #f97316 !important;
        }
    </style>
</head>

<body class="min-h-screen bg-white text-black dark:bg-zinc-800 dark:text-white">
<div x-data="{ open: false }" class="z-50">

    {{-- Mobile toggle button --}}
    <button
        x-on:click="open = true"
        class="fixed right-4 top-12 p-5 z-40"
    >
        <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    {{-- Overlay --}}
    <div
        x-cloak
        x-show="open"
        x-transition.opacity
        x-on:click="open = false"
        class="fixed inset-0 bg-black/40 z-30"
    ></div>

    {{-- Sidebar --}}
    <div
        class="fixed top-0 right-0 h-full w-72 z-40 transform transition-transform duration-300"
        :style="open ? 'transform: translateX(0);' : 'transform: translateX(100%);'"
    >
        <flux:sidebar
            sticky
            class="w-72 h-full bg-white dark:bg-zinc-900 border-l border-gray-300 dark:border-zinc-700 flex flex-col overflow-y-auto"
        >

            {{-- Close button --}}
            <div class="flex justify-end p-4">
                <button x-on:click="open = false">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" d="M6 6l12 12M6 18L18 6" />
                    </svg>
                </button>
            </div>

            {{-- Logo --}}
            <div class="px-4 mb-4">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 font-semibold" wire:navigate>
                    <x-app-logo />
                </a>
            </div>

            {{-- Scrollable main menu --}}
            <div class="px-4 flex-1">
                <p class="uppercase text-gray-600 dark:text-gray-300 font-semibold text-sm mb-2">Platform</p>

                <flux:navlist variant="outline" color="zinc" color-active="orange">
                    <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                        Dashboard
                    </flux:navlist.item>

                    <flux:navlist.item icon="truck" :href="route('trucks')" :current="request()->routeIs('trucks')" wire:navigate>
                        Trucks
                    </flux:navlist.item>

                    <flux:navlist.item icon="home-modern" :href="route('production-sites')" :current="request()->routeIs('production-sites')" wire:navigate>
                        Production Sites
                    </flux:navlist.item>

                    <flux:navlist.item icon="arrows-right-left" :href="route('delivery-companies')" :current="request()->routeIs('delivery-companies')" wire:navigate>
                        Delivery Companies
                    </flux:navlist.item>

                    <flux:navlist.item icon="credit-card" :href="route('delivery-companies')" :current="request()->routeIs('delivery-companies')" wire:navigate>
                        Credit Companies
                    </flux:navlist.item>
                </flux:navlist>

                <hr class="my-5 border-gray-300 dark:border-zinc-700">

                <p class="uppercase text-gray-600 dark:text-gray-300 font-semibold text-sm mb-2">Admin</p>

                <flux:navlist variant="ghost" color="zinc" color-active="orange">
                    <flux:navlist.item icon="user" :href="route('admin.users')" :current="request()->routeIs('admin.users')" wire:navigate>
                        Users
                    </flux:navlist.item>
                </flux:navlist>
            </div>


            <flux:navlist variant="outline">
                <flux:navlist.item icon="book-open-text" href="https://laravel.com/docs/starter-kits#livewire" target="_blank">
                    {{ __('Documentation') }}
                </flux:navlist.item>
            </flux:navlist>

            {{-- Profile dropdown (now visible on all screen sizes) --}}
            <flux:dropdown class="block lg:block" position="bottom" align="start">
                <flux:profile
                        :name="auth()->user()->name"
                        :initials="auth()->user()->initials()"
                        icon:trailing="chevrons-up-down"
                        data-test="sidebar-menu-button"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                            class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                            {{ __('Settings') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item
                                as="button"
                                type="submit"
                                icon="arrow-right-start-on-rectangle"
                                class="w-full"
                                data-test="logout-button"
                        >
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>

        </flux:sidebar>
    </div>
</div>

{{ $slot }}

@fluxScripts
</body>
</html>
