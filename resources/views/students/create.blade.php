<x-layouts.app :title="__('Create Student')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="w-full flex items-center gap-4 max-w-[600px] mx-auto">
            <flux:heading size="lg">{{ __('Create Student') }}</flux:heading>
        </div>

        <div class="flex flex-1 flex-col gap-4">
            <form method="POST" action="{{ route('students.store') }}" class="space-y-6 w-full max-w-[600px] mx-auto">
                @csrf

                <flux:input :label="__('Name')" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                @error('name') <flux:error name="name" /> @enderror

                <flux:input :label="__('Email')" type="email" name="email" :value="old('email')" required autocomplete="email" />
                @error('email') <flux:error name="email" /> @enderror

                <flux:input :label="__('Password')" type="password" name="password" required autocomplete="new-password" />
                @error('password') <flux:error name="password" /> @enderror

                <flux:select :label="__('Department')" name="department_id" required>
                    <option value="">Select Department</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                            {{ $department->name }}
                        </option>
                    @endforeach
                </flux:select>

                <flux:input :label="__('Student Number')" type="text" name="student_number" :value="old('student_number')" required autocomplete="student_number" />
                @error('student_number') <flux:error name="student_number" /> @enderror

                <flux:input :label="__('Phone')" type="tel" name="phone" :value="old('phone')" autocomplete="tel" />
                @error('phone') <flux:error name="phone" /> @enderror

                <flux:input :label="__('Enrollment Date')" type="date" name="enrollment_date" :value="old('enrollment_date')" required />
                @error('enrollment_date') <flux:error name="enrollment_date" /> @enderror

                <flux:select :label="__('Level')" name="level">
                    <option value="">Select Level</option>
                    <option value="Bachelor" {{ old('level') == 'Bachelor' ? 'selected' : '' }}>Bachelor</option>
                    <option value="Master" {{ old('level') == 'Master' ? 'selected' : '' }}>Master</option>
                    <option value="PhD" {{ old('level') == 'PhD' ? 'selected' : '' }}>PhD</option>
                </flux:select>

                <div class="flex items-center gap-4">
                    <flux:spacer />

                    <a href="{{ route('students.index') }}" wire:navigate>
                        <flux:button variant="ghost">{{ __('Cancel') }}</flux:button>
                    </a>

                    <flux:button type="submit" variant="primary">
                        {{ __('Create Student') }}
                    </flux:button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
