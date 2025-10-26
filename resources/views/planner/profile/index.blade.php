@extends('layouts.template-planner')

@section('title', 'Profil Pengguna')

@section('content')
<div class="container py-5">
    <div class="d-flex flex-column align-items-center justify-content-center text-center" style="min-height: 60vh;">
        <div class="card shadow-sm border-0 p-4" style="max-width: 400px; width: 100%;">
            <div class="mb-3">
                <i class="bi bi-person-circle text-primary" style="font-size: 4rem;"></i>
            </div>

            <h5 class="fw-bold text-dark mb-2">Profil Pengguna</h5>
            <p class="text-muted mb-4">Anda saat ini masuk sebagai Planner</p>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger w-100">
                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
