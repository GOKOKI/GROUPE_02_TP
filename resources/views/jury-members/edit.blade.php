<x-layouts.app :title="__('Edit Jury Member')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="w-full flex items-center gap-4 max-w-[600px] mx-auto">
            <flux:heading size="lg">{{ __('Edit Jury Member') }}</flux:heading>
        </div>

        <div class="flex flex-1 flex-col gap-4">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium mb-4">Jury Member Details</h3>
                    <div class="space-y-2">
                        <p><strong>Professor:</strong> {{ $juryMember->professor->user->name }}</p>
                        <p><strong>Thesis:</strong> {{ $juryMember->thesisDefenseReport->title }}</p>
                        <p><strong>Student:</strong> {{ $juryMember->thesisDefenseReport->student->user->name }}</p>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('jury-members.update', $juryMember) }}" class="space-y-6 w-full max-w-[600px] mx-auto">
                @csrf
                @method('PUT')

                <flux:input :label="__('Role')" type="text" name="role" :value="old('role', $juryMember->role)" required />
                @error('role') <flux:error name="role" /> @enderror

                <flux:input :label="__('Grade (0-20)')" type="number" name="grade" :value="old('grade', $juryMember->grade)" min="0" max="20" step="0.01" />
                @error('grade') <flux:error name="grade" /> @enderror

                <flux:textarea :label="__('Comments')" name="comments" rows="3">{{ old('comments', $juryMember->comments) }}</flux:textarea>
                @error('comments') <flux:error name="comments" /> @enderror

                <div class="flex items-center gap-4">
                    <flux:spacer />

                    <a href="{{ route('thesis-defense-reports.show', $juryMember->thesis_defense_report_id) }}" wire:navigate>
                        <flux:button variant="ghost">{{ __('Cancel') }}</flux:button>
                    </a>

                    <flux:button type="submit" variant="primary">
                        {{ __('Update Jury Member') }}
                    </flux:button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
