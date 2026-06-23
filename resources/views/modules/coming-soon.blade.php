@extends('layouts.app')

@section('title', $module)
@section('page-title', $module)

@section('content')
<div class="card">
    <div class="card-body text-center py-5">
        <i class="fas fa-tools fa-3x text-muted mb-3"></i>
        <h4>{{ $module }}</h4>
        <p class="text-muted">This module will be implemented in the next phase.</p>
        <a href="{{ route('dashboard') }}" class="btn btn-primary">Back to Dashboard</a>
    </div>
</div>
@endsection
