<x-layouts.app :title="__('Create Department')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="w-full flex items-center gap-4 max-w-[600px] mx-auto">
            <flux:heading size="lg">{{ __('Create Department') }}</flux:heading>
        </div>

        <div class="flex flex-1 flex-col gap-4">
            <form method="POST" action="{{ route('departments.store') }}" class="space-y-6 w-full max-w-[600px] mx-auto">
                @csrf

                <flux:input :label="__('Name')" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                @error('name') <flux:error name="name" /> @enderror

                <flux:input :label="__('Code')" type="text" name="code" :value="old('code')" required autocomplete="code" />
                @error('code') <flux:error name="code" /> @enderror

                <flux:textarea :label="__('Description')" name="description" rows="4">{{ old('description') }}</flux:textarea>
                @error('description') <flux:error name="description" /> @enderror

                <div class="flex items-center gap-4">
                    <flux:spacer />

                    <a href="{{ route('departments.index') }}" wire:navigate>
                        <flux:button variant="ghost">{{ __('Cancel') }}</flux:button>
                    </a>

                    <flux:button type="submit" variant="primary">
                        {{ __('Create Department') }}
                    </flux:button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
