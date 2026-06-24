@php($case = $case ?? null)
@php($selectedCitizen = $selectedCitizen ?? null)

<div class="form-group">
    <label for="citizen_search">Citizen <span class="text-danger">*</span></label>
    <div class="row">
        <div class="col-md-8">
            <input type="text" id="citizen_search" class="form-control"
                   placeholder="Search citizen by name or passport…"
                   autocomplete="off"
                   list="citizen_suggestions">
            <datalist id="citizen_suggestions"></datalist>
        </div>
        <div class="col-md-4">
            <select name="citizen_id" id="citizen_id" class="form-control" required>
                <option value="">— Select citizen —</option>
                @foreach ($citizens as $citizen)
                    <option value="{{ $citizen->id }}"
                            @selected(old('citizen_id', $case?->citizen_id ?? $selectedCitizen?->id ?? '') == $citizen->id)>
                        {{ $citizen->full_name }} ({{ $citizen->passport_number }})
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>

@if (isset($nextCaseNumber))
    <div class="alert alert-light border">
        <strong>Next reference number:</strong> {{ $nextCaseNumber }} <small class="text-muted">(assigned on save)</small>
    </div>
@elseif ($case)
    <div class="form-group">
        <label>Reference Number</label>
        <input type="text" class="form-control" value="{{ $case->ref_no }}" readonly disabled>
    </div>
@endif

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="case_type">Case Type <span class="text-danger">*</span></label>
            <select name="case_type" id="case_type" class="form-control" required>
                @foreach (\App\Models\AssistanceCase::TYPES as $value => $label)
                    <option value="{{ $value }}" @selected(old('case_type', $case?->case_type ?? '') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="status">Status <span class="text-danger">*</span></label>
            <select name="status" id="status" class="form-control" required>
                @foreach (\App\Models\AssistanceCase::STATUSES as $value => $label)
                    <option value="{{ $value }}" @selected(old('status', $case?->status ?? 'open') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="opened_at">Opened Date <span class="text-danger">*</span></label>
            <input type="date" name="opened_at" id="opened_at" class="form-control"
                   value="{{ old('opened_at', $case?->opened_at?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" required>
        </div>
    </div>
</div>

@if ($case)
    <div class="form-group" id="closed_at_group" style="{{ old('status', $case->status) === 'closed' ? '' : 'display:none' }}">
        <label for="closed_at">Closed Date</label>
        <input type="date" name="closed_at" id="closed_at" class="form-control"
               value="{{ old('closed_at', $case->closed_at?->format('Y-m-d') ?? '') }}">
    </div>
@endif

<div class="form-group">
    <label for="description">Description <span class="text-danger">*</span></label>
    <textarea name="description" id="description" class="form-control" rows="4" required>{{ old('description', $case?->description ?? '') }}</textarea>
</div>

<div class="form-group">
    <label for="actions_taken">Actions Taken</label>
    <textarea name="actions_taken" id="actions_taken" class="form-control" rows="3">{{ old('actions_taken', $case?->actions_taken ?? '') }}</textarea>
</div>

<div class="form-group">
    <label for="notes">Notes</label>
    <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes', $case?->notes ?? '') }}</textarea>
</div>

@push('scripts')
<script>
(function () {
    var citizenSelect = document.getElementById('citizen_id');
    var searchInput = document.getElementById('citizen_search');
    var statusSelect = document.getElementById('status');
    var closedAtGroup = document.getElementById('closed_at_group');
    var lookupUrl = @json(route('visas.citizens.lookup'));
    var lookupTimer;

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            clearTimeout(lookupTimer);
            var q = this.value.trim();
            if (q.length < 2) return;

            lookupTimer = setTimeout(function () {
                fetch(lookupUrl + '?q=' + encodeURIComponent(q))
                    .then(function (r) { return r.json(); })
                    .then(function (citizens) {
                        var datalist = document.getElementById('citizen_suggestions');
                        datalist.innerHTML = '';
                        citizens.forEach(function (c) {
                            var opt = document.createElement('option');
                            opt.value = c.full_name + ' (' + c.passport_number + ')';
                            datalist.appendChild(opt);
                        });
                    });
            }, 300);
        });

        searchInput.addEventListener('change', function () {
            var value = this.value;
            for (var i = 0; i < citizenSelect.options.length; i++) {
                if (citizenSelect.options[i].text === value) {
                    citizenSelect.selectedIndex = i;
                    break;
                }
            }
        });
    }

    if (statusSelect && closedAtGroup) {
        statusSelect.addEventListener('change', function () {
            closedAtGroup.style.display = this.value === 'closed' ? '' : 'none';
        });
    }
})();
</script>
@endpush
