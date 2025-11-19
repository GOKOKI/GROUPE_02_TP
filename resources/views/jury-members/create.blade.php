<x-layouts.app :title="__('Add Jury Member')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="w-full flex items-center gap-4 max-w-[600px] mx-auto">
            <flux:heading size="lg">{{ __('Add Jury Member') }}</flux:heading>
        </div>

        <div class="flex flex-1 flex-col gap-4">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium mb-4">Thesis Defense</h3>
                    <div class="space-y-2">
                        <p><strong>Title:</strong> {{ $thesisDefenseReport->title }}</p>
                        <p><strong>Student:</strong> {{ $thesisDefenseReport->student->user->name }}</p>
                        <p><strong>Supervisor:</strong> {{ $thesisDefenseReport->supervisor->user->name }}</p>
                        <p><strong>Date:</strong> {{ $thesisDefenseReport->defense_date->format('M d, Y') }} at {{ $thesisDefenseReport->defense_time }}</p>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('jury-members.store') }}" class="space-y-6 w-full max-w-[600px] mx-auto">
                @csrf

                <input type="hidden" name="thesis_defense_report_id" value="{{ $thesisDefenseReport->id }}">

                <flux:select :label="__('Professor')" name="professor_id" required>
                @error('professor_id') <flux:error name="professor_id" /> @enderror
                    <option value="">Select Professor</option>
                    @foreach($availableProfessors as $professor)
                        <option value="{{ $professor->id }}" {{ old('professor_id') == $professor->id ? 'selected' : '' }}>
                            {{ $professor->user->name }} ({{ $professor->title ?? 'Professor' }})
                        </option>
                    @endforeach
                </flux:select>

                <flux:input :label="__('Role')" type="text" name="role" :value="old('role', 'member')" required placeholder="e.g., president, secretary, member" />
                @error('role') <flux:error name="role" /> @enderror

                <div class="flex items-center gap-4">
                    <flux:spacer />

                    <a href="{{ route('thesis-defense-reports.show', $thesisDefenseReport) }}" wire:navigate>
                        <flux:button variant="ghost">{{ __('Cancel') }}</flux:button>
                    </a>

                    <flux:button type="submit" variant="primary">
                        {{ __('Add Jury Member') }}
                    </flux:button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
