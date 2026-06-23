@props(['parent', 'documents'])

<div class="card mt-3">
    <div class="card-header">
        <h3 class="card-title mb-0">Documents ({{ $documents->count() }})</h3>
    </div>
    <div class="card-body border-bottom">
        @include('documents.partials.upload-form', ['parent' => $parent])
    </div>
    @if ($documents->isNotEmpty())
        <div class="card-body p-0">
            <table class="table table-sm mb-0">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Size</th>
                        <th>Uploaded</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($documents as $document)
                        <tr>
                            <td>{{ $document->title }}</td>
                            <td>{{ \App\Models\Document::CATEGORIES[$document->category] ?? $document->category }}</td>
                            <td>{{ $document->formatted_size }}</td>
                            <td>{{ $document->uploaded_at->format('d M Y') }}</td>
                            <td class="text-right text-nowrap">
                                <a href="{{ route('documents.show', $document) }}">View</a>
                                ·
                                <a href="{{ route('documents.download', $document) }}">Download</a>
                                ·
                                <form action="{{ route('documents.destroy', $document) }}" method="POST" class="d-inline"
                                      data-confirm="Delete &quot;{{ $document->title }}&quot;? This cannot be undone."
                                      data-confirm-title="Delete document">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-link btn-sm p-0 align-baseline text-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="card-body text-muted">No documents attached yet.</div>
    @endif
</div>
