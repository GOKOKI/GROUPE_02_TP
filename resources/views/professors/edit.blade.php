<x-layouts.app :title="__('Edit Professor')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="w-full flex items-center gap-4 max-w-[600px] mx-auto">
            <flux:heading size="lg">{{ __('Edit Professor') }}</flux:heading>
        </div>

        <div class="flex flex-1 flex-col gap-4">
            <form method="POST" action="{{ route('professors.update', $professor) }}" class="space-y-6 w-full max-w-[600px] mx-auto">
                @csrf
                @method('PUT')

                <flux:input :label="__('Name')" type="text" name="name" :value="old('name', $professor->user->name)" required autofocus autocomplete="name" />
                @error('name') <flux:error name="name" /> @enderror

                <flux:input :label="__('Email')" type="email" name="email" :value="old('email', $professor->user->email)" required autocomplete="email" />
                @error('email') <flux:error name="email" /> @enderror

                <flux:select :label="__('Department')" name="department_id" required>
                    <option value="">Select Department</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}" {{ old('department_id', $professor->department_id) == $department->id ? 'selected' : '' }}>
                            {{ $department->name }}
                        </option>
                    @endforeach
                </flux:select>
                @error('department_id') <flux:error name="department_id" /> @enderror

                <flux:input :label="__('Title')" type="text" name="title" :value="old('title', $professor->title)" autocomplete="title" />
                @error('title') <flux:error name="title" /> @enderror

                <flux:input :label="__('Specialty')" type="text" name="specialty" :value="old('specialty', $professor->specialty)" autocomplete="specialty" />
                @error('specialty') <flux:error name="specialty" /> @enderror

                <flux:input :label="__('Phone')" type="tel" name="phone" :value="old('phone', $professor->phone)" autocomplete="tel" />
                @error('phone') <flux:error name="phone" /> @enderror

                <flux:textarea :label="__('Bio')" name="bio" rows="4">{{ old('bio', $professor->bio) }}</flux:textarea>
                @error('bio') <flux:error name="bio" /> @enderror

                <div class="flex items-center gap-4">
                    <flux:spacer />

                    <a href="{{ route('professors.index') }}" wire:navigate>
                        <flux:button variant="ghost">{{ __('Cancel') }}</flux:button>
                    </a>

                    <flux:button type="submit" variant="primary">
                        {{ __('Update Professor') }}
                    </flux:button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
