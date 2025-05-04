<nav class="navbar navbar-expand-lg sticky-top"
    style="background: linear-gradient(90deg, #c42f81, #feb47b); box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
    <div class="container px-4 px-lg-5">
        <!-- Logo with Link -->
        <a class="navbar-brand text-white fw-bold d-flex align-items-center" href="{{ route('home') }}">
            <img src="{{ asset('l.png') }}" style="height: 40px;" class="me-2">

        </a>

        <!-- Toggle for mobile -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon text-white"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Nav Links -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                <li class="nav-item">
                    <a class="nav-link text-white fw-semibold {{ request()->routeIs('home') ? 'active' : '' }}"
                        href="{{ route('home') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white fw-semibold {{ request()->routeIs('about.page') ? 'active' : '' }}"
                        href="{{ route('about.page') }}">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white fw-semibold {{ request()->routeIs('product.page') ? 'active' : '' }}"
                        href="{{ route('product.page') }}">Product</a>
                </li>

                <!-- Shop Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white fw-semibold" id="navbarDropdown" href="#"
                        role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Shop
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 p-2">
                        <li>
                            <a class="dropdown-item d-flex align-items-center py-2" href="{{ route('product.page') }}">
                                <i class="bi bi-bag me-2 text-primary"></i> All Products
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center py-2" href="{{route('popularProduct')}}">
                                <i class="bi bi-star-fill me-2 text-warning"></i> Popular Items
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center py-2" href="{{ route('latestProduct') }}">
                                <i class="bi bi-lightning-fill me-2 text-danger"></i> New Arrivals
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>

            <!-- Cart & Login Buttons -->
            <div class="d-flex align-items-center gap-2 ms-lg-3 mt-3 mt-lg-0">
                <!-- Cart Button -->
                <a href="{{ route('cart.show') }}" class="btn btn-light text-dark position-relative rounded-pill px-3">
                    <i class="bi bi-cart-fill me-1"></i> Cart
                    <span
                        class="badge bg-dark text-white position-absolute top-0 start-100 translate-middle rounded-pill px-2 py-1">
                        @auth
                        {{ Cart::session(auth()->user()->id)->getTotalQuantity() }}
                        @else
                        0
                        @endauth
                    </span>
                </a>



                <!-- Login Button -->
                @guest
                <a href="{{ route('login') }}" class="btn btn-white text-dark rounded-pill px-3 me-2"
                    style="background-color: #ffffff; border: 1px solid #fff;">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Login
                </a>
                @endguest

                 @auth

<p></p>
                {{-- <strong class="text-success">Active as {{auth()->user()->name}}</strong> --}}

                <a href="{{route('user.logout')}}" class="btn btn-danger" title="Logout">
                    <i class="fas fa-sign-out-alt"></i> <strong>Logout</strong>
                </a>

                @endauth

            </div>


        </div>
    </div>
</nav>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
