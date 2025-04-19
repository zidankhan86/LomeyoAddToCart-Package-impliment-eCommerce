@extends('backend.layout.app')
@section('content')

<div class="d-flex justify-content-center">
    <div class="col-md-8">
        <form class="card shadow-lg border-0" method="POST" action="{{ route('product.update', $product->id) }}"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-header bg-primary text-white">
                <h3 class="card-title text-center mb-0">Edit Product</h3>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <!-- Product Name -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label fw-bold">Product Name</label>
                            <input type="text" class="form-control border-2 border-primary" name="name"
                                value="{{ old('name', $product->name) }}" placeholder="Enter product name" required>
                        </div>
                    </div>

                    <!-- Category Selection -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label fw-bold">Category</label>
                            <select class="form-control border-2 border-primary" name="category_id" required>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $product->category_id == $category->id ?
                                    'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Price -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label fw-bold">Price &#2547;</label>
                            <input type="number" class="form-control border-2 border-primary" name="price"
                                value="{{ old('price', $product->price) }}" placeholder="Enter price" step="0.01"
                                min="0" required>
                        </div>
                    </div>

                    <!-- Main Image Upload -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label fw-bold">Product Thumbnail</label>
                            <input type="file" class="form-control border-2 border-primary" name="image"
                                accept="image/*" onchange="previewImage(event)">
                            <small class="form-text text-muted">Current image:
                                <a href="{{ asset($product->image) }}" target="_blank">View</a>
                            </small>
                            <div id="image-preview-container" class="mt-2" style="display: none;">
                                <img id="image-preview" class="img-thumbnail" style="max-width: 200px;">
                            </div>
                        </div>
                    </div>

                    <!-- Multiple Images Upload -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label fw-bold">Additional Images</label>
                            <input type="file" class="form-control border-2 border-primary" name="images[]"
                                accept="image/jpeg,image/png" multiple>
                            <small class="form-text text-muted">
                                Current images:
                                @foreach($product->images as $image)
                                <a href="{{ asset($image->path) }}" target="_blank" class="me-2">Image {{
                                    $loop->iteration }}</a>
                                @endforeach
                            </small>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label fw-bold">Product Description</label>
                            <textarea rows="3" name="description" class="form-control border-2 border-primary"
                                placeholder="Provide a brief description of the product"
                                required>{{ old('description', $product->description) }}</textarea>
                        </div>
                    </div>

                    <div class="row my-4">
                        <!-- Status -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-bold">Status</label>
                                <select class="form-control border-2 border-primary" name="status" required>
                                    <option value="" disabled {{ old('status', $product->status ?? '') == '' ? 'selected' : '' }}>Select status</option>
                                    <option value="active" {{ old('status', $product->status ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $product->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>

                        <!-- Is Popular -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label fw-bold">Is Popular</label>
                                <select class="form-control border-2 border-primary" name="is_popular" required>
                                    <option value="" disabled {{ old('is_popular', $product->is_popular ?? '') === '' ? 'selected' : '' }}>Is this product popular?</option>
                                    <option value="1" {{ old('is_popular', $product->is_popular ?? '') == '1' ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ old('is_popular', $product->is_popular ?? '') == '0' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
            <div class="card-footer text-end bg-light">
                <button type="submit" class="btn btn-primary px-4">Update Product</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function previewImage(event) {
        const file = event.target.files[0];
        const previewContainer = document.getElementById('image-preview-container');
        const previewImage = document.getElementById('image-preview');

        if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                previewContainer.style.display = 'block';
                previewImage.src = e.target.result;
            };

            reader.readAsDataURL(file);
        } else {
            previewContainer.style.display = 'none';
        }
    }
</script>
@endsection
