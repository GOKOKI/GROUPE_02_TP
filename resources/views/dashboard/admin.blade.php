<x-layouts.app :title="__('Admin Dashboard')">
    <div class="flex flex-col gap-8">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <flux:heading size="xl">{{ __('app.admin_dashboard') }}</flux:heading>
            <span class="text-sm text-zinc-500 dark:text-zinc-400">{{ now()->format('l, d F Y') }}</span>
        </div>

        <!-- Statistics Overview -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Users -->
            <div class="p-6 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                        <flux:icon name="users" class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">{{ __('app.total_users') }}</p>
                        <p class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">{{ number_format($stats['total_users']) }}</p>
                    </div>
                </div>
            </div>

            <!-- Students -->
            <div class="p-6 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                        <flux:icon name="academic-cap" class="w-6 h-6 text-green-600 dark:text-green-400" />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">{{ __('app.students') }}</p>
                        <p class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">{{ number_format($stats['total_students']) }}</p>
                    </div>
                </div>
            </div>

            <!-- Professors -->
            <div class="p-6 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                        <flux:icon name="user-group" class="w-6 h-6 text-purple-600 dark:text-purple-400" />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">{{ __('app.professors') }}</p>
                        <p class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">{{ number_format($stats['total_professors']) }}</p>
                    </div>
                </div>
            </div>

            <!-- Departments -->
            <div class="p-6 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                        <flux:icon name="building-office" class="w-6 h-6 text-yellow-600 dark:text-yellow-400" />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">{{ __('app.departments') }}</p>
                        <p class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">{{ number_format($stats['total_departments']) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Defense Status Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-6 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-sm relative overflow-hidden">
                <div class="relative z-10">
                    <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">{{ __('app.total_defenses') }}</p>
                    <p class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100 mt-1">{{ number_format($stats['total_thesis_defenses']) }}</p>
                </div>
                <flux:icon name="clipboard-document-list" class="absolute right-4 bottom-4 w-12 h-12 text-zinc-100 dark:text-zinc-800" />
            </div>

            <div class="p-6 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-sm relative overflow-hidden">
                <div class="relative z-10">
                    <p class="text-sm font-medium text-orange-600 dark:text-orange-400">{{ __('app.scheduled') }}</p>
                    <p class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100 mt-1">{{ number_format($stats['scheduled_defenses']) }}</p>
                </div>
                <flux:icon name="calendar" class="absolute right-4 bottom-4 w-12 h-12 text-orange-50 dark:text-orange-900/20" />
            </div>

            <div class="p-6 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-sm relative overflow-hidden">
                <div class="relative z-10">
                    <p class="text-sm font-medium text-green-600 dark:text-green-400">{{ __('app.completed') }}</p>
                    <p class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100 mt-1">{{ number_format($stats['completed_defenses']) }}</p>
                </div>
                <flux:icon name="check-circle" class="absolute right-4 bottom-4 w-12 h-12 text-green-50 dark:text-green-900/20" />
            </div>
        </div>

        <!-- Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Defenses -->
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-sm flex flex-col">
                <div class="p-6 border-b border-zinc-100 dark:border-zinc-800 flex items-center justify-between">
                    <flux:heading size="lg">{{ __('app.recent_thesis_defenses') }}</flux:heading>
                    <flux:button size="sm" variant="ghost" :href="route('thesis-defense-reports.index')">{{ __('app.view_all') }}</flux:button>
                </div>
                <div class="p-6 flex-1">
                    @if($recentDefenses->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentDefenses as $defense)
                                <div class="flex items-start justify-between gap-4">
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100 truncate">{{ $defense->title }}</p>
                                        <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">
                                            {{ $defense->student->user->name }} • Sup: {{ $defense->supervisor->user->name }}
                                        </p>
                                    </div>
                                    <flux:badge size="sm" :color="$defense->status === 'scheduled' ? 'yellow' : ($defense->status === 'completed' ? 'green' : 'red')">
                                        {{ $defense->status === 'scheduled' ? __('app.scheduled') : ($defense->status === 'completed' ? __('app.completed') : __('app.pending')) }}
                                    </flux:badge>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6 text-zinc-500">
                            {{ __('app.no_recent_thesis_defenses') }}
                        </div>
                    @endif
                </div>
                <div class="p-4 border-t border-zinc-100 dark:border-zinc-800">
                    {{ $recentDefenses->links() }}
                </div>
            </div>

            <!-- Recent Documents -->
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-sm flex flex-col">
                <div class="p-6 border-b border-zinc-100 dark:border-zinc-800 flex items-center justify-between">
                    <flux:heading size="lg">{{ __('app.recent_documents') }}</flux:heading>
                    <flux:button size="sm" variant="ghost" :href="route('documents.index')">{{ __('app.view_all') }}</flux:button>
                </div>
                <div class="p-6 flex-1">
                    @if($recentDocuments->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentDocuments as $document)
                                <div class="flex items-center justify-between gap-4">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="p-2 bg-zinc-100 dark:bg-zinc-800 rounded-md shrink-0">
                                            <flux:icon name="document" class="w-4 h-4 text-zinc-500" />
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-medium text-zinc-900 dark:text-zinc-100 truncate">{{ $document->title }}</p>
                                            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">
                                                {{ $document->documentable->user->name ?? 'Unknown' }} • {{ $document->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                    <flux:badge size="sm" color="zinc">{{ $document->type ?? 'File' }}</flux:badge>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6 text-zinc-500">
                            {{ __('app.no_recent_documents') }}
                        </div>
                    @endif
                </div>
                <div class="p-4 border-t border-zinc-100 dark:border-zinc-800">
                    {{ $recentDocuments->links() }}
                </div>
            </div>
        </div>

        <!-- Department Distribution -->
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-sm">
            <div class="p-6 border-b border-zinc-100 dark:border-zinc-800">
                <flux:heading size="lg">{{ __('app.department_distribution') }}</flux:heading>
            </div>
            <div class="p-6">
                @if($departmentStats->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-800">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">{{ __('app.department') }}</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">{{ __('app.students') }}</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">{{ __('app.professors') }}</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">{{ __('Total') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                                @foreach($departmentStats as $dept)
                                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                                        <td class="px-4 py-3 text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $dept['name'] }}</td>
                                        <td class="px-4 py-3 text-sm text-zinc-500 dark:text-zinc-400">{{ $dept['students'] }}</td>
                                        <td class="px-4 py-3 text-sm text-zinc-500 dark:text-zinc-400">{{ $dept['professors'] }}</td>
                                        <td class="px-4 py-3 text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $dept['total'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-6 text-zinc-500">
                        {{ __('app.no_departments_found') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>
