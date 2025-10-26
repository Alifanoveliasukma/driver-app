@extends('layouts.template-planner')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-body text-center">
            <h4>{{ $title }}</h4>
            <p class="mt-3 mb-4">
                <strong>{{ $name }}</strong><br>
                <small>{{ $username }}</small>
            </p>

             <form action="{{ route('logout') }}" method="POST" class="tile mt-3">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-lg btn-block" style="width: 100%;">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
