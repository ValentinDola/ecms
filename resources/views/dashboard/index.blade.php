@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $citizenCount }}</h3>
                <p>Registered Citizens</p>
            </div>
            <div class="icon"><i width="100" height="100" data-lucide="users"></i></div>
            <a href="{{ route('citizens.index') }}" class="small-box-footer">View all <i data-lucide="move-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $visaCount }}</h3>
                <p>Visa Records</p>
            </div>
            <div class="icon"><i width="100" height="100" data-lucide="id-card"></i></div>
            <a href="{{ route('visas.index') }}" class="small-box-footer">Manage visas <i data-lucide="move-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $openCaseCount }}</h3>
                <p>Open Assistance Cases</p>
            </div>
            <div class="icon"><i width="100" height="100" data-lucide="hand-helping"></i></div>
            <a href="{{ route('assistance.index') }}" class="small-box-footer">View cases <i data-lucide="move-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-secondary">
            <div class="inner">
                <h3>{{ $documentCount }}</h3>
                <p>Stored Documents</p>
            </div>
            <div class="icon"><i width="80" height="80" data-lucide="folder-open"></i></div>
            <a href="{{ route('documents.index') }}" class="small-box-footer">View documents <i data-lucide="move-right"></i></a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Quick Actions</h3>
            </div>
            <div class="card-body">
                <a href="{{ route('citizens.create') }}" class="btn btn-primary mr-2 mb-2">
                    <i data-lucide="user-plus"></i> Register Citizen
                </a>

                <a href="{{ route('search') }}" class="btn btn-outline-primary mr-2 mb-2">
                    <i data-lucide="search"></i> Global Search
                </a>

                <a href="{{ route('visas.create') }}" class="btn btn-outline-success mr-2 mb-2">
                    <i data-lucide="id-card"></i> Create Visa
                </a>

                <a href="{{ route('assistance.create') }}" class="btn btn-outline-warning mr-2 mb-2">
                    <i data-lucide="hand-helping"></i> Assistance Case
                </a>
                <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary mr-2 mb-2">
                    <i data-lucide="file-text"></i> Reports
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
