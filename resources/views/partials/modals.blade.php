{{-- Delete confirmation modal --}}
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="confirmModalTitle">
                    <i class="fas fa-exclamation-triangle mr-1"></i> Confirm deletion
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="confirmModalMessage" class="mb-0"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmModalSubmit">
                    <i class="fas fa-trash mr-1"></i> Delete
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Flash / validation alert modal --}}
@php
    $flashType = session('success') ? 'success' : (session('error') ? 'danger' : ($errors->any() ? 'danger' : null));
    $flashTitle = match ($flashType) {
        'success' => 'Success',
        'danger' => 'Attention',
        default => null,
    };
    $flashIcon = $flashType === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
@endphp

@if ($flashType)
    <div class="modal fade" id="flashModal" tabindex="-1" role="dialog" aria-labelledby="flashModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-{{ $flashType }} text-white">
                    <h5 class="modal-title" id="flashModalTitle">
                        <i class="fas {{ $flashIcon }} mr-1"></i> {{ $flashTitle }}
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if (session('success'))
                        <p class="mb-0">{{ session('success') }}</p>
                    @endif

                    @if (session('error'))
                        <p class="mb-0">{{ session('error') }}</p>
                    @endif

                    @if ($errors->any())
                        <ul class="mb-0 pl-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-{{ $flashType === 'success' ? 'success' : 'danger' }}" data-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
@endif

@push('scripts')
<script>
(function ($) {
    var pendingForm = null;

    $(document).on('submit', 'form[data-confirm]', function (e) {
        if ($(this).data('confirmed')) {
            return;
        }

        e.preventDefault();
        pendingForm = this;

        var message = $(this).data('confirm') || 'Are you sure you want to continue?';
        var title = $(this).data('confirm-title') || 'Confirm deletion';
        var submitLabel = $(this).data('confirm-submit') || 'Delete';

        $('#confirmModalTitle').html('<i class="fas fa-exclamation-triangle mr-1"></i> ' + title);
        $('#confirmModalMessage').text(message);
        $('#confirmModalSubmit').html('<i class="fas fa-trash mr-1"></i> ' + submitLabel);
        $('#confirmModal').modal('show');
    });

    $('#confirmModalSubmit').on('click', function () {
        if (!pendingForm) {
            return;
        }

        $(pendingForm).data('confirmed', true);
        $('#confirmModal').modal('hide');
        pendingForm.submit();
        pendingForm = null;
    });

    $('#confirmModal').on('hidden.bs.modal', function () {
        pendingForm = null;
    });

    @if ($flashType)
        $('#flashModal').modal('show');
    @endif
})(jQuery);
</script>
@endpush
