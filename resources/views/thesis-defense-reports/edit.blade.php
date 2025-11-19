<x-layouts.app :title="__('Edit Thesis Defense Report')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="w-full flex items-center gap-4 max-w-[600px] mx-auto">
            <flux:heading size="lg">{{ __('Edit Thesis Defense Report') }}</flux:heading>
        </div>

        <div class="flex flex-1 flex-col gap-4">
            <form method="POST" action="{{ route('thesis-defense-reports.update', $thesisDefenseReport) }}" class="space-y-6 w-full max-w-[600px] mx-auto">
                @csrf
                @method('PUT')

                <flux:select :label="__('Student')" name="student_id" required>
                    <option value="">Select Student</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" {{ old('student_id', $thesisDefenseReport->student_id) == $student->id ? 'selected' : '' }}>
                            {{ $student->user->name }} ({{ $student->student_number }})
                        </option>
                    @endforeach
                </flux:select>
                @error('student_id') <flux:error name="student_id" /> @enderror

                <flux:select :label="__('Supervisor')" name="supervisor_id" required>
                    <option value="">Select Supervisor</option>
                    @foreach($professors as $professor)
                        <option value="{{ $professor->id }}" {{ old('supervisor_id', $thesisDefenseReport->supervisor_id) == $professor->id ? 'selected' : '' }}>
                            {{ $professor->user->name }} ({{ $professor->title ?? 'Professor' }})
                        </option>
                    @endforeach
                </flux:select>
                @error('supervisor_id') <flux:error name="supervisor_id" /> @enderror

                <flux:input :label="__('Thesis Title')" type="text" name="title" :value="old('title', $thesisDefenseReport->title)" required />
                @error('title') <flux:error name="title" /> @enderror

                <flux:textarea :label="__('Abstract')" name="abstract" rows="4" placeholder="Brief description of the thesis">{{ old('abstract', $thesisDefenseReport->abstract) }}</flux:textarea>
                @error('abstract') <flux:error name="abstract" /> @enderror

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <flux:input :label="__('Defense Date')" type="date" name="defense_date" :value="old('defense_date', $thesisDefenseReport->defense_date?->format('Y-m-d'))" required />
                    @error('defense_date') <flux:error name="defense_date" /> @enderror

                    <flux:input :label="__('Defense Time')" type="time" name="defense_time" :value="old('defense_time', $thesisDefenseReport->defense_time?->format('H:i'))" required />
                    @error('defense_time') <flux:error name="defense_time" /> @enderror

                    <flux:input :label="__('Room')" type="text" name="room" :value="old('room', $thesisDefenseReport->room)" placeholder="e.g., Room 101" />
                    @error('room') <flux:error name="room" /> @enderror
                </div>

                <flux:input :label="__('Final Grade (0-20)')" type="number" name="final_grade" :value="old('final_grade', $thesisDefenseReport->final_grade)" min="0" max="20" step="0.01" />
                @error('final_grade') <flux:error name="final_grade" /> @enderror

                <flux:select :label="__('Status')" name="status" required>
                    <option value="">Select Status</option>
                    <option value="scheduled" {{ old('status', $thesisDefenseReport->status) == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                    <option value="completed" {{ old('status', $thesisDefenseReport->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ old('status', $thesisDefenseReport->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </flux:select>
                @error('status') <flux:error name="status" /> @enderror

                <flux:textarea :label="__('Comments')" name="comments" rows="3">{{ old('comments', $thesisDefenseReport->comments) }}</flux:textarea>
                @error('comments') <flux:error name="comments" /> @enderror

                <div class="flex items-center gap-4">
                    <flux:spacer />

                    <a href="{{ route('thesis-defense-reports.index') }}" wire:navigate>
                        <flux:button variant="ghost">{{ __('Cancel') }}</flux:button>
                    </a>

                    <flux:button type="submit" variant="primary">
                        {{ __('Update Thesis Defense Report') }}
                    </flux:button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
