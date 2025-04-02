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

        <!-- Hidden PayPal Form -->
        {{-- <form id="paypal-form" action="{{ route('processPaypalPayment') }}" method="POST" style="display: none;">
            @csrf
            <input type="hidden" name="name" id="paypal-name">
            <input type="hidden" name="email" id="paypal-email">
            <input type="hidden" name="street" id="paypal-street">
            <input type="hidden" name="zipcode" id="paypal-zipcode">
            <input type="hidden" name="phone" id="paypal-phone">
            <input type="hidden" name="note" id="paypal-note">
            <input type="hidden" name="payment_method" value="paypal">
        </form> --}}

    </div>
</div>

@endsection
