@extends('layouts.app')
@section('title', 'แก้ไขข้อมูลลูกค้า')

@section('content')
<div class="pt-3 pb-2 mb-3 border-bottom d-flex align-items-center gap-2">
    <a href="{{ route('customers.index') }}" class="btn btn-sm btn-outline-secondary">
        <span data-feather="arrow-left"></span>
    </a>
    <h1 class="h2 mb-0">แก้ไขข้อมูลลูกค้า: <span class="text-primary">{{ $customer->name }}</span></h1>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form action="{{ route('customers.update', $customer->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    @include('master-data.customer.partial._form')

                </form>
            </div>
        </div>
    </div>
</div>
@endsection
