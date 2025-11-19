<x-layouts.app :title="__('Thesis Defense Reports')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="flex items-center gap-4">
            <flux:heading size="lg">{{ __('Thesis Defense Reports') }}</flux:heading>
        </div>

        <div class="flex flex-1 flex-col gap-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <form method="GET" class="flex items-center gap-2">
                        <flux:select name="status" placeholder="Filter by status">
                            <option value="">All Status</option>
                            <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </flux:select>

                        <flux:select name="student_id" placeholder="Filter by student">
                            <option value="">All Students</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->user->name }}
                                </option>
                            @endforeach
                        </flux:select>

                        <flux:select name="supervisor_id" placeholder="Filter by supervisor">
                            <option value="">All Supervisors</option>
                            @foreach($professors as $professor)
                                <option value="{{ $professor->id }}" {{ request('supervisor_id') == $professor->id ? 'selected' : '' }}>
                                    {{ $professor->user->name }}
                                </option>
                            @endforeach
                        </flux:select>

                        <flux:button type="submit" variant="outline" size="sm">Filter</flux:button>
                    </form>
                </div>

                @can('create', App\Models\ThesisDefenseReport::class)
                    <a href="{{ route('thesis-defense-reports.create') }}" wire:navigate>
                        <flux:button variant="primary">Schedule Defense</flux:button>
                    </a>
                @endcan
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Student</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Supervisor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date & Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Grade</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($thesisDefenseReports as $report)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ $report->student->user->name }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300 max-w-xs truncate">
                                    {{ $report->title }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $report->supervisor->user->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $report->defense_date->format('M d, Y') }}<br>
                                    <span class="text-xs">{{ $report->defense_time }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($report->status === 'scheduled') bg-yellow-100 text-yellow-800
                                        @elseif($report->status === 'completed') bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ ucfirst($report->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $report->final_grade ? number_format($report->final_grade, 2) . '/20' : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    @can('view', $report)
                                        <a href="{{ route('thesis-defense-reports.show', $report) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-2">View</a>
                                    @endcan
                                    @can('update', $report)
                                        <a href="{{ route('thesis-defense-reports.edit', $report) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-2">Edit</a>
                                    @endcan
                                    @can('delete', $report)
                                        <form method="POST" action="{{ route('thesis-defense-reports.destroy', $report) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    No thesis defense reports found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $thesisDefenseReports->links() }}
        </div>
    </div>
</x-layouts.app>