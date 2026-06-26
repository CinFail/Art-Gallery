@extends('layouts.app')
@section('title', 'Edit Customer')
@section('breadcrumb', 'Maintenance / Customers / Edit')

@section('content')
<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('customers.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h1 class="page-title mb-0">Edit Customer: {{ $customer->name }}</h1>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">Customer Information</div>
            <div class="card-body">
                <form action="{{ route('customers.update', $customer) }}" method="POST">
                    @csrf @method('PUT')
                    @include('customers._form')
                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Update Customer
                        </button>
                        <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
