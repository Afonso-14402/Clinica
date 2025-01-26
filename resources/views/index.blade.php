@extends('layouts.admin')

@section('content')
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
    .stat-card {
        border-radius: 10px;
        color: white;
        padding: 20px;
        text-align: center;
        transition: transform 0.2s ease-in-out;
    }
    .stat-card:hover {
        transform: scale(1.05);
    }
    .stat-card .icon {
        font-size: 40px;
        margin-bottom: 10px;
    }
    .stat-card .number {
        font-size: 32px;
        font-weight: bold;
    }
    .stat-card .description {
        font-size: 16px;
    }
    .table-container {
        background: #fff;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin-top: 30px;
    }
    .table thead th {
        background: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
    }
    .activity-container {
        background: #fff;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin-top: 30px;
    }
</style>


@endsection
