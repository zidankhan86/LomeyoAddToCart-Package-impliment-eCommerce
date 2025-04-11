@extends('frontend.layout.app')
<!-- Custom CSS for better visual appeal (inline) -->
<style>
    .payment-option:hover {
        transform: scale(1.05);
    }

    .payment-option input[type="radio"]:checked+.payment-label {
        background-color: #28a745;
        color: white;
        border-color: #28a745;
    }

    .payment-option input[type="radio"]:checked+.payment-label .payment-text {
        font-weight: bold;
    }

    .payment-option:hover .payment-label {
        background-color: #e2e6ea;
    }
</style>

@section('content')

<div class="container py-5">
    <div class="row">
        <div class="col-12 text-center mb-5">
            <h1 class="display-4">Checkout</h1>
            @if (session('success'))
            <div class="alert alert-info alert-dismissible fade show text-center" style="margin-bottom: 30px;">
                {{ session('success') }}
            </div>
            @endif
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title mb-4">Billing Details</h4>

                    <form action="{{ route('checkout.process') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                                        required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Street Address</label>
                            <input type="text" name="street" class="form-control" value="{{ old('street') }}" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Zip Code</label>
                                    <input type="text" name="zipcode" class="form-control" value="{{ old('zipcode') }}"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Phone</label>
                                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}"
                                        required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Country</label>
                                    <input type="text" name="country_id" class="form-control"
                                        value="{{ old('country_id') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">State</label>
                                    <input type="text" name="state_id" class="form-control"
                                        value="{{ old('state_id') }}" required>
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
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title mb-4">Order Summary</h4>

                    <ul class="list-group mb-4">
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
                    <h5 class="mb-3" style="font-size: 1.25rem; font-weight: 600; color: #333;">Payment Method</h5>
                    <div class="mb-3">
                        <!-- PayPal Option -->
                        <div class="payment-option d-flex align-items-center"
                            style="margin-bottom: 15px; cursor: pointer; transition: transform 0.3s ease;">
                            <input class="form-check-input" type="radio" name="payment_method" id="paypal"
                                value="paypal" checked style="display: none;">
                            <label class="payment-label d-flex align-items-center" for="paypal"
                                style="padding: 12px 20px; border: 2px solid #ddd; border-radius: 10px; background-color: #f8f9fa; width: 100%; display: flex; align-items: center; justify-content: start; transition: background-color 0.3s ease;">
                                <img src="https://www.paypalobjects.com/webstatic/mktg/Logo/pp-logo-100px.png"
                                    alt="PayPal Logo" class="payment-icon" style="height: 30px; margin-right: 10px;">
                                <span class="payment-text" style="font-size: 16px; font-weight: 500; color: #333;">Pay
                                    with PayPal</span>
                            </label>
                        </div>

                        <!-- Stripe Option -->
                        <div class="payment-option d-flex align-items-center"
                            style="margin-bottom: 15px; cursor: pointer; transition: transform 0.3s ease;">
                            <input class="form-check-input" type="radio" name="payment_method" id="stripe"
                                value="stripe" style="display: none;">
                            <label class="payment-label d-flex align-items-center" for="stripe"
                                style="padding: 12px 20px; border: 2px solid #ddd; border-radius: 10px; background-color: #f8f9fa; width: 100%; display: flex; align-items: center; justify-content: start; transition: background-color 0.3s ease;">
                                <img src="https://stripe.com/img/v3/home/twitter.png" alt="Stripe Logo"
                                    class="payment-icon" style="height: 30px; margin-right: 10px;">
                                <span class="payment-text" style="font-size: 16px; font-weight: 500; color: #333;">Pay
                                    with Stripe</span>
                            </label>
                        </div>

                        <!-- SSLCommerz Option -->
                        <div class="payment-option d-flex align-items-center"
                            style="margin-bottom: 15px; cursor: pointer; transition: transform 0.3s ease;">
                            <input class="form-check-input" type="radio" name="payment_method" id="sslcommerz"
                                value="sslcommerze" style="display: none;">
                            <label class="payment-label d-flex align-items-center" for="sslcommerz"
                                style="padding: 12px 20px; border: 2px solid #ddd; border-radius: 10px; background-color: #f8f9fa; width: 100%; display: flex; align-items: center; justify-content: start; transition: background-color 0.3s ease;">
                                <img src="{{asset('ssl.png')}}" alt="SSLCommerz Logo" class="payment-icon"
                                    style="height: 30px; margin-right: 10px;">
                                <span class="payment-text" style="font-size: 16px; font-weight: 500; color: #333;">Pay
                                    with SSLCommerz</span>
                            </label>
                        </div>


                        <!-- COD Option -->
                        <div class="payment-option d-flex align-items-center"
                            style="margin-bottom: 15px; cursor: pointer; transition: transform 0.3s ease;">
                            <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod"
                                style="display: none;">
                            <label class="payment-label d-flex align-items-center" for="cod"
                                style="padding: 12px 20px; border: 2px solid #ddd; border-radius: 10px; background-color: #f8f9fa; width: 100%; display: flex; align-items: center; justify-content: start; transition: background-color 0.3s ease;">
                                <span class="payment-text" style="font-size: 16px; font-weight: 500; color: #333;">Cash
                                    on Delivery</span>
                            </label>
                        </div>
                    </div>







                    @if($errors->any())
                    <div class="alert alert-danger mb-4">
                        {{ $errors->first() }}
                    </div>
                    @endif

                    <button type="submit" class="btn btn-success w-100">Place Order</button>
                </div>

                </form>
            </div>
        </div>
    </div>
</div>

@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const paymentOptions = document.querySelectorAll('.payment-option');

        paymentOptions.forEach(option => {
            option.addEventListener('click', () => {
                // Remove active styles from all
                paymentOptions.forEach(opt => {
                    opt.querySelector('.payment-label').style.borderColor = '#ddd';
                    opt.querySelector('.payment-label').style.backgroundColor = '#f8f9fa';
                });

                // Add active style to the clicked one
                const label = option.querySelector('.payment-label');
                label.style.borderColor = '#007bff';
                label.style.backgroundColor = '#e2edff';

                // Set the radio button as checked
                const radio = option.querySelector('input[type="radio"]');
                radio.checked = true;
            });
        });
    });
</script>
