<section class="py-5">
    <div class="container px-4 px-lg-5 mt-5">
        <h2 class="text-center fw-bold mb-4">Our Products</h2>

        <!-- Search Bar -->
        <div class="mb-4">
            <form method="GET" action="{{ route('product.page') }}" class="d-flex justify-content-center">
                <input type="text" class="form-control me-2 w-50 shadow-sm" name="query"
                    placeholder="Search for products..." value="{{ request('query') }}">
                <button type="submit" class="btn btn-dark">
                    <i class="bi-search"></i> Search
                </button>
            </form>
        </div>


        <!-- Product Grid -->
        <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
            @foreach ($products as $item)
            <div class="col mb-5">
                <div class="card h-100 shadow-sm border-0">
                    <!-- Product image-->
                    <div class="product-image-container position-relative">
                        <a href="{{route('product.details',$item->slug)}}">
                            <img class="card-img-top" src="{{ url('/public/uploads/', $item->image) }}" alt="{{ $item->name }}" />
                        </a>
                        <div class="position-absolute top-50 start-50 translate-middle">
                            <button class="btn btn-primary btn-sm quick-view-btn rounded-pill px-3 shadow-sm"
                            data-id="{{ $item->id }}"
                            data-name="{{ $item->name }}"
                            data-price="{{ number_format($item->price, 2) }}"
                            data-image="{{ url('/public/uploads/', $item->image) }}"
                            data-description="{{ $item->description }}"
                            title="Quick View"
                            aria-label="Quick view of {{ $item->name }}">
                        <i class="bi bi-eye-fill me-1"></i> Quick View
                    </button>
                        </div>
                    </div>
                    <!-- Product details-->
                    <div class="card-body p-4 text-center">
                        <!-- Product name-->
                        <h5 class="fw-bolder text-truncate" title="{{ $item->name }}">{{ $item->name }}</h5>
                        <!-- Product price-->
                        <p class="text-success fs-5 mb-1">BDT {{ number_format($item->price, 2) }}</p>
                    </div>
                    <!-- Product actions-->
                    <div class="text-center">
                        <!-- Product actions-->
                        <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                            <div class="d-flex justify-content-between">
                                <a class="btn btn-info btn-sm flex-grow-1 me-2" href="{{ route('cart.add', $item->id) }}">
                                    <i class="bi bi-cart-check-fill"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>


    </div>
</section>


{{-- Quick View Modal --}}
<div class="modal fade" id="productQuickViewModal" tabindex="-1" aria-labelledby="quickViewModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="quickViewModalLabel">Product Quick View</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <img id="quickViewImage" src="" alt="" class="img-fluid">
                    </div>
                    <div class="col-md-6">
                        <h4 id="quickViewName"></h4>
                        <h5 class="text-success mb-3">BDT <span id="quickViewPrice"></span></h5>
                        <p id="quickViewDescription"></p>
                        <button class="btn btn-dark mt-3">
                            <i class="fa fa-shopping-cart"></i> Add to Cart
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .product-image-container {
        overflow: hidden;
        position: relative;
    }

    .product-image-container img {
        transition: transform 0.3s ease, opacity 0.3s ease;
        max-height: 250px;
        object-fit: cover;
    }

    .product-image-container:hover img {
        transform: scale(1.1);
        opacity: 0.9;
    }

    .card {
        transition: box-shadow 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .btn-dark {
        background-color: #333;
        border-color: #333;
    }

    .btn-dark:hover {
        background-color: #555;
        border-color: #555;
    }

    .text-truncate {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .form-control {
        border: 1px solid #ccc;
        border-radius: 30px;
        padding: 10px 20px;
    }

    .form-control:focus {
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
        border-color: #333;
    }

    .btn-dark {
        border-radius: 30px;
    }
</style>
@endpush


@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
    const quickViewButtons = document.querySelectorAll('.quick-view-btn');
    const modal = new bootstrap.Modal(document.getElementById('productQuickViewModal'));

    quickViewButtons.forEach(button => {
        button.addEventListener('click', function () {
            const name = this.dataset.name;
            const price = this.dataset.price;
            const image = this.dataset.image;
            const description = this.dataset.description;

            document.getElementById('quickViewName').textContent = name;
            document.getElementById('quickViewPrice').textContent = price;
            document.getElementById('quickViewImage').src = image;
            document.getElementById('quickViewDescription').textContent = description;

            modal.show();
        });
    });
});

</script>
@endpush
