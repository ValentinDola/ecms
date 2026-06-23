@extends('layouts.app')

@section('title', 'Edit Citizen')
@section('page-title', 'Edit Citizen')

@section('content')
<div class="card">
    <form action="{{ route('citizens.update', $citizen) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            @include('citizens._form')
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Update Citizen</button>
            <a href="{{ route('citizens.show', $citizen) }}" class="btn btn-default">Cancel</a>
        </div>
    </form>
</div>
@endsection
