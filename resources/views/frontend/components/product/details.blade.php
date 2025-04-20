@extends('frontend.layout.app')
@push('styles')
<style>
    .product-image {
            max-height: 400px;
            object-fit: cover;
        }
        .thumbnail {
            width: 80px;
            height: 80px;
            object-fit: cover;
            cursor: pointer;
            opacity: 0.6;
            transition: opacity 0.3s ease;
        }
        .thumbnail:hover, .thumbnail.active {
            opacity: 1;
        }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">

@endpush
@section('content')


<div class="container mt-5">
    <div class="row">
        <!-- Product Images -->
        <div class="col-md-6 mb-4">

            {{-- @dd(url('/public/uploads/', $product->image)) --}}
            <img src="{{url('/public/uploads/', $product->image)}}" alt="Product" class="img-fluid rounded mb-3 product-image" id="mainImage">


            <div class="d-flex justify-content-between">
                @foreach ($product->images as $image)

                {{-- @dd(asset($image->images)) --}}
                <img src="{{asset($image->images)}}" alt="Thumbnail 1" class="thumbnail rounded active" onclick="changeImage(event, this.src)">
                @endforeach
            </div>
        </div>

        <!-- Product Details -->
        <div class="col-md-6">
            <h2 class="mb-3">{{$product->name}}</h2>
            <p class="text-muted mb-4">SKU: WH1000XM4</p>
            <div class="mb-3">
                <span class="h4 me-2">BDT {{$product->price}}</span>
                <span class="text-muted"><s>$399.99</s></span>
            </div>

            <p class="mb-4">
                {{nl2br($product->description)}}
            </p>
            <div class="mb-4">
                <h5>Color:</h5>
                <div class="btn-group" role="group" aria-label="Color selection">
                    <input type="radio" class="btn-check" name="color" id="black" autocomplete="off" checked>
                    <label class="btn btn-outline-dark" for="black">Black</label>
                    <input type="radio" class="btn-check" name="color" id="silver" autocomplete="off">
                    <label class="btn btn-outline-secondary" for="silver">Silver</label>
                    <input type="radio" class="btn-check" name="color" id="blue" autocomplete="off">
                    <label class="btn btn-outline-primary" for="blue">Blue</label>
                </div>
            </div>
            <div class="mb-4">
                <label for="quantity" class="form-label">Quantity:</label>
                <input type="number" class="form-control" id="quantity" value="1" min="1" style="width: 80px;">
            </div>
            <button class="btn btn-primary btn-lg mb-3 me-2">
                    <i class="bi bi-cart-plus"></i> Add to Cart
                </button>
            <button class="btn btn-outline-secondary btn-lg mb-3">
                    <i class="bi bi-heart"></i> Add to Wishlist
                </button>

        </div>
    </div>
</div>



@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function changeImage(event, src) {
            document.getElementById('mainImage').src = src;
            document.querySelectorAll('.thumbnail').forEach(thumb => thumb.classList.remove('active'));
            event.target.classList.add('active');
        }
</script>
@endpush
