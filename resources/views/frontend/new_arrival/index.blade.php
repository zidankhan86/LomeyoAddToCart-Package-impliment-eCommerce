@extends('frontend.layout.app')

@push('styles')
<style>
    .product-image {
        height: 230px;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .product-image:hover {
        transform: scale(1.05);
    }

    .card:hover {
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
    }

    .btn-outline-dark:hover {
        background-color: #333;
        color: white;
    }

    .badge.bg-danger {
        font-size: 0.75rem;
        padding: 5px 10px;
        border-radius: 20px;
    }
</style>
@endpush

@section('content')

<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center fw-bold mb-5" data-aos="fade-down">🔥 New Arrival Products</h2>

        <!-- Product Grid -->
        <div class="row g-4 justify-content-center" data-aos="fade-up">
            @foreach ($latestProducts as $product)
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="card border-0 shadow-sm position-relative h-100">
                        <!-- New Badge -->
                        <span class="badge bg-danger position-absolute top-0 start-0 m-2">NEW</span>

                        <!-- Product Image -->
                        <a href="{{ route('product.details', $product->slug) }}">
                            <img src="{{ url('/public/uploads/', $product->image) }}" class="card-img-top product-image" alt="{{ $product->name }}">
                        </a>

                        <!-- Product Body -->
                        <div class="card-body text-center">
                            <h5 class="card-title text-truncate">{{ $product->name }}</h5>
                            <p class="text-success fw-semibold fs-5">BDT {{ number_format($product->price, 2) }}</p>
                            <button
                                class="btn btn-outline-dark btn-sm rounded-pill quick-view-btn"
                                data-id="{{ $product->id }}"
                                data-name="{{ $product->name }}"
                                data-price="{{ number_format($product->price, 2) }}"
                                data-image="{{ url('/public/uploads/', $product->image) }}"
                                data-description="{{ $product->description }}">
                                <i class="bi bi-eye-fill me-1"></i> Quick View
                            </button>
                        </div>

                        <!-- Product Action -->
                        <div class="card-footer bg-transparent border-0 text-center pb-3">
                            <a href="{{ route('cart.add', $product->id) }}" class="btn btn-dark rounded-pill w-100">
                                <i class="bi bi-cart-check-fill me-1"></i> Add to Cart
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Quick View Modal -->
<div class="modal fade" id="productQuickViewModal" tabindex="-1" aria-labelledby="quickViewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="quickViewModalLabel">Product Quick View</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <div class="row g-4 align-items-center">
                    <div class="col-md-6">
                        <img id="quickViewImage" src="" alt="" class="img-fluid rounded w-100">
                    </div>
                    <div class="col-md-6">
                        <h4 id="quickViewName" class="fw-bold mb-2"></h4>
                        <h5 class="text-success mb-3">BDT <span id="quickViewPrice"></span></h5>
                        <p id="quickViewDescription" class="mb-3 text-muted"></p>
                        <a href="#" class="btn btn-dark rounded-pill disabled">
                            <i class="bi bi-cart"></i> Add to Cart (Coming Soon)
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const quickViewButtons = document.querySelectorAll('.quick-view-btn');
        const modalElement = document.getElementById('productQuickViewModal');

        if (modalElement) {
            const modal = new bootstrap.Modal(modalElement);
            
            quickViewButtons.forEach(button => {
                button.addEventListener('click', function () {
                    document.getElementById('quickViewName').textContent = this.dataset.name;
                    document.getElementById('quickViewPrice').textContent = this.dataset.price;
                    document.getElementById('quickViewImage').src = this.dataset.image;
                    document.getElementById('quickViewDescription').textContent = this.dataset.description;
                    modal.show();
                });
            });
        }
    });
</script>
@endpush
