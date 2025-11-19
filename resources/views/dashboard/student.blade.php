<x-layouts.app :title="__('Student Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="flex items-center gap-4">
            <flux:heading size="lg">{{ __('app.student_dashboard') }}</flux:heading>
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
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">{{ __('app.thesis_defenses') }}</dt>
                            <dd class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ number_format($stats['thesis_defenses']) }}</dd>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
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
                            <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                                <span class="text-white text-sm font-bold">C</span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">{{ __('app.completed_defenses') }}</dt>
                            <dd class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ number_format($stats['completed_defenses']) }}</dd>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-orange-500 rounded-md flex items-center justify-center">
                                <span class="text-white text-sm font-bold">P</span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">{{ __('app.pending_defenses') }}</dt>
                            <dd class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $stats['thesis_defenses'] - $stats['completed_defenses'] }}</dd>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thesis Defense History -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium">{{ __('app.my_thesis_defenses') }}</h3>
                    <a href="{{ route('thesis-defense-reports.index') }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">{{ __('app.view_all') }}</a>
                </div>

                @if($defenseHistory->count() > 0)
                    <div class="space-y-4">
                        @foreach($defenseHistory as $defense)
                            <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3">
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $defense->title }}</h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ __('app.supervisor') }}: {{ $defense->supervisor->user->name }} • {{ $defense->defense_date->format('M d, Y') }} at {{ $defense->defense_time }}
                                            </p>
                                            @if($defense->room)
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('app.room') }}: {{ $defense->room }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    @if($defense->final_grade)
                                        <div class="mt-2">
                                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ __('app.final_grade') }}: {{ number_format($defense->final_grade, 2) }}/20
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="px-2 py-1 text-xs rounded-full
                                        @if($defense->status === 'scheduled') bg-yellow-100 text-yellow-800
                                        @elseif($defense->status === 'completed') bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800 @endif">
                                        @if($defense->status === 'scheduled'){{ __('app.scheduled') }}
                                        @elseif($defense->status === 'completed'){{ __('app.completed') }}
                                        @else{{ __('app.pending') }}@endif
                                    </span>
                                    <a href="{{ route('thesis-defense-reports.show', $defense) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm">{{ __('app.view_details') }}</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl">🎓</span>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">{{ __('app.no_thesis_defenses_yet') }}</h3>
                        <p class="text-gray-500 dark:text-gray-400">{{ __('app.thesis_defense_schedule_will_appear_here') }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Documents -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium">{{ __('app.my_recent_documents') }}</h3>
                    <a href="{{ route('documents.index') }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">{{ __('app.view_all') }}</a>
                </div>

                @if($recentDocuments->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($recentDocuments as $document)
                            <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $document->title }}</h4>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            {{ $document->type ?? 'Document' }} • {{ $document->created_at->diffForHumans() }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ number_format($document->file_size / 1024, 1) }} KB
                                        </p>
                                    </div>
                                    <a href="{{ route('documents.download', $document) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm ml-2">
                                        {{ __('app.download') }}
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl">📄</span>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">{{ __('app.no_documents_yet') }}</h3>
                        <p class="text-gray-500 dark:text-gray-400">{{ __('app.upload_thesis_documents_cv') }}</p>
                        <a href="{{ route('documents.create') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('app.upload_document') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-medium mb-4">{{ __('app.quick_actions') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="{{ route('documents.create') }}" class="block p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-500 rounded-md flex items-center justify-center mr-3">
                                <span class="text-white text-lg">📤</span>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900 dark:text-gray-100">{{ __('app.upload_document') }}</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('app.submit_academic_documents') }}</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('profile.edit') }}" class="block p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-green-500 rounded-md flex items-center justify-center mr-3">
                                <span class="text-white text-lg">👤</span>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900 dark:text-gray-100">{{ __('app.update_profile') }}</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('app.manage_personal_information') }}</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>