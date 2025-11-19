<x-layouts.app :title="__('Professor Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="flex items-center gap-4">
            <flux:heading size="lg">{{ __('app.professor_dashboard') }}</flux:heading>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                <span class="text-white text-sm font-bold">T</span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">{{ __('app.supervised_theses') }}</dt>
                            <dd class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ number_format($stats['supervised_theses']) }}</dd>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                <span class="text-white text-sm font-bold">J</span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">{{ __('app.jury_memberships') }}</dt>
                            <dd class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ number_format($stats['jury_memberships']) }}</dd>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                                <span class="text-white text-sm font-bold">D</span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">{{ __('app.uploaded_documents') }}</dt>
                            <dd class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ number_format($stats['uploaded_documents']) }}</dd>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-orange-500 rounded-md flex items-center justify-center">
                                <span class="text-white text-sm font-bold">U</span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">{{ __('app.upcoming_defenses') }}</dt>
                            <dd class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ number_format($stats['upcoming_defenses']) }}</dd>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Supervised Theses -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium">{{ __('app.recent_supervised_theses') }}</h3>
                        <a href="{{ route('thesis-defense-reports.index') }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">{{ __('app.view_all') }}</a>
                    </div>

                    @if($recentTheses->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentTheses as $thesis)
                                <div class="flex items-center justify-between py-2">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $thesis->title }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            Student: {{ $thesis->student->user->name }}
                                        </p>
                                    </div>
                                    <span class="px-2 py-1 text-xs rounded-full
                                        @if($thesis->status === 'scheduled') bg-yellow-100 text-yellow-800
                                        @elseif($thesis->status === 'completed') bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800 @endif">
                                        @if($thesis->status === 'scheduled'){{ __('app.scheduled') }}
                                        @elseif($thesis->status === 'completed'){{ __('app.completed') }}
                                        @else{{ __('app.pending') }}@endif
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400 text-center py-4">{{ __('app.no_supervised_theses_yet') }}</p>
                    @endif
                </div>
            </div>

            <!-- Upcoming Defenses -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium">{{ __('app.upcoming_defenses') }}</h3>
                        <a href="{{ route('thesis-defense-reports.index') }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">{{ __('app.view_all') }}</a>
                    </div>

                    @if($upcomingDefenses->count() > 0)
                        <div class="space-y-4">
                            @foreach($upcomingDefenses as $defense)
                                <div class="flex items-center justify-between py-2">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $defense->title }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            Student: {{ $defense->student->user->name }} • {{ $defense->defense_date->format('M d, Y') }} at {{ $defense->defense_time }}
                                        </p>
                                    </div>
                                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">
                                        {{ __('app.scheduled') }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400 text-center py-4">{{ __('app.no_upcoming_defenses') }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-medium mb-4">{{ __('app.quick_actions') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('thesis-defense-reports.create') }}" class="block p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-500 rounded-md flex items-center justify-center mr-3">
                                <span class="text-white text-lg">+</span>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900 dark:text-gray-100">{{ __('app.schedule_defense') }}</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('app.create_new_thesis_defense') }}</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('documents.create') }}" class="block p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-green-500 rounded-md flex items-center justify-center mr-3">
                                <span class="text-white text-lg">📄</span>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900 dark:text-gray-100">{{ __('app.upload_document') }}</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('app.add_new_document') }}</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('students.index') }}" class="block p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-purple-500 rounded-md flex items-center justify-center mr-3">
                                <span class="text-white text-lg">👥</span>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900 dark:text-gray-100">{{ __('app.view_students') }}</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('app.manage_student_information') }}</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>