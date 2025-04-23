@push('styles')
<style>
    body {
        font-family: Arial, sans-serif;
    }

    .categories-section {
        padding: 20px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .categories-section h2 {
        font-size: 24px;
        margin-bottom: 20px;
    }

    .categories-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
    }

    .category-card {
        width: 120px;
        text-align: center;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 10px;
        box-sizing: border-box;
    }

    .category-card img {
        width: 80px;
        height: 80px;
        object-fit: contain;
    }

    .category-card p {
        margin: 10px 0 0;
        font-size: 14px;
        color: #e63946;
    }
</style>


<style>
    body {
        font-family: Arial, sans-serif;
    }

    .categories-section {
        padding: 20px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .categories-section h2 {
        font-size: 12px;
        margin-bottom: 10px;
    }

    .categories-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        justify-content: space-between;
    }

    .category-card {
        width: 16%;
        /* Ensures that 6 items fit side by side */
        text-align: center;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 10px;
        box-sizing: border-box;
        transition: transform 0.3s ease;
    }

    .category-card:hover {
        transform: scale(1.05);
    }

    .category-card i {
        font-size: 1.4rem;
        /* Smaller icon size */
        margin-bottom: 6px;
    }

    .category-card p {
        margin-top: 6px;
        font-size: 12px;
        color: #e63946;
    }

    /* Responsive grid */
    @media (max-width: 300px) {
        .category-card {
            width: 38%;
            /* Stack the cards on small screens */
        }
    }

    @media (max-width: 350px) {
        .category-card {
            width: 30%;
            /* Adjust to 3 cards per row on medium screens */
        }
    }
</style>


@endpush


<div class="mb-5 text-center">
    <section class="categories-section">
        <h1 class="text-center mb-4">Popular Categories</h1>
        <div class="categories-grid">
            @forelse ($categories as $category)
            <div class="category-card">
                <a href="{{ route('home', ['category' => $category->slug]) }}" class="text-decoration-none">
                    <i class="{{ $category->icon }} fs-1 text-dark"></i>
                    <p class="mt-2" style="color: black">{{ Str::limit($category->name, 15) }}</p>
                </a>

            </div>
            @empty
            <h3 class="text-center">No Category Found</h3>
            @endforelse
        </div>
    </section>
</div>

<!-- Ad Banner -->
<div class="mb-5 text-center">
    <a href="{{route('banner.click')}}">
        <img src="{{ asset('image.png') }}" alt="Ad Banner" class="img-fluid rounded shadow-sm"
            style="max-height: 300px;">
    </a>
</div>
<section class="py-5">
    <div class="container px-4 px-lg-5 mt-2">
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
        <div class="row gx-4 gx-lg-5 mt-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
            @forelse ($products as $item)
            <div class="col mb-5">
                <div class="card h-100 shadow-sm border-0">
                    <!-- Product image-->
                    <div class="product-image-container position-relative">
                        <a href="{{route('product.details',$item->slug)}}">
                            <img class="card-img-top" src="{{ url('/public/uploads/', $item->image) }}"
                                alt="{{ $item->name }}" />
                        </a>
                        <div class="position-absolute top-50 start-50 translate-middle">
                            <button class="btn btn-dark btn-sm quick-view-btn rounded-pill px-3 shadow-sm"
                                data-id="{{ $item->id }}" data-name="{{ $item->name }}"
                                data-price="{{ number_format($item->price, 2) }}"
                                data-image="{{ url('/public/uploads/', $item->image) }}"
                                data-description="{{ $item->description }}" title="Quick View"
                                aria-label="Quick view of {{ $item->name }}"
                                style="display: inline-flex; align-items: center; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
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
                                <a class="btn btn-dark btn-sm flex-grow-1 me-2"
                                    href="{{ route('cart.add', $item->id) }}">
                                    <i class="bi bi-cart-check-fill"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <h3 class="text-center text-danger">No Data Found</h3>
            @endforelse
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
