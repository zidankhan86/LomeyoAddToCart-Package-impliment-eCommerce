@extends('backend.layout.app')

@section('content')
<br><div class="col-12">
    <form class="card shadow-sm border border-secondary" method="POST" action="{{ route('product.update', $product->id) }}" enctype="multipart/form-data" style="max-width: 1000px; margin: 0 auto;">
        @csrf
        @method('PUT')
        <div class="card-body p-3">
            <h3 class="text-center mb-3">Edit Product</h3>

            <div class="row g-3">
                <!-- Product Name -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Product Name</label>
                    <input type="text" class="form-control" name="name" value="{{ old('name', $product->name) }}" placeholder="Enter product name" required>
                </div>

                <!-- Category -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Category</label>
                    <select class="form-control" name="category_id" required>
                        <option value="" disabled>Select a category</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Price -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Price (৳)</label>
                    <input type="number" name="price" class="form-control" value="{{ old('price', $product->price) }}" placeholder="Enter price" step="0.01" min="0" required>
                </div>

                <!-- Thumbnail -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Thumbnail Image</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    <small class="text-muted">JPEG, PNG formats recommended. Current: <img src="{{ asset('public/uploads/' . $product->image) }}" style="max-width: 100px;" alt="Product Thumbnail"></small>
                </div>

                <!-- Product Images -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Additional Images</label>
                    <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
                    <small class="text-muted">You can upload multiple images.</small>
                    <div class="mt-2">
                        @foreach($product->images as $images)
                            <img src="{{ asset($images->images) }}" style="max-width: 100px; margin-right: 5px;" alt="Product Image">
                        @endforeach
                    </div>
                </div>

                <!-- Description -->
                <div class="col-md-12">
                    <label class="form-label fw-semibold">Product Description</label>
                    <textarea name="description" rows="3" class="form-control" placeholder="Write a short description..." required>{{ old('description', $product->description) }}</textarea>
                </div>

                <!-- Status -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-control" required>
                        <option value="" disabled>Select status</option>
                        <option value="active" {{ $product->status == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $product->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <!-- Is Popular -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Popular Product?</label>
                    <select name="is_popular" class="form-control" required>
                        <option value="" disabled selected>Select option</option>
                        <option value="1" {{ $product->is_popular == 1 ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ $product->is_popular == 0 ? 'selected' : '' }}>No</option>
                    </select>
                </div>

                <!-- Variants -->
                <div class="col-md-12">
                    <hr>
                    <h5 class="fw-bold">Product Variants</h5>
                    <div id="variant-container">
                        @foreach($product->variants as $index => $variant)
                            <div class="row variant-group g-3 mb-3">
                                <div class="col-md-3">
                                    <input type="text" name="variants[{{ $index }}][color]" class="form-control"
                                           value="{{ old('variants.' . $index . '.color', $variant->color) }}"
                                           placeholder="Color (e.g. Red)" required>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="variants[{{ $index }}][size]" class="form-control"
                                           value="{{ old('variants.' . $index . '.size', $variant->size) }}"
                                           placeholder="Size (e.g. M)" required>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" name="variants[{{ $index }}][stock]" class="form-control"
                                           value="{{ old('variants.' . $index . '.stock', $variant->stock) }}"
                                           placeholder="Stock" min="0" required>
                                </div>
                                <div class="col-md-3">
                                    <button type="button" class="btn btn-outline-danger w-100 remove-variant">Remove</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" id="add-variant" class="btn btn-outline-secondary mt-2">+ Add Variant</button>
                </div>
            </div>
        </div>

        <div class="card-footer bg-white text-end py-3">
            <button type="submit" class="btn btn-primary px-4">Update Product</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    let variantIndex = {{ count($product->variants) }};

    document.getElementById('add-variant').addEventListener('click', function () {
        const container = document.getElementById('variant-container');

        const newGroup = document.createElement('div');
        newGroup.classList.add('row', 'variant-group', 'g-3', 'mb-3');
        newGroup.innerHTML = `
            <div class="col-md-3">
                <input type="text" name="variants[${variantIndex}][color]" class="form-control" placeholder="Color" required>
            </div>
            <div class="col-md-3">
                <input type="text" name="variants[${variantIndex}][size]" class="form-control" placeholder="Size" required>
            </div>
            <div class="col-md-3">
                <input type="number" name="variants[${variantIndex}][stock]" class="form-control" placeholder="Stock" min="0" required>
            </div>
            <div class="col-md-3">
                <button type="button" class="btn btn-outline-danger w-100 remove-variant">Remove</button>
            </div>
        `;
        container.appendChild(newGroup);
        variantIndex++;
    });

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-variant')) {
            e.target.closest('.variant-group').remove();
        }
    });
</script>
@endpush
