<x-layouts.app :title="__('Create System Setting')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="w-full flex items-center gap-4 max-w-[600px] mx-auto">
            <flux:heading size="lg">{{ __('Create System Setting') }}</flux:heading>
        </div>

        <div class="flex flex-1 flex-col gap-4">
            <form method="POST" action="{{ route('system-settings.store') }}" class="space-y-6 w-full max-w-[600px] mx-auto">
                @csrf

                <flux:input :label="__('Key')" type="text" name="key" :value="old('key')" required autofocus autocomplete="key" />
                @error('key') <flux:error name="key" /> @enderror

                <flux:input :label="__('Value')" type="text" name="value" :value="old('value')" autocomplete="value" />
                @error('value') <flux:error name="value" /> @enderror

                <flux:select :label="__('Type')" name="type" required>
                    <option value="">Select Type</option>
                    <option value="string" {{ old('type') == 'string' ? 'selected' : '' }}>String</option>
                    <option value="integer" {{ old('type') == 'integer' ? 'selected' : '' }}>Integer</option>
                    <option value="boolean" {{ old('type') == 'boolean' ? 'selected' : '' }}>Boolean</option>
                    <option value="json" {{ old('type') == 'json' ? 'selected' : '' }}>JSON</option>
                </flux:select>
                @error('type') <flux:error name="type" /> @enderror

                <flux:textarea :label="__('Description')" name="description" rows="3">{{ old('description') }}</flux:textarea>
                @error('description') <flux:error name="description" /> @enderror

                <div class="flex items-center gap-4">
                    <flux:spacer />

                    <a href="{{ route('system-settings.index') }}" wire:navigate>
                        <flux:button variant="ghost">{{ __('Cancel') }}</flux:button>
                    </a>

                    <flux:button type="submit" variant="primary">
                        {{ __('Create System Setting') }}
                    </flux:button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
