<nav class="navbar navbar-expand-lg" style="background: linear-gradient(90deg, #c42f81, #feb47b); box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
    <div class="container px-4 px-lg-5">
        <!-- Logo with Link -->
        <a class="navbar-brand text-white fw-bold" href="{{ route('home') }}">
            <img src="{{ asset('l.png') }}" alt="{{ config('app.name') }}" style="height: 40px;">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Nav Links -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                <li class="nav-item"><a class="nav-link text-white fw-semibold" href="{{ route('home') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link text-white fw-semibold" href="{{ route('about.page') }}">About</a></li>
                <li class="nav-item"><a class="nav-link text-white fw-semibold" href="{{ route('product.page') }}">Product</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white fw-semibold" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Shop</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="">All Products</a></li>
                        <li><hr class="dropdown-divider" /></li>
                        <li><a class="dropdown-item" href="#!">Popular Items</a></li>
                        <li><a class="dropdown-item" href="#!">New Arrivals</a></li>
                    </ul>
                </li>
            </ul>

            <!-- Cart Button -->
            <div class="d-flex">
                <a href="{{ route('cart.show') }}" class="btn btn-light text-dark position-relative">
                    <i class="bi bi-cart-fill me-1"></i> Cart
                    <span class="badge bg-dark text-white ms-1 rounded-pill position-absolute top-0 start-100 translate-middle">
                        @auth
                            {{ Cart::session(auth()->user()->id)->getTotalQuantity() }}
                        @else
                            0
                        @endauth
                    </span>
                </a>
            </div>
        </div>
    </div>
</nav>
