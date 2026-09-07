@extends('layouts.dashboard')

@section('title', $pageTitle ?? 'Dashboard')
@section('page-title', $pageTitle ?? 'Dashboard')
@section('timestamp', now()->format('d M Y, H:i'))

@section('content')
    <div class="page-heading">
        <div>
            <p class="eyebrow">Workspace</p>
            <h1>{{ $pageTitle ?? 'Dashboard' }}</h1>
            <p>{{ $pageDesc ?? 'Halaman ini belum tersedia.' }}</p>
        </div>
    </div>
@endsection
