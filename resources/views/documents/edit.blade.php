<x-layouts.app :title="__('Edit Document')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="w-full flex items-center gap-4 max-w-[600px] mx-auto">
            <flux:heading size="lg">{{ __('Edit Document') }}</flux:heading>
        </div>

        <div class="flex flex-1 flex-col gap-4">
            <form method="POST" action="{{ route('documents.update', $document) }}" enctype="multipart/form-data" class="space-y-6 w-full max-w-[600px] mx-auto">
                @csrf
                @method('PUT')

                <flux:input :label="__('Title')" type="text" name="title" :value="old('title', $document->title)" required />
                @error('title') <flux:error name="title" /> @enderror

                <div>
                    <flux:label for="file">{{ __('File (leave empty to keep current file)') }}</flux:label>
                    <input id="file" type="file" name="file" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png" />
                    @if($document->file_name)
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Current file: {{ $document->file_name }}</p>
                    @endif
                    @error('file') <flux:error name="file" /> @enderror
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Accepted formats: PDF, DOC, DOCX, TXT, JPG, JPEG, PNG. Max size: 10MB</p>
                </div>

                <flux:select :label="__('Owner Type')" name="documentable_type" required>
                    <option value="">Select Owner Type</option>
                    <option value="App\Models\Professor" {{ old('documentable_type', $document->documentable_type) == 'App\Models\Professor' ? 'selected' : '' }}>Professor</option>
                    <option value="App\Models\Student" {{ old('documentable_type', $document->documentable_type) == 'App\Models\Student' ? 'selected' : '' }}>Student</option>
                </flux:select>
                @error('documentable_type') <flux:error name="documentable_type" /> @enderror

                <flux:select :label="__('Owner')" name="documentable_id" required>
                    <option value="">Select Owner</option>
                    @if(old('documentable_type', $document->documentable_type) === 'App\Models\Professor' || $document->documentable_type === 'App\Models\Professor')
                        @foreach($professors as $professor)
                            <option value="{{ $professor->id }}" {{ old('documentable_id', $document->documentable_id) == $professor->id ? 'selected' : '' }}>
                                Professor {{ $professor->user->name }}
                            </option>
                        @endforeach
                    @elseif(old('documentable_type', $document->documentable_type) === 'App\Models\Student' || $document->documentable_type === 'App\Models\Student')
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ old('documentable_id', $document->documentable_id) == $student->id ? 'selected' : '' }}>
                                Student {{ $student->user->name }}
                            </option>
                        @endforeach
                    @endif
                </flux:select>
                @error('documentable_id') <flux:error name="documentable_id" /> @enderror

                <flux:input :label="__('Document Type')" type="text" name="type" :value="old('type', $document->type)" placeholder="e.g., CV, Thesis, Report" />
                @error('type') <flux:error name="type" /> @enderror

                <flux:textarea :label="__('Description')" name="description" rows="3">{{ old('description', $document->description) }}</flux:textarea>
                @error('description') <flux:error name="description" /> @enderror

                <div class="flex items-center gap-4">
                    <flux:spacer />

                    <a href="{{ route('documents.index') }}" wire:navigate>
                        <flux:button variant="ghost">{{ __('Cancel') }}</flux:button>
                    </a>

                    <flux:button type="submit" variant="primary">
                        {{ __('Update Document') }}
                    </flux:button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
