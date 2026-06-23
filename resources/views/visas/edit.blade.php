@extends('layouts.app')

@section('title', 'Edit Visa')
@section('page-title', 'Edit Visa Record')

@section('content')
<div class="card">
    <form action="{{ route('visas.update', $visa) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            @include('visas._form')
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Update Visa Record</button>
            <a href="{{ route('visas.show', $visa) }}" class="btn btn-default">Cancel</a>
        </div>
    </form>
</div>
@endsection
