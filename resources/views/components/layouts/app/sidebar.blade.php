<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')

    <script src="//unpkg.com/alpinejs" defer></script>

    <style>
        [data-flux-navlist-item] {
            color: #000 !important;
        }

        [data-flux-navlist-item]:hover {
            color: #f97316 !important;
        }

        [data-flux-navlist-item][data-current="true"] {
            color: #f97316 !important;
            font-weight: 600;
        }

        flux-profile,
        flux-sidebar-profile,
        [data-flux-profile],
        [data-flux-sidebar-profile] {
            color: #000 !important;
        }

        flux-sidebar-profile,
        [data-flux-sidebar-profile] {
            --color-foreground: #000 !important;
            --color-sidebar-foreground: #000 !important;
            --color-text: #000 !important;
            color: #000 !important;
        }

        flux-sidebar-profile span,
        [data-flux-sidebar-profile] span {
            color: #000 !important;
        }

        flux-menu,
        [data-flux-menu] {
            background-color: #fff !important;
            color: #000 !important;
            border: 1px solid #e5e7eb !important;
        }

        flux-menu *,
        [data-flux-menu] * {
            color: #000 !important;
        }

        flux-menu a span,
        flux-menu button span {
            color: #000 !important;
        }

        flux-menu span.bg-neutral-200 {
            background-color: #e5e5e5 !important;
            color: #000 !important;
        }


        flux-menu a:hover,
        flux-menu button:hover,
        [data-flux-menu] a:hover,
        [data-flux-menu] button:hover {
            background-color: #fff !important;
            color: #f97316 !important;
        }

        flux-menu a:hover span,
        flux-menu button:hover span,
        [data-flux-menu] a:hover span,
        [data-flux-menu] button:hover span {
            color: #f97316 !important;
        }
    </style>
</head>

<body class="min-h-screen bg-white text-black">
<div x-data="{ open: false }">


    <button
            x-on:click="open = true"
            class="fixed right-4 top-1 z-50 p-2"
    >
        <svg class="w-8 h-8" fill="none" stroke="black" stroke-width="2">
            <path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>


    <div
            x-show="open"
            x-transition.opacity
            x-on:click="open = false"
            class="fixed inset-0 bg-black/40 z-40"
    ></div>


    <div
            class="fixed top-0 right-0 h-full w-72 z-50 transform transition-transform duration-300"
            :style="open ? 'transform: translateX(0);' : 'transform: translateX(100%);'"
    >
        <flux:sidebar
                sticky
                stashable
                class="w-72 h-full bg-white border-l border-gray-300 flex flex-col text-black"
        >

            <div class="flex justify-end p-4">
                <button x-on:click="open = false">
                    <svg class="w-8 h-8" fill="none" stroke="black" stroke-width="2">
                        <path stroke-linecap="round" d="M6 6l12 12M6 18L18 6" />
                    </svg>
                </button>
            </div>

            <div class="px-4 mb-4">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 text-black font-semibold" wire:navigate>
                    <x-app-logo />
                </a>
            </div>

            <div class="px-4 flex-1 overflow-y-auto">

                <p class="uppercase text-gray-600 font-semibold text-sm mb-2">Platform</p>

                <flux:navlist variant="outline" color="zinc" color-active="orange" class="text-black">
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

                <hr class="my-5 border-gray-300">

                <p class="uppercase text-gray-600 font-semibold text-sm mb-2">Admin</p>

                <flux:navlist variant="ghost" color="zinc" color-active="orange">
                    <flux:navlist.item icon="user" :href="route('admin.users')" :current="request()->routeIs('admin.users')" wire:navigate>
                        Users
                    </flux:navlist.item>
                </flux:navlist>

                <hr class="my-5 border-gray-300">

                <flux:navlist variant="outline">
                    <flux:navlist.item icon="book-open-text" href="https://laravel.com/docs/starter-kits#livewire" target="_blank">
                        Documentation
                    </flux:navlist.item>
                </flux:navlist>

            </div>

            <div class="border-t border-gray-300 p-4 text-black">
                <flux:dropdown align="start" position="top" class="text-black">
                    <flux:sidebar.profile
                            :name="auth()->user()->name"
                            :initials="auth()->user()->initials()"
                            icon:trailing="chevrons-up-down"
                            class="text-black"
                    />

                    <flux:menu class="w-[240px] bg-white text-black border border-gray-200 shadow-lg">

                        <flux:menu.radio.group>
                            <div class="p-0 text-sm font-normal">
                                <div class="flex items-center gap-2 px-1 py-1.5">
                                    <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                        <span class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black">
                                            {{ auth()->user()->initials() }}
                                        </span>
                                    </span>

                                    <div class="grid flex-1 text-sm leading-tight">
                                        <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                        <span class="truncate text-xs text-gray-600">{{ auth()->user()->email }}</span>
                                    </div>
                                </div>
                            </div>
                        </flux:menu.radio.group>

                        <flux:menu.separator />

                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                            Settings
                        </flux:menu.item>

                        <flux:menu.separator />

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle">
                                Log Out
                            </flux:menu.item>
                        </form>

                    </flux:menu>

                </flux:dropdown>
            </div>

        </flux:sidebar>
    </div>
</div>

{{ $slot }}

@fluxScripts
</body>
</html>
