@extends('layouts.app')

@section('title', 'Edit Case')
@section('page-title', 'Edit ' . $case->case_number)

@section('content')
<div class="card">
    <form action="{{ route('assistance.update', $case) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            @include('assistance._form')
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Update Case</button>
            <a href="{{ route('assistance.show', $case) }}" class="btn btn-default">Cancel</a>
        </div>
    </form>
</div>
@endsection
