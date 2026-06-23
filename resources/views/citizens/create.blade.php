@extends('layouts.app')

@section('title', 'Register Citizen')
@section('page-title', 'Register Citizen')

@section('content')
<div class="card">
    <form action="{{ route('citizens.store') }}" method="POST">
        @csrf
        <div class="card-body">
            @include('citizens._form')
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Save Citizen</button>
            <a href="{{ route('citizens.index') }}" class="btn btn-default">Cancel</a>
        </div>
    </form>
</div>
@endsection
