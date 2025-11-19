<x-layouts.app :title="__('Thesis Defense Details')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="flex items-center gap-4">
            <flux:heading size="lg">{{ $thesisDefenseReport->title }}</flux:heading>
        </div>

        <div class="flex flex-1 flex-col gap-4">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Information -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium mb-4">Thesis Information</h3>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Title</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $thesisDefenseReport->title }}</dd>
                                </div>
                                @if($thesisDefenseReport->abstract)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Abstract</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $thesisDefenseReport->abstract }}</dd>
                                    </div>
                                @endif
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                                    <dd class="mt-1">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($thesisDefenseReport->status === 'scheduled') bg-yellow-100 text-yellow-800
                                            @elseif($thesisDefenseReport->status === 'completed') bg-green-100 text-green-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ ucfirst($thesisDefenseReport->status) }}
                                        </span>
                                    </dd>
                                </div>
                                @if($thesisDefenseReport->final_grade)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Final Grade</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-semibold">{{ number_format($thesisDefenseReport->final_grade, 2) }}/20</dd>
                                    </div>
                                @endif
                                @if($thesisDefenseReport->comments)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Comments</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $thesisDefenseReport->comments }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    <!-- Jury Members -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-medium">Jury Members</h3>
                                @can('update', $thesisDefenseReport)
                                    <a href="{{ route('jury-members.create', ['thesis_defense_report_id' => $thesisDefenseReport->id]) }}" wire:navigate>
                                        <flux:button variant="outline" size="sm">Add Jury Member</flux:button>
                                    </a>
                                @endcan
                            </div>

                            @if($thesisDefenseReport->juryMembers->count() > 0)
                                <div class="space-y-4">
                                    @foreach($thesisDefenseReport->juryMembers as $juryMember)
                                        <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-3">
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                            {{ $juryMember->professor->user->name }}
                                                        </p>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                                            {{ $juryMember->role }}
                                                        </p>
                                                    </div>
                                                </div>
                                                @if($juryMember->grade)
                                                    <div class="mt-2">
                                                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                            Grade: {{ number_format($juryMember->grade, 2) }}/20
                                                        </span>
                                                    </div>
                                                @endif
                                                @if($juryMember->comments)
                                                    <div class="mt-1">
                                                        <p class="text-xs text-gray-600 dark:text-gray-400">{{ $juryMember->comments }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                            @can('update', $thesisDefenseReport)
                                                <div class="flex items-center gap-2">
                                                    <a href="{{ route('jury-members.edit', $juryMember) }}" wire:navigate>
                                                        <flux:button variant="ghost" size="sm">Edit</flux:button>
                                                    </a>
                                                    <form method="POST" action="{{ route('jury-members.destroy', $juryMember) }}" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <flux:button type="submit" variant="ghost" size="sm" class="text-red-600 hover:text-red-800" onclick="return confirm('Remove this jury member?')">Remove</flux:button>
                                                    </form>
                                                </div>
                                            @endcan
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 dark:text-gray-400 text-center py-4">No jury members assigned yet.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Defense Details -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium mb-4">Defense Details</h3>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Student</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $thesisDefenseReport->student->user->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Supervisor</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $thesisDefenseReport->supervisor->user->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Date & Time</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $thesisDefenseReport->defense_date->format('l, F d, Y') }}<br>
                                        {{ $thesisDefenseReport->defense_time }}
                                    </dd>
                                </div>
                                @if($thesisDefenseReport->room)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Room</dt>
                                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $thesisDefenseReport->room }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    <!-- Actions -->
                    @can('update', $thesisDefenseReport)
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium mb-4">Actions</h3>
                                <div class="space-y-2">
                                    <a href="{{ route('thesis-defense-reports.edit', $thesisDefenseReport) }}" wire:navigate>
                                        <flux:button variant="outline" class="w-full">Edit Defense</flux:button>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endcan
                </div>
            </div>

            <div class="flex items-center gap-4">
                <a href="{{ route('thesis-defense-reports.index') }}" wire:navigate>
                    <flux:button variant="ghost">Back to Defenses</flux:button>
                </a>
            </div>
        </div>
    </div>
</x-layouts.app>