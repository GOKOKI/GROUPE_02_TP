<x-layouts.app :title="__('Edit System Setting')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="w-full flex items-center gap-4 max-w-[600px] mx-auto">
            <flux:heading size="lg">{{ __('Edit System Setting') }}</flux:heading>
        </div>

        <div class="flex flex-1 flex-col gap-4">
            <form method="POST" action="{{ route('system-settings.update', $systemSetting) }}" class="space-y-6 w-full max-w-[600px] mx-auto">
                @csrf
                @method('PUT')

                <div>
                    <flux:label>{{ __('Key') }}</flux:label>
                    <p class="mt-1 text-sm text-gray-900 dark:text-gray-100 font-mono bg-gray-100 dark:bg-gray-800 px-3 py-2 rounded">{{ $systemSetting->key }}</p>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">The key cannot be changed.</p>
                </div>

                <flux:input :label="__('Value')" type="text" name="value" :value="old('value', $systemSetting->value)" />
                @error('value') <flux:error name="value" /> @enderror

                <flux:select :label="__('Type')" name="type" required>
                    <option value="">Select Type</option>
                    <option value="string" {{ old('type', $systemSetting->type) == 'string' ? 'selected' : '' }}>String</option>
                    <option value="integer" {{ old('type', $systemSetting->type) == 'integer' ? 'selected' : '' }}>Integer</option>
                    <option value="boolean" {{ old('type', $systemSetting->type) == 'boolean' ? 'selected' : '' }}>Boolean</option>
                    <option value="json" {{ old('type', $systemSetting->type) == 'json' ? 'selected' : '' }}>JSON</option>
                </flux:select>
                @error('type') <flux:error name="type" /> @enderror

                <flux:textarea :label="__('Description')" name="description" rows="3">{{ old('description', $systemSetting->description) }}</flux:textarea>
                @error('description') <flux:error name="description" /> @enderror

                <div class="flex items-center gap-4">
                    <flux:spacer />

                    <a href="{{ route('system-settings.index') }}" wire:navigate>
                        <flux:button variant="ghost">{{ __('Cancel') }}</flux:button>
                    </a>

                    <flux:button type="submit" variant="primary">
                        {{ __('Update System Setting') }}
                    </flux:button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
