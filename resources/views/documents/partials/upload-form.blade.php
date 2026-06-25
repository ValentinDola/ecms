<form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="documentable_type" value="{{ $parent::class }}">
    <input type="hidden" name="documentable_id" value="{{ $parent->id }}">

    <div class="row">
        <div class="col-md-4">
            <div class="form-group mb-2">
                <label for="doc_title_{{ $parent->id }}" class="mb-1">Title <span class="text-danger">*</span></label>
                <input type="text" name="title" id="doc_title_{{ $parent->id }}" class="form-control form-control-sm"
                       value="{{ old('documentable_id') == $parent->id ? old('title') : '' }}" required>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group mb-2">
                <label for="doc_category_{{ $parent->id }}" class="mb-1">Category <span class="text-danger">*</span></label>
                <select name="category" id="doc_category_{{ $parent->id }}" class="form-control form-control-sm" required>
                    @foreach (\App\Models\Document::CATEGORIES as $value => $label)
                        <option value="{{ $value }}" @selected(old('documentable_id') == $parent->id && old('category') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group mb-2">
                
                <label for="doc_file_{{ $parent->id }}" class="mb-1">File <span class="text-danger">*</span></label>
                {{-- <i data-lucide="file-up" class="mr-2"></i> --}}
{{-- <button type="button" class="btn btn-primary btn-sm mb-2">
                <i width="16" height="16" data-lucide="clipboard-plus" class="mr-2"></i>  --}}
                <input type="file" name="file" id="doc_file_{{ $parent->id }}" class="form-control-file form-control-sm"
                       accept=".pdf,.jpg,.jpeg,.png" required>
            {{-- </button> --}}
                
            </div>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary btn-sm mb-2">
                <i width="16" height="16" data-lucide="upload" class="mr-2"></i> Upload
            </button>
        </div>
    </div>
    <small class="text-muted">PDF, JPG, or PNG — max 10 MB</small>
</form>
