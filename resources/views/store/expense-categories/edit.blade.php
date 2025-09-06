@extends('layouts.app')

@section('title', 'Edit Categories')

@section('content')
<div class="container-fluid">
    <div class="page-header">
      <div class="row">
        <div class="col-sm-6">
          <h3>Expense Categories</h3>
          <ol class="breadcrumb">
             <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('store.expense-categories.index') }}">Expense Categories</a></li>
            <li class="breadcrumb-item active">Edit</li>
          </ol>
        </div>
      </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('store.expense-categories.update', $expenseCategory->id) }}">
                        @csrf
                        @method('PUT')
                        @include('store.expense-categories._form')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection