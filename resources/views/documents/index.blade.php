<x-layouts.app :title="__('Documents')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="flex items-center gap-4">
            <flux:heading size="lg">{{ __('Documents') }}</flux:heading>
        </div>

        <div class="flex flex-1 flex-col gap-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <form method="GET" class="flex items-center gap-2">
                        <flux:select name="type" placeholder="Filter by type">
                            <option value="">All Types</option>
                            <option value="CV" {{ request('type') == 'CV' ? 'selected' : '' }}>CV</option>
                            <option value="Thesis" {{ request('type') == 'Thesis' ? 'selected' : '' }}>Thesis</option>
                            <option value="Report" {{ request('type') == 'Report' ? 'selected' : '' }}>Report</option>
                            <option value="Certificate" {{ request('type') == 'Certificate' ? 'selected' : '' }}>Certificate</option>
                        </flux:select>
                        <flux:button type="submit" variant="outline" size="sm">Filter</flux:button>
                    </form>
                </div>

                @can('create', App\Models\Document::class)
                    <a href="{{ route('documents.create') }}" wire:navigate>
                        <flux:button variant="primary">Upload Document</flux:button>
                    </a>
                @endcan
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Owner</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">File Size</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Uploaded</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($documents as $document)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ $document->title }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    @if($document->documentable_type === 'App\\Models\\Professor')
                                        Professor: {{ $document->documentable->user->name }}
                                    @else
                                        Student: {{ $document->documentable->user->name }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $document->type ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ number_format($document->file_size / 1024, 1) }} KB
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $document->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    @can('view', $document)
                                        <a href="{{ route('documents.show', $document) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-2">View</a>
                                        <a href="{{ route('documents.download', $document) }}" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 mr-2">Download</a>
                                    @endcan
                                    @can('update', $document)
                                        <a href="{{ route('documents.edit', $document) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-2">Edit</a>
                                    @endcan
                                    @can('delete', $document)
                                        <form method="POST" action="{{ route('documents.destroy', $document) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    No documents found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $documents->links() }}
        </div>
    </div>
</x-layouts.app>