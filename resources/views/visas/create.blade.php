@extends('layouts.app')

@section('title', 'New Visa')
@section('page-title', 'New Visa Record')

@section('content')
<div class="card">
    <form action="{{ route('visas.store') }}" method="POST">
        @csrf
        <div class="card-body">
            @include('visas._form')
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Save Visa Record</button>
            <a href="{{ route('visas.index') }}" class="btn btn-default">Cancel</a>
        </div>
    </form>
</div>
@endsection
