@extends('layouts.admin')

<style>
    .profile-img {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #ccc;
    }
    .timer {
        font-size: 1.5rem;
        text-align: center;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 10px;
        background-color: #f8f9fa;
    }
    .btn-group-custom {
        margin-top: 20px;
    }
    .details h5 {
        font-size: 1.25rem;
        font-weight: bold;
    }
    .details p {
        margin-bottom: 8px;
        font-size: 1rem;
    }
    .prescription-btn {
        background-color: #28a745;
        color: white;
        font-size: 1rem;
    }
    .finalize-btn {
        background-color: #dc3545;
        color: white;
    }
    .section-title {
        font-weight: bold;
        margin-top: 20px;
        margin-bottom: 10px;
        border-bottom: 2px solid #ddd;
        padding-bottom: 5px;
    }
</style>

@section('content')

@endsection
