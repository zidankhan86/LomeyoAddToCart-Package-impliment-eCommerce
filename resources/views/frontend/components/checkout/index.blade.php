@extends('frontend.layout.app')

@section('content')

<div class="container">
    <div class="container padding-bottom-3x mb-1"><br><br>
        <h1 style="text-align: center">Checkout</h1>

        @if (session('success'))
            <div class="alert alert-info alert-dismissible fade show text-center" style="margin-bottom: 30px;">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('checkout.process') }}" method="POST">
            @csrf

            <div class="row">
                <!-- Billing Details -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Billing Details</h4>

                            <div class="mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Street Address</label>
                                <input type="text" name="street" class="form-control" value="{{ old('street') }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Zip Code</label>
                                <input type="text" name="zipcode" class="form-control" value="{{ old('zipcode') }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Country</label>
                                        <input type="text" name="country_id" class="form-control" value="{{ old('country_id') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">State</label>
                                        <input type="text" name="state_id" class="form-control" value="{{ old('state_id') }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">City</label>
                                <input type="text" name="city" class="form-control" value="{{ old('city') }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Additional Note</label>
                                <textarea name="note" class="form-control" rows="3">{{ old('note') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Order Summary</h4>

                            <ul class="list-group mb-3">
                                @foreach($cartContents as $item)
                                    <li class="list-group-item d-flex justify-content-between">
                                        <div>
                                            <h6 class="my-0">{{ $item->name }}</h6>
                                            <small class="text-muted">Quantity: {{ $item->quantity }}</small>
                                        </div>
                                        <span class="text-muted">BDT {{ number_format($item->price * $item->quantity, 2) }}</span>
                                    </li>
                                @endforeach
                                <li class="list-group-item d-flex justify-content-between">
                                    <strong>Total</strong>
                                    <strong>BDT {{ number_format($totalPrice, 2) }}</strong>
                                </li>
                            </ul>

                         <!-- Payment Method -->
                            <h5>Payment Method</h5>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="paypal" value="paypal" checked>
                                    <label class="form-check-label" for="paypal">
                                        <img src="https://www.paypalobjects.com/webstatic/mktg/Logo/pp-logo-100px.png" alt="PayPal Logo" style="height: 20px;">
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="stripe" value="stripe">
                                    <label class="form-check-label" for="stripe">
                                        <img src="https://stripe.com/img/v3/home/twitter.png" alt="Stripe Logo" style="height: 20px;">
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="sslcommerz" value="sslcommerze">
                                    <label class="form-check-label" for="sslcommerz">
                                        <img src="data:image/png;base64,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" alt="SSLCommerz Logo" style="height: 20px;">
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod">
                                    <label class="form-check-label" for="cod">
                                        Cash on Delivery
                                    </label>
                                </div>
                            </div>

                            @if($errors->any())
                                <div class="alert alert-danger">
                                    {{ $errors->first() }}
                                </div>
                            @endif

                            <button type="submit" class="btn btn-success w-100">Place Order</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>



    </div>
</div>

@endsection
