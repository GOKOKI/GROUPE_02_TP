<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased font-sans bg-zinc-50 text-zinc-900 dark:bg-zinc-950 dark:text-zinc-100 selection:bg-red-500 selection:text-white">
        <div class="min-h-screen flex flex-col">
            <!-- Navbar -->
            <header class="w-full py-6 px-6 lg:px-8">
                <nav class="flex items-center justify-between max-w-7xl mx-auto">
                    <div class="flex items-center gap-2 font-bold text-xl tracking-tight">
                        <div class="p-2 bg-red-600 text-white rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                            </svg>
                        </div>
                        <span>ESGIS<span class="text-red-600">Manager</span></span>
                    </div>

                    <div class="flex items-center gap-4">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white transition">
                                    {{ __('Dashboard') }}
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="text-sm font-medium text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white transition">
                                    {{ __('Log in') }}
                                </a>

                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="text-sm font-medium bg-zinc-900 text-white px-4 py-2 rounded-lg hover:bg-zinc-800 transition dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200">
                                        {{ __('Register') }}
                                    </a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </nav>
            </header>

            <!-- Hero Section -->
            <main class="flex-grow flex items-center justify-center px-6 lg:px-8 py-12 lg:py-0">
                <div class="max-w-7xl mx-auto w-full grid lg:grid-cols-2 gap-12 lg:gap-24 items-center">
                    <div class="space-y-8">
                        <div class="space-y-4">
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 text-xs font-medium uppercase tracking-wide">
                                <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                Gestion Administrative Simplifiée
                            </div>
                            <h1 class="text-4xl lg:text-6xl font-bold tracking-tight leading-tight">
                                Gérez les soutenances <br class="hidden lg:block" />
                                <span class="text-zinc-400 dark:text-zinc-600">avec efficacité.</span>
                            </h1>
                            <p class="text-lg text-zinc-600 dark:text-zinc-400 leading-relaxed max-w-xl">
                                Une plateforme centralisée pour la gestion des documents de soutenance,
                                le suivi des dossiers étudiants et la coordination des jurys.
                            </p>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="inline-flex justify-center items-center px-6 py-3 text-base font-medium rounded-xl text-white bg-red-600 hover:bg-red-700 transition shadow-lg shadow-red-500/20">
                                    Accéder au Tableau de Bord
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 ml-2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                    </svg>
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="inline-flex justify-center items-center px-6 py-3 text-base font-medium rounded-xl text-white bg-red-600 hover:bg-red-700 transition shadow-lg shadow-red-500/20">
                                    Commencer
                                </a>
                                <a href="#features" class="inline-flex justify-center items-center px-6 py-3 text-base font-medium rounded-xl text-zinc-700 bg-zinc-100 hover:bg-zinc-200 dark:text-zinc-300 dark:bg-zinc-800 dark:hover:bg-zinc-700 transition">
                                    En savoir plus
                                </a>
                            @endauth
                        </div>

                        <div class="pt-8 border-t border-zinc-200 dark:border-zinc-800 flex items-center gap-8 text-sm text-zinc-500">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span>Sécurisé</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span>Rapide</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span>Intuitif</span>
                            </div>
                        </div>
                    </div>

                    <div class="relative hidden lg:block">
                        <!-- Abstract Graphic Representation -->
                        <div class="absolute -top-12 -right-12 w-72 h-72 bg-red-400/10 rounded-full blur-3xl"></div>
                        <div class="absolute -bottom-12 -left-12 w-72 h-72 bg-red-600/10 rounded-full blur-3xl"></div>

                        <div class="relative bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl shadow-2xl p-6 rotate-2 hover:rotate-0 transition duration-500">
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-zinc-100 dark:bg-zinc-800 rounded-full flex items-center justify-center text-xl">🎓</div>
                                    <div>
                                        <div class="h-2.5 w-24 bg-zinc-200 dark:bg-zinc-700 rounded-full mb-1.5"></div>
                                        <div class="h-2 w-16 bg-zinc-100 dark:bg-zinc-800 rounded-full"></div>
                                    </div>
                                </div>
                                <div class="px-3 py-1 bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 text-xs rounded-full font-medium">Validé</div>
                            </div>
                            <div class="space-y-3">
                                <div class="h-20 w-full bg-zinc-50 dark:bg-zinc-800/50 rounded-lg border border-zinc-100 dark:border-zinc-800"></div>
                                <div class="flex justify-between gap-4">
                                    <div class="h-8 w-full bg-zinc-100 dark:bg-zinc-800 rounded-lg"></div>
                                    <div class="h-8 w-full bg-red-600 rounded-lg opacity-80"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <!-- Footer -->
            <footer class="py-8 text-center text-sm text-zinc-500 dark:text-zinc-600">
                <p>&copy; {{ date('Y') }} devalade. Tous droits réservés.</p>
            </footer>
        </div>
    </body>
</html>
