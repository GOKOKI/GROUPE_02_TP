<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Professor;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Document::with(['user', 'documentable']);

        // Filter by type if specified
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        // Filter by owner if specified
        if ($request->has('owner_type') && $request->has('owner_id')) {
            $query->where('documentable_type', $request->owner_type)
                ->where('documentable_id', $request->owner_id);
        }

        $documents = $query->latest()->paginate(15);

        return view('documents.index', compact('documents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', Document::class);

        $professors = Professor::with('user')->get();
        $students = Student::with('user')->get();

        // Pre-select owner if specified in query params
        $selectedOwnerType = $request->get('owner_type');
        $selectedOwnerId = $request->get('owner_id');

        return view('documents.create', compact('professors', 'students', 'selectedOwnerType', 'selectedOwnerId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Document::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'required|file|max:10240|mimes:pdf,doc,docx,txt,jpg,jpeg,png',
            'type' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'documentable_type' => 'required|string|in:App\\Models\\Professor,App\\Models\\Student',
            'documentable_id' => 'required|integer',
        ]);

        // Validate that the documentable exists
        $documentableType = $validated['documentable_type'];
        $documentableId = $validated['documentable_id'];

        if ($documentableType === 'App\\Models\\Professor') {
            $documentable = Professor::findOrFail($documentableId);
        } else {
            $documentable = Student::findOrFail($documentableId);
        }

        // Store the file
        $file = $request->file('file');
        $fileName = time().'_'.$file->getClientOriginalName();
        $filePath = $file->storeAs('documents', $fileName, 'public');

        Document::create([
            'user_id' => auth()->id(),
            'documentable_type' => $documentableType,
            'documentable_id' => $documentableId,
            'title' => $validated['title'],
            'file_path' => $filePath,
            'file_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'type' => $request->input('type'),
            'description' => $request->input('description'),
        ]);

        return redirect()->route('documents.index')
            ->with('success', __('app.document_uploaded_successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Document $document): View
    {
        $this->authorize('view', $document);

        return view('documents.show', compact('document'));
    }

    /**
     * Download the document file.
     */
    public function download(Document $document)
    {
        $this->authorize('view', $document);

        return Storage::disk('public')->download($document->file_path, $document->file_name);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Document $document): View
    {
        $this->authorize('update', $document);

        $professors = Professor::with('user')->get();
        $students = Student::with('user')->get();

        return view('documents.edit', compact('document', 'professors', 'students'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Document $document): RedirectResponse
    {
        $this->authorize('update', $document);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'nullable|file|max:10240|mimes:pdf,doc,docx,txt,jpg,jpeg,png',
            'type' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'documentable_type' => 'required|string|in:App\\Models\\Professor,App\\Models\\Student',
            'documentable_id' => 'required|integer',
        ]);

        // Validate that the documentable exists
        $documentableType = $validated['documentable_type'];
        $documentableId = $validated['documentable_id'];

        if ($documentableType === 'App\\Models\\Professor') {
            $documentable = Professor::findOrFail($documentableId);
        } else {
            $documentable = Student::findOrFail($documentableId);
        }

        $updateData = [
            'title' => $validated['title'],
            'documentable_type' => $documentableType,
            'documentable_id' => $documentableId,
            'type' => $validated['type'],
            'description' => $validated['description'],
        ];

        // Handle file upload if a new file is provided
        if ($request->hasFile('file')) {
            // Delete old file
            Storage::disk('public')->delete($document->file_path);

            // Store new file
            $file = $request->file('file');
            $fileName = time().'_'.$file->getClientOriginalName();
            $filePath = $file->storeAs('documents', $fileName, 'public');

            $updateData['file_path'] = $filePath;
            $updateData['file_name'] = $file->getClientOriginalName();
            $updateData['mime_type'] = $file->getMimeType();
            $updateData['file_size'] = $file->getSize();
        }

        $document->update($updateData);

        return redirect()->route('documents.index')
            ->with('success', __('app.document_updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document): RedirectResponse
    {
        $this->authorize('delete', $document);

        // Delete the file from storage
        Storage::disk('public')->delete($document->file_path);

        $document->delete();

        return redirect()->route('documents.index')
            ->with('success', __('app.document_deleted_successfully'));
    }
}
