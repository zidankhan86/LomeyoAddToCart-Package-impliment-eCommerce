@extends('backend.layout.app')
@section('content')
<div class="col-12">
    <form class="card shadow-lg border-0" method="POST" action="{{ route('product.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <h3 class="card-title text-center mb-4 text-primary">Create a New Product</h3>
            <div class="row g-3">
                <!-- Product Name -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label fw-bold">Product Name</label>
                        <input type="text" class="form-control border-2 border-primary" name="name" placeholder="Enter product name" required>
                    </div>
                </div>

                <!-- Category Selection -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label fw-bold">Category</label>
                        <select class="form-control border-2 border-primary" name="category_id" required>
                            <option value="" disabled selected>Select a category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Price -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label fw-bold">Price &#2547; </label>
                        <input type="number" class="form-control border-2 border-primary" name="price" placeholder="Enter price" step="0.01" min="0" required>
                    </div>
                </div>

                <!-- Image Upload -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label fw-bold">Product Thumbnail</label>
                        <input type="file" class="form-control border-2 border-primary" name="image" accept="image/*" required>
                        <small class="form-text text-muted">Upload a high-quality image (JPEG, PNG).</small>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label fw-bold">Product Images</label>
                        <input type="file" class="form-control border-2 border-primary" name="images[]" accept="image/jpeg,image/png" multiple required>
                        <small class="form-text text-muted">Upload high-quality images (JPEG, PNG).</small>
                    </div>
                </div>

                <!-- Description -->
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-label fw-bold">Product Description</label>
                        <textarea rows="3" name="description" class="form-control border-2 border-primary" placeholder="Provide a brief description of the product" required></textarea>
                    </div>
                </div>
            </div>

<div class="row my-4">
                <!-- Status -->
<div class="col-md-6">
    <div class="form-group">
        <label class="form-label fw-bold">Status</label>
        <select class="form-control border-2 border-primary" name="status" required>
            <option value="" disabled selected>Select status</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select>
    </div>
</div>

<!-- Is Popular -->
<div class="col-md-6">
    <div class="form-group">
        <label class="form-label fw-bold">Is Popular</label>
        <select class="form-control border-2 border-primary" name="is_popular" required>
            <option value="" disabled selected>Is this product popular?</option>
            <option value="1">Yes</option>
            <option value="0">No</option>
        </select>
    </div>
</div>

</div>

        </div>
        <div class="card-footer text-end bg-light">
            <button type="submit" class="btn btn-primary px-4">Create</button>
        </div>
    </form>
</div>

<style>
    .card {
        border-radius: 12px;
        background: linear-gradient(to bottom, #ffffff, #f9f9f9);
    }

    .form-control {
        box-shadow: none;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(46, 67, 90, 0.5);
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        transition: background-color 0.3s ease, border-color 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
    }
</style>
@endsection
