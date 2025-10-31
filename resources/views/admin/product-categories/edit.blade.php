@extends('layouts.admin')

@section('title', 'เพิ่มหมวดหมู่ใหม่')

@section('breadcrumb')
    <li class="breadcrumb-item">จัดการสินค้า</li>
    <li class="breadcrumb-item"><a href="{{ route('admin.product-categories.index') }}">หมวดหมู่สินค้า</a></li>
    <li class="breadcrumb-item active"แก้ไขหมวดหมู่สินค้า</li>
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="pb-0 card-header">
                <h5>แก้ไขหมวดหมู่สินค้า</h5>
            </div>
            <form action="{{ route('admin.product-categories.update', $category->id) }}) }}" method="POST">
                @csrf
                @method('PUT')
                
                @include('admin.product-categories._form')

            </form>
        </div>
    </div>
</div>
@endsection