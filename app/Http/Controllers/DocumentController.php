<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDocumentRequest;
use App\Models\AssistanceCase;
use App\Models\Citizen;
use App\Models\Document;
use App\Models\Visa;
use App\Services\DocumentStorageService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentController extends Controller
{
    public function __construct(private DocumentStorageService $storageService) {}

    public function index(Request $request)
    {
        $search = $request->string('q')->trim();
        $category = $request->string('category')->trim();

        $documents = Document::query()
            ->with('documentable')
            ->when($search->isNotEmpty(), function ($query) use ($search) {
                $term = $search->toString();
                $query->where('title', 'like', "%{$term}%");
            })
            ->when($category->isNotEmpty(), fn ($query) => $query->where('category', $category->toString()))
            ->orderByDesc('uploaded_at')
            ->paginate(25)
            ->withQueryString();

        return view('documents.index', compact('documents', 'search', 'category'));
    }

    public function store(StoreDocumentRequest $request)
    {
        $parent = $this->resolveParent(
            $request->input('documentable_type'),
            (int) $request->input('documentable_id')
        );

        $document = $this->storageService->store($parent, $request->file('file'), [
            'title' => $request->input('title'),
            'category' => $request->input('category'),
        ]);

        return redirect()
            ->to($this->parentShowUrl($parent))
            ->with('success', 'Document uploaded successfully.');
    }

    public function show(Document $document)
    {
        $document->load('documentable');

        return view('documents.show', compact('document'));
    }

    public function download(Document $document): StreamedResponse
    {
        if (! Storage::disk('public')->exists($document->file_path)) {
            abort(404, 'File not found.');
        }

        $filename = Str::slug($document->title).'.'.pathinfo($document->file_path, PATHINFO_EXTENSION);

        return Storage::disk('public')->download($document->file_path, $filename);
    }

    public function destroy(Document $document)
    {
        $parent = $document->documentable;
        $this->storageService->delete($document);

        if ($parent) {
            return redirect()
                ->to($this->parentShowUrl($parent))
                ->with('success', 'Document deleted.');
        }

        return redirect()
            ->route('documents.index')
            ->with('success', 'Document deleted.');
    }

    private function resolveParent(string $type, int $id): Model
    {
        return match ($type) {
            Citizen::class => Citizen::findOrFail($id),
            Visa::class => Visa::findOrFail($id),
            AssistanceCase::class => AssistanceCase::findOrFail($id),
            default => abort(422, 'Invalid document parent type.'),
        };
    }

    private function parentShowUrl(Model $parent): string
    {
        return match ($parent::class) {
            Citizen::class => route('citizens.show', $parent),
            Visa::class => route('visas.show', $parent),
            AssistanceCase::class => route('assistance.show', $parent),
            default => route('documents.index'),
        };
    }
}
