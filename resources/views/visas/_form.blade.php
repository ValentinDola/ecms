@php($visa = $visa ?? null)
@php($selectedCitizen = $selectedCitizen ?? null)

<div class="form-group">
    <label for="citizen_search">Link to Citizen <small class="text-muted">(optional)</small></label>
    <div class="row">
        <div class="col-md-8">
            <input type="text" id="citizen_search" class="form-control"
                   placeholder="Search citizen by name or passport…"
                   autocomplete="off"
                   list="citizen_suggestions">
            <datalist id="citizen_suggestions"></datalist>
        </div>
        <div class="col-md-4">
            <select name="citizen_id" id="citizen_id" class="form-control">
                <option value="">— No linked citizen —</option>
                @foreach ($citizens as $citizen)
                    <option value="{{ $citizen->id }}"
                            data-first-name="{{ $citizen->first_name }}"
                            data-last-name="{{ $citizen->last_name }}"
                            data-passport="{{ $citizen->passport_number }}"
                            @selected(old('citizen_id', $visa?->citizen_id ?? $selectedCitizen?->id ?? '') == $citizen->id)>
                        {{ $citizen->full_name }} ({{ $citizen->passport_number }})
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <small class="form-text text-muted">Select a citizen to auto-fill applicant name and passport, or enter details manually below.</small>
</div>

<hr>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="applicant_first_name">Applicant First Name <span class="text-danger">*</span></label>
            <input type="text" name="applicant_first_name" id="applicant_first_name" class="form-control"
                   value="{{ old('applicant_first_name', $visa?->applicant_first_name ?? $selectedCitizen?->first_name ?? '') }}" required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="applicant_last_name">Applicant Last Name <span class="text-danger">*</span></label>
            <input type="text" name="applicant_last_name" id="applicant_last_name" class="form-control"
                   value="{{ old('applicant_last_name', $visa?->applicant_last_name ?? $selectedCitizen?->last_name ?? '') }}" required>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="visa_number">Visa Number <span class="text-danger">*</span></label>
            <input type="text" name="visa_number" id="visa_number" class="form-control"
                   value="{{ old('visa_number', $visa?->visa_number ?? '') }}" required>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="passport_number">Passport Number <span class="text-danger">*</span></label>
            <input type="text" name="passport_number" id="passport_number" class="form-control"
                   value="{{ old('passport_number', $visa?->passport_number ?? $selectedCitizen?->passport_number ?? '') }}" required>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="visa_type">Visa Type <span class="text-danger">*</span></label>
            <select name="visa_type" id="visa_type" class="form-control" required>
                @foreach (\App\Models\Visa::TYPES as $value => $label)
                    <option value="{{ $value }}" @selected(old('visa_type', $visa?->visa_type ?? '') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="issue_date">Issue Date <span class="text-danger">*</span></label>
            <input type="date" name="issue_date" id="issue_date" class="form-control"
                   value="{{ old('issue_date', $visa?->issue_date?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" required>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="expiry_date">Expiry Date <span class="text-danger">*</span></label>
            <input type="date" name="expiry_date" id="expiry_date" class="form-control"
                   value="{{ old('expiry_date', $visa?->expiry_date?->format('Y-m-d') ?? now()->addMonths(3)->format('Y-m-d')) }}" required>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="status">Status <span class="text-danger">*</span></label>
            <select name="status" id="status" class="form-control" required>
                @foreach (\App\Models\Visa::STATUSES as $value => $label)
                    <option value="{{ $value }}" @selected(old('status', $visa?->status ?? 'pending') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="form-group">
    <label for="purpose_of_visit">Purpose of Visit</label>
    <textarea name="purpose_of_visit" id="purpose_of_visit" class="form-control" rows="2">{{ old('purpose_of_visit', $visa?->purpose_of_visit ?? '') }}</textarea>
</div>

<div class="form-group">
    <label for="notes">Notes</label>
    <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes', $visa?->notes ?? '') }}</textarea>
</div>

@push('scripts')
<script>
(function () {
    var citizenSelect = document.getElementById('citizen_id');
    var searchInput = document.getElementById('citizen_search');
    var lookupUrl = @json(route('visas.citizens.lookup'));
    var lookupTimer;

    function fillFromCitizen(option) {
        if (!option || !option.value) return;
        document.getElementById('applicant_first_name').value = option.dataset.firstName || '';
        document.getElementById('applicant_last_name').value = option.dataset.lastName || '';
        document.getElementById('passport_number').value = option.dataset.passport || '';
    }

    citizenSelect.addEventListener('change', function () {
        fillFromCitizen(this.options[this.selectedIndex]);
    });

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
                        opt.dataset.id = c.id;
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
                fillFromCitizen(citizenSelect.options[i]);
                break;
            }
        }
    });

    if (citizenSelect.value) {
        fillFromCitizen(citizenSelect.options[citizenSelect.selectedIndex]);
    }
})();
</script>
@endpush
