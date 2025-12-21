<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-gray-800">Categories</h1>
        <button wire:click="create" class="btn btn-primary">
            <i class="fas fa-plus"></i> New Category
        </button>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            {{-- <th>ID</th> --}}
                            <th>Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                {{-- <td>{{ $category->id }}</td> --}}
                                <td>{{ $category->name }}</td>
                                <td>
                                    <button wire:click="edit({{ $category->id }})"
                                        class="btn btn-sm btn-info text-white">
                                        Edit
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger"
                                        @click="$dispatch('open-confirm-modal', {
                                            component: '{{ $this->getId() }}',
                                            method: 'delete',
                                            params: {{ $category->id }},
                                            title: 'Delete Category?',
                                            message: 'If you delete this category, products inside might be affected.'
                                        })">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $categories->links() }}
        </div>
    </div>

    @if ($isOpen)
        <div class="modal fade show d-block" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $categoryId ? 'Edit Category' : 'Create Category' }}</h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit="{{ $categoryId ? 'update' : 'store' }}">
                            <div class="mb-3">
                                <label class="form-label">Category Name</label>
                                <input type="text" wire:model="name"
                                    class="form-control @error('name') is-invalid @enderror">
                                @error('name')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="text-end">
                                <button type="button" wire:click="closeModal" class="btn btn-secondary">Cancel</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
