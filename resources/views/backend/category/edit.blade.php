@extends('backend.layout.app')
@section('content')

<div class="d-flex justify-content-center">
    <div class="col-md-12 col-lg-8 py-5">
        <form action="{{ route('category.update', $category->id) }}" method="POST" class="card">
            @csrf
            @method('PUT')
            <div class="card-header">
                <h3 class="card-title">Edit Category</h3>
            </div>
            <div class="row card-body">
                <div class="mb-3 col-md-6">
                    <label class="form-label">Type Name</label>
                    <input type="text" name="name" class="form-control" value="{{ $category->name }}" placeholder="Category Name">
                </div>
                <div class="mb-3 col-md-6">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="1" {{ $category->status ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ !$category->status ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Select Icon</label>
                    <div class="row g-2 border rounded p-3" style="max-height: 240px; overflow-y: auto;">
                        @php
                            $icons = [
                                'bi-house', 'bi-person', 'bi-gear', 'bi-book', 'bi-camera',
                                'bi-cart', 'bi-heart', 'bi-star', 'bi-bell', 'bi-envelope',
                                'bi-cup', 'bi-music-note', 'bi-laptop', 'bi-phone', 'bi-globe',
                                'bi-award', 'bi-calendar', 'bi-lightning', 'bi-palette', 'bi-puzzle',
                                'bi-tree', 'bi-shield-lock', 'bi-cloud-sun', 'bi-truck'
                            ];
                        @endphp
                        @foreach($icons as $icon)
                            <div class="col-2 text-center">
                                <div class="icon-item p-2 border rounded {{ $category->icon === $icon ? 'active' : '' }}" style="cursor: pointer;" data-icon="{{ $icon }}">
                                    <i class="bi {{ $icon }} fs-3"></i>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <input type="hidden" name="icon" id="iconInput" value="{{ $category->icon }}">
                </div>
            </div>
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary">Update Category</button>
            </div>
        </form>
    </div>
</div>

<style>
    .icon-item.active {
        background-color: #0d6efd;
        color: #fff;
        border-color: #0d6efd;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const iconItems = document.querySelectorAll('.icon-item');
        const iconInput = document.getElementById('iconInput');
        const selectedIcon = iconInput.value;

        // Highlight the already selected icon on page load
        iconItems.forEach(item => {
            if (item.getAttribute('data-icon') === selectedIcon) {
                item.classList.add('active');
            }

            item.addEventListener('click', function () {
                iconItems.forEach(i => i.classList.remove('active'));
                this.classList.add('active');
                iconInput.value = this.getAttribute('data-icon');
            });
        });
    });
</script>

@endsection
