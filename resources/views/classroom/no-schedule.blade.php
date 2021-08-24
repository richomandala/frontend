@extends('layout.app')

@section('content')
<div id="main-card" class="card">
    <div class="card-header">
        <h4>Roomchat</h4>
    </div>
    <div class="card-body">
        <div class="empty-state" data-height="400">
            <div class="empty-state-icon bg-danger">
                <i class="fas fa-times"></i>
            </div>
            <h2>No Schedule Today</h2>
            <p class="lead">
                You have no schedule today, check your schedule on the <a href="{{ url('/') }}">Dashboard</a>
            </p>
        </div>
    </div>
</div>
@endsection
