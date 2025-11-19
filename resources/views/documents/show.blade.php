<x-layouts.app :title="__('Document Details')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="flex items-center gap-4">
            <flux:heading size="lg">{{ $document->title }}</flux:heading>
        </div>

        <div class="flex flex-1 flex-col gap-4">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium mb-4">Document Information</h3>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Title</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $document->title }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Type</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $document->type ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">File Name</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $document->file_name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">File Size</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ number_format($document->file_size / 1024, 1) }} KB</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">MIME Type</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $document->mime_type }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium mb-4">Owner & Upload Information</h3>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Owner</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        @if($document->documentable_type === 'App\\Models\\Professor')
                                            Professor: {{ $document->documentable->user->name }}
                                        @else
                                            Student: {{ $document->documentable->user->name }}
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Uploaded By</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $document->user->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Upload Date</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $document->created_at->format('M d, Y H:i') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Updated</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $document->updated_at->format('M d, Y H:i') }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    @if($document->description)
                        <div class="mt-6">
                            <h3 class="text-lg font-medium mb-2">Description</h3>
                            <p class="text-gray-700 dark:text-gray-300">{{ $document->description }}</p>
                        </div>
                    @endif

                    <div class="mt-6 flex items-center gap-4">
                        <a href="{{ route('documents.download', $document) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Download File
                        </a>

                        @can('update', $document)
                            <a href="{{ route('documents.edit', $document) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Edit Document
                            </a>
                        @endcan

                        @can('delete', $document)
                            <form method="POST" action="{{ route('documents.destroy', $document) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150" onclick="return confirm('Are you sure you want to delete this document?')">
                                    Delete Document
                                </button>
                            </form>
                        @endcan

                        <a href="{{ route('documents.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Back to Documents
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>