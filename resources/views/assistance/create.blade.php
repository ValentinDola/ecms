@extends('layouts.app')

@section('title', 'Open Case')
@section('page-title', 'Open Assistance Case')

@section('content')
<div class="card">
    <form action="{{ route('assistance.store') }}" method="POST">
        @csrf
        <div class="card-body">
            @include('assistance._form')
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Open Case</button>
            <a href="{{ route('assistance.index') }}" class="btn btn-default">Cancel</a>
        </div>
    </form>
</div>
@endsection
