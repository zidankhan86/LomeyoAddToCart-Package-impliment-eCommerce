<div class="container">

<div class="container padding-bottom-3x mb-1"><br><br>
    <h1 style="text-align: center">Cart Product</h1>
    @if (session('success'))
    <div class="alert alert-info alert-dismissible fade show text-center" style="margin-bottom: 30px;">
        {{ session('success') }}
    </div>
    @endif

    <div class="table-responsive shopping-cart">
        <table class="table">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th class="text-center">Quantity</th>
                    <th class="text-center">Subtotal</th>
                    <th class="text-center">Discount</th>
                    <th class="text-center">
                        <form action="{{ route('cart.clear') }}">

                            <button class="btn btn-sm btn-outline-danger" type="submit">Clear Cart</button>
                        </form>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($cartContents as $item)
                <tr>
                    <td>
                        <div class="product-item">
                            {{-- <a class="product-thumb" href="#"><img src="{{ asset('public/uploads/' . $item->image) }}" alt="Product"></a> --}}

                            <div class="product-info">
                                <h4 class="product-title"><a href="#">{{ $item->name }}</a></h4>
                                {{-- <span><em>Size:</em> {{ $item->attributes->size }}</span>
                                <span><em>Color:</em> {{ $item->attributes->color }}</span> --}}
                            </div>
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="count-input">
                            <form action="#" method="POST">
                                @csrf
                                @method('PATCH')
                                <select class="form-control" name="quantity" onchange="this.form.submit()">
                                    @for ($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}" @if($i == $item->quantity) selected @endif>{{ $i }}</option>
                                    @endfor
                                </select>
                            </form>
                        </div>
                    </td>
                    <td class="text-center text-lg text-medium">BDT {{ number_format($item->price * $item->quantity, 2) }}</td>
                    <td class="text-center">BDT {{ number_format($item->discount, 2) }}</td>
                    <td class="text-center">
                        <form action="{{ route('cart.remove', ['product' => $item->id]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <a class="btn btn-danger" href="{{ route('cart.remove',$item->id) }}" data-toggle="tooltip" title="Remove item">
                                Remove item
                            </a>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="shopping-cart-footer">
        <div class="column">
            <form class="coupon-form" method="post">
                <input class="form-control form-control-sm" type="text" placeholder="Coupon code" required="">
                <button class="btn btn-outline-primary btn-sm" type="submit">Apply Coupon</button>
            </form>
        </div>
        <div class="column text-lg">Subtotal: <span class="text-medium"> BDT {{ number_format($totalPrice, 2) }}</span></div>
    </div>
    <div class="shopping-cart-footer">
        <div class="column"><a class="btn btn-outline-secondary" href="{{ route('home') }}"><i class="icon-arrow-left"></i>&nbsp;Back to Shopping</a></div>
        <div class="column">
            <a class="btn btn-primary" href="#" data-toast="" data-toast-type="success"
                data-toast-position="topRight" data-toast-icon="icon-circle-check" data-toast-title="Your cart"
                data-toast-message="is updated successfully!">Update Cart</a>
            <a class="btn btn-success" href="{{route('checkout')}}">Checkout</a>
        </div>
    </div>
</div>

</div>
<style>
    body{margin-top:20px;}
select.form-control:not([size]):not([multiple]) {
    height: 44px;
}
select.form-control {
    padding-right: 38px;
    background-position: center right 17px;
    background-image: url(data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNv…9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8Zz4KPC9nPgo8L3N2Zz4K);
    background-repeat: no-repeat;
    background-size: 9px 9px;
}
.form-control:not(textarea) {
    height: 44px;
}
.form-control {
    padding: 0 18px 3px;
    border: 1px solid #dbe2e8;
    border-radius: 22px;
    background-color: #fff;
    color: #606975;
    font-family: "Maven Pro",Helvetica,Arial,sans-serif;
    font-size: 14px;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
}

.shopping-cart,
.wishlist-table,
.order-table {
    margin-bottom: 20px
}

.shopping-cart .table,
.wishlist-table .table,
.order-table .table {
    margin-bottom: 0
}

.shopping-cart .btn,
.wishlist-table .btn,
.order-table .btn {
    margin: 0
}

.shopping-cart>table>thead>tr>th,
.shopping-cart>table>thead>tr>td,
.shopping-cart>table>tbody>tr>th,
.shopping-cart>table>tbody>tr>td,
.wishlist-table>table>thead>tr>th,
.wishlist-table>table>thead>tr>td,
.wishlist-table>table>tbody>tr>th,
.wishlist-table>table>tbody>tr>td,
.order-table>table>thead>tr>th,
.order-table>table>thead>tr>td,
.order-table>table>tbody>tr>th,
.order-table>table>tbody>tr>td {
    vertical-align: middle !important
}

.shopping-cart>table thead th,
.wishlist-table>table thead th,
.order-table>table thead th {
    padding-top: 17px;
    padding-bottom: 17px;
    border-width: 1px
}

.shopping-cart .remove-from-cart,
.wishlist-table .remove-from-cart,
.order-table .remove-from-cart {
    display: inline-block;
    color: #ff5252;
    font-size: 18px;
    line-height: 1;
    text-decoration: none
}

.shopping-cart .count-input,
.wishlist-table .count-input,
.order-table .count-input {
    display: inline-block;
    width: 100%;
    width: 86px
}

.shopping-cart .product-item,
.wishlist-table .product-item,
.order-table .product-item {
    display: table;
    width: 100%;
    min-width: 150px;
    margin-top: 5px;
    margin-bottom: 3px
}

.shopping-cart .product-item .product-thumb,
.shopping-cart .product-item .product-info,
.wishlist-table .product-item .product-thumb,
.wishlist-table .product-item .product-info,
.order-table .product-item .product-thumb,
.order-table .product-item .product-info {
    display: table-cell;
    vertical-align: top
}

.shopping-cart .product-item .product-thumb,
.wishlist-table .product-item .product-thumb,
.order-table .product-item .product-thumb {
    width: 130px;
    padding-right: 20px
}

.shopping-cart .product-item .product-thumb>img,
.wishlist-table .product-item .product-thumb>img,
.order-table .product-item .product-thumb>img {
    display: block;
    width: 100%
}

@media screen and (max-width: 860px) {
    .shopping-cart .product-item .product-thumb,
    .wishlist-table .product-item .product-thumb,
    .order-table .product-item .product-thumb {
        display: none
    }
}

.shopping-cart .product-item .product-info span,
.wishlist-table .product-item .product-info span,
.order-table .product-item .product-info span {
    display: block;
    font-size: 13px
}

.shopping-cart .product-item .product-info span>em,
.wishlist-table .product-item .product-info span>em,
.order-table .product-item .product-info span>em {
    font-weight: 500;
    font-style: normal
}

.shopping-cart .product-item .product-title,
.wishlist-table .product-item .product-title,
.order-table .product-item .product-title {
    margin-bottom: 6px;
    padding-top: 5px;
    font-size: 16px;
    font-weight: 500
}

.shopping-cart .product-item .product-title>a,
.wishlist-table .product-item .product-title>a,
.order-table .product-item .product-title>a {
    transition: color .3s;
    color: #374250;
    line-height: 1.5;
    text-decoration: none
}

.shopping-cart .product-item .product-title>a:hover,
.wishlist-table .product-item .product-title>a:hover,
.order-table .product-item .product-title>a:hover {
    color: #0da9ef
}

.shopping-cart .product-item .product-title small,
.wishlist-table .product-item .product-title small,
.order-table .product-item .product-title small {
    display: inline;
    margin-left: 6px;
    font-weight: 500
}

.wishlist-table .product-item .product-thumb {
    display: table-cell !important
}

@media screen and (max-width: 576px) {
    .wishlist-table .product-item .product-thumb {
        display: none !important
    }
}

.shopping-cart-footer {
    display: table;
    width: 100%;
    padding: 10px 0;
    border-top: 1px solid #e1e7ec
}

.shopping-cart-footer>.column {
    display: table-cell;
    padding: 5px 0;
    vertical-align: middle
}

.shopping-cart-footer>.column:last-child {
    text-align: right
}

.shopping-cart-footer>.column:last-child .btn {
    margin-right: 0;
    margin-left: 15px
}

@media (max-width: 768px) {
    .shopping-cart-footer>.column {
        display: block;
        width: 100%
    }
    .shopping-cart-footer>.column:last-child {
        text-align: center
    }
    .shopping-cart-footer>.column .btn {
        width: 100%;
        margin: 12px 0 !important
    }
}

.coupon-form .form-control {
    display: inline-block;
    width: 100%;
    max-width: 235px;
    margin-right: 12px;
}

.form-control-sm:not(textarea) {
    height: 36px;
}


</style>
