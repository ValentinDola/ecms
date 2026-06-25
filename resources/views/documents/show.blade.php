@extends('layouts.app')

@section('title', $document->title)
@section('page-title', $document->title)

@section('content')
<div class="mb-3">
    <a href="{{ route('documents.download', $document) }}" class="btn btn-primary btn-sm">
        <i width="16" height="16" data-lucide="download" class="mr-2"></i> Download
    </a>
    @if ($document->parentShowUrl())
        <a href="{{ $document->parentShowUrl() }}" class="btn btn-default btn-sm">Back to {{ $document->parent_type_label }}</a>
    @endif
    <a href="{{ route('documents.index') }}" class="btn btn-default btn-sm">All Documents</a>
    <form action="{{ route('documents.destroy', $document) }}" method="POST" class="d-inline"
          data-confirm="This will permanently delete the file from storage and cannot be undone."
          data-confirm-title="Delete document">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm"><i width="16" height="16" data-lucide="trash-2" class="mr-2"></i> Delete</button>
    </form>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><h3 class="card-title">File Details</h3></div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <tr><th>Title</th><td>{{ $document->title }}</td></tr>
                    <tr><th>Category</th><td>{{ \App\Models\Document::CATEGORIES[$document->category] ?? $document->category }}</td></tr>
                    <tr><th>Type</th><td>{{ $document->mime_type ?? '—' }}</td></tr>
                    <tr><th>Size</th><td>{{ $document->formatted_size }}</td></tr>
                    <tr><th>Uploaded</th><td>{{ $document->uploaded_at->format('d M Y H:i') }}</td></tr>
                    <tr>
                        <th>Attached To</th>
                        <td>
                            @if ($document->parentShowUrl())
                                {{ $document->parent_type_label }} —
                                <a href="{{ $document->parentShowUrl() }}">{{ $document->parentLabel() }}</a>
                            @else
                                —
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">Preview</h3>
                <button type="button" class="btn btn-sm btn-default" onclick="window.print()">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>
            <div class="card-body document-preview">
                @if ($document->isImage())
                    <img src="{{ $document->url }}" alt="{{ $document->title }}" class="img-fluid">
                @elseif ($document->isPdf())
                    <iframe src="{{ $document->url }}" class="w-100" style="height: 600px; border: 1px solid #ddd;"></iframe>
                @else
                    <p class="text-muted mb-0">Preview not available for this file type.</p>
                    <a href="{{ route('documents.download', $document) }}" class="btn btn-primary mt-2">Download File</a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
@media print {
    .main-header, .main-sidebar, .content-header, .btn, form, .main-footer { display: none !important; }
    .content-wrapper, .document-preview { margin: 0 !important; padding: 0 !important; }
}
</style>
@endpush
