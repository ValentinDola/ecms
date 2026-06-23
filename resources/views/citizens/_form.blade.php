@csrf

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="first_name">First Name <span class="text-danger">*</span></label>
            <input type="text" name="first_name" id="first_name" class="form-control"
                   value="{{ old('first_name', $citizen->first_name ?? '') }}" required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="last_name">Last Name <span class="text-danger">*</span></label>
            <input type="text" name="last_name" id="last_name" class="form-control"
                   value="{{ old('last_name', $citizen->last_name ?? '') }}" required>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="passport_number">Passport Number <span class="text-danger">*</span></label>
            <input type="text" name="passport_number" id="passport_number" class="form-control"
                   value="{{ old('passport_number', $citizen->passport_number ?? '') }}" required>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="date_of_birth">Date of Birth</label>
            <input type="date" name="date_of_birth" id="date_of_birth" class="form-control"
                   value="{{ old('date_of_birth', isset($citizen) && $citizen->date_of_birth ? $citizen->date_of_birth->format('Y-m-d') : '') }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="nationality">Nationality <span class="text-danger">*</span></label>
            <input type="text" name="nationality" id="nationality" class="form-control"
                   value="{{ old('nationality', $citizen->nationality ?? 'Togolese') }}" required>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" name="phone" id="phone" class="form-control"
                   value="{{ old('phone', $citizen->phone ?? '') }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control"
                   value="{{ old('email', $citizen->email ?? '') }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label for="registration_date">Registration Date <span class="text-danger">*</span></label>
            <input type="date" name="registration_date" id="registration_date" class="form-control"
                   value="{{ old('registration_date', isset($citizen) ? $citizen->registration_date->format('Y-m-d') : now()->format('Y-m-d')) }}" required>
        </div>
    </div>
</div>

<div class="form-group">
    <label for="address_in_ghana">Address in Ghana</label>
    <textarea name="address_in_ghana" id="address_in_ghana" class="form-control" rows="2">{{ old('address_in_ghana', $citizen->address_in_ghana ?? '') }}</textarea>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="city">City</label>
            <input type="text" name="city" id="city" class="form-control"
                   value="{{ old('city', $citizen->city ?? '') }}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="region">Region</label>
            <input type="text" name="region" id="region" class="form-control"
                   value="{{ old('region', $citizen->region ?? '') }}">
        </div>
    </div>
</div>

<div class="form-group">
    <label for="notes">Notes</label>
    <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes', $citizen->notes ?? '') }}</textarea>
</div>
