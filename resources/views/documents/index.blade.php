@extends('layouts.app')

@section('title', 'Documents')
@section('page-title', 'Document Management')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title mb-0">All Documents</h3>
    </div>
    <div class="card-body border-bottom">
        <form method="GET" class="form-inline flex-wrap">
            <input type="search" name="q" class="form-control form-control-sm mr-2 mb-2"
                   placeholder="Search by title…" value="{{ $search }}">
            <select name="category" class="form-control form-control-sm mr-2 mb-2">
                <option value="">All categories</option>
                @foreach (\App\Models\Document::CATEGORIES as $value => $label)
                    <option value="{{ $value }}" @selected($category->toString() === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-sm btn-outline-secondary mb-2">Filter</button>
            @if ($search->isNotEmpty() || $category->isNotEmpty())
                <a href="{{ route('documents.index') }}" class="btn btn-sm btn-link mb-2">Clear</a>
            @endif
        </form>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Attached To</th>
                    <th>Size</th>
                    <th>Uploaded</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($documents as $document)
                    <tr>
                        <td>{{ $document->title }}</td>
                        <td>{{ \App\Models\Document::CATEGORIES[$document->category] ?? $document->category }}</td>
                        <td>
                            @if ($document->parentShowUrl())
                                <span class="text-muted">{{ $document->parent_type_label }}:</span>
                                <a href="{{ $document->parentShowUrl() }}">{{ $document->parentLabel() }}</a>
                            @else
                                —
                            @endif
                        </td>
                        <td>{{ $document->formatted_size }}</td>
                        <td>{{ $document->uploaded_at->format('d M Y') }}</td>
                        <td class="text-right text-nowrap">
                            <a href="{{ route('documents.show', $document) }}" class="btn btn-xs btn-default">View</a>
                            <a href="{{ route('documents.download', $document) }}" class="btn btn-xs btn-default">Download</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            No documents uploaded yet. Attach files from a citizen, visa, or assistance case record.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($documents->hasPages())
        <div class="card-footer">{{ $documents->links() }}</div>
    @endif
</div>
@endsection
