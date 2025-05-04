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
                <span class="text-muted"><s>BDT 399.99</s></span>
            </div>

            <p class="mb-4">
                {{nl2br($product->description)}}
            </p>
            <div class="mb-4">
                <h5>Color:</h5>
                <div class="row g-2">
                    @foreach($product->colors as $color)
                        <div class="col-4 col-md-2">
                            <input type="radio" class="btn-check" name="color" id="color_{{ $color->id }}" autocomplete="off" {{ $loop->first ? 'checked' : '' }}>
                            <label class="btn d-flex align-items-center justify-content-center"
                                   for="color_{{ $color->id }}"
                                   style="background-color: {{ $color->color_code }}; height: 50px; border-radius: 5px;
                                          border: 2px solid black; color: black; font-weight: bold; text-transform: uppercase;
                                          transition: all 0.3s ease-in-out; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);">
                                {{ $color->color }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>


            <div class="mb-4">
                <label for="quantity" class="form-label">Variants Available:</label>
                <div class="d-flex flex-column">
                    @foreach($product->variants as $variant)
                        @php
                            // Assuming the color is stored as a JSON string, decode it
                            $colorData = json_decode($variant->color);
                        @endphp
                        <!-- Display Variant Color and Stock -->
                        <div class="d-flex justify-content-between align-items-center mb-2 border p-2 rounded">
                            <div>
                                <strong>{{ $colorData->color ?? $variant->color }}</strong>
                            </div>
                            <div class="text-muted">
                                Stock: <span class="badge bg-primary">{{ $variant->stock }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>




            <a class="btn btn-dark btn-sm flex-grow-1 me-2"
            href="{{ route('cart.add', $product->id) }}">
            <i class="bi bi-cart-check-fill"></i> Add TO CART
        </a><br><br>
            {{-- <button class="btn btn-outline-secondary btn-lg mb-3">
                    <i class="bi bi-heart"></i> Add to Wishlist
                </button> --}}

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
