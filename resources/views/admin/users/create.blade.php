@extends('layouts.app')

@section('title', 'Create New User')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-sm-6">
                <h3>User Management</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
                    <li class="breadcrumb-item active">Create</li>
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
                    <form method="POST" action="{{ route('admin.users.store') }}">
                        @csrf
                        
                        @include('admin.users._form')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        var selectedTenantId = '{{ old('tenant_id', $user->tenant_id ?? '') }}';
        var selectedBranchId = '{{ old('branch_id', $user->branch_id ?? '') }}';

        function fetchBranches(tenantId, targetBranchId) {
            if (!tenantId) {
                $('#branch_id').html('<option value="">-- Select Tenant First --</option>');
                return;
            }
            $.ajax({
                url: '{{ route("admin.get-branches", ["tenantId" => ":tenantId"]) }}'.replace(':tenantId', tenantId),
                type: 'GET',
                success: function(data) {
                    var branchSelect = $('#branch_id');
                    branchSelect.html('<option value="">-- Select Branch --</option>');
                    $.each(data, function(key, value) {
                        var option = $('<option></option>').attr('value', value.id).text(value.name);
                        if (value.id == targetBranchId) {
                            option.attr('selected', 'selected');
                        }
                        branchSelect.append(option);
                    });
                }
            });
        }

        // Trigger on page load if a tenant is already selected (for edit page)
        if (selectedTenantId) {
            fetchBranches(selectedTenantId, selectedBranchId);
        }

        // Trigger on tenant change
        // $('#tenant_id').change(function(){
        //     console.log('Tenant changed');
        // });
        $('#tenant_id').on('change', function() {
            var tenantId = $(this).val();
            fetchBranches(tenantId, null);
        });
    });
</script>
@endpush