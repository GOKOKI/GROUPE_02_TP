<x-layouts.app :title="__('Schedule Thesis Defense')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="w-full flex items-center gap-4 max-w-[600px] mx-auto">
            <flux:heading size="lg">{{ __('Schedule Thesis Defense') }}</flux:heading>
        </div>

        <div class="flex flex-1 flex-col gap-4">
            <form method="POST" action="{{ route('thesis-defense-reports.store') }}" class="space-y-6 w-full max-w-[600px] mx-auto">
                @csrf

                <flux:select :label="__('Student')" name="student_id" required>
                @error('student_id') <flux:error name="student_id" /> @enderror
                    <option value="">Select Student</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                            {{ $student->user->name }} ({{ $student->student_number }})
                        </option>
                    @endforeach
                </flux:select>

                <flux:select :label="__('Supervisor')" name="supervisor_id" required>
                @error('supervisor_id') <flux:error name="supervisor_id" /> @enderror
                    <option value="">Select Supervisor</option>
                    @foreach($professors as $professor)
                        <option value="{{ $professor->id }}" {{ old('supervisor_id') == $professor->id ? 'selected' : '' }}>
                            {{ $professor->user->name }} ({{ $professor->title ?? 'Professor' }})
                        </option>
                    @endforeach
                </flux:select>

                <flux:input :label="__('Thesis Title')" type="text" name="title" :value="old('title')" required />
                @error('title') <flux:error name="title" /> @enderror

                <flux:textarea :label="__('Abstract')" name="abstract" rows="4" placeholder="Brief description of the thesis">{{ old('abstract') }}</flux:textarea>
                @error('abstract') <flux:error name="abstract" /> @enderror

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <flux:input :label="__('Defense Date')" type="date" name="defense_date" :value="old('defense_date')" required />
                    @error('defense_date') <flux:error name="defense_date" /> @enderror

                    <flux:input :label="__('Defense Time')" type="time" name="defense_time" :value="old('defense_time', '09:00')" required />
                    @error('defense_time') <flux:error name="defense_time" /> @enderror

                    <flux:input :label="__('Room')" type="text" name="room" :value="old('room')" placeholder="e.g., Room 101" />
                    @error('room') <flux:error name="room" /> @enderror
                </div>

                <div class="flex items-center gap-4">
                    <flux:spacer />

                    <a href="{{ route('thesis-defense-reports.index') }}" wire:navigate>
                        <flux:button variant="ghost">{{ __('Cancel') }}</flux:button>
                    </a>

                    <flux:button type="submit" variant="primary">
                        {{ __('Schedule Defense') }}
                    </flux:button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
