<?php

namespace App\Http\Controllers\frontend;
use App\Models\Order;
use App\Models\OrderItem;
use Darryldecode\Cart\Cart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Library\SslCommerz\SslCommerzNotification;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class OrderController extends Controller
{
    public function checkout() {

        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Please login to proceed to checkout.');
        }

        $userId = $user->id;
        $cartContents = \Cart::session($userId)->getContent();
        $totalPrice = 0;

        foreach ($cartContents as $item) {
            $totalPrice += $item->price * $item->quantity;
        }

        $data = [
            'cartContents' => $cartContents,
            'totalPrice' => $totalPrice,
            'subTotal' => \Cart::session($userId)->getSubTotal(),
            'total' => \Cart::session($userId)->getTotal(),
        ];

        return view('frontend.components.checkout.index', $data);
    }

    public function processOrder(Request $request)
    {
        // Validate input data
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|max:255',
            'street'         => 'required|string|max:255',
            'zipcode'        => 'required|string|max:20',
            'phone'          => 'required|string|max:20',
            'note'           => 'nullable|string|max:500',
            'payment_method' => 'required',
        ]);
// dd($request->all());
        $userId = auth()->id();
        $cartContents = \Cart::session($userId)->getContent();
        $subtotal = \Cart::session($userId)->getSubTotal();
        $totalPrice = \Cart::session($userId)->getTotal();

        if ($request->payment_method === 'stripe') {
            // Store order data in session before Stripe redirect
            session()->put('order_data', [
                'user_id' => $userId,
                'billing' => $request->except('_token'),
                'cart_contents' => $cartContents,
                'total_price' => $totalPrice
            ]);

            return $this->processStripePayment($totalPrice, $request->email);
        }

        if ($request->payment_method === 'paypal') {
            // Store order data in session before PayPal redirect
            session()->put('order_data', [
                'user_id' => $userId,
                'billing' => $request->except('_token'),
                'cart_contents' => $cartContents,
                'total_price' => $totalPrice
            ]);

            // Remove $request parameter since we only need the amount
            return $this->processPaypalPayment($totalPrice);
        }


        if ($request->payment_method === 'sslcommerze') {
            return $this->processSslPayment($request, $totalPrice);
        }



        if ($request->payment_method === 'cod') {
            $order = $this->checkoutProcessCOD($request, $totalPrice);
            \Cart::session($userId)->clear(); // Clear cart after successful order
            return redirect()->route('home')->with('success', 'Your order has been placed successfully!');
        }

        return redirect()->back()->with('error', 'Invalid payment method selected.');
    }


    private function checkoutProcessCOD($request, $subtotal)
    {
        // Validate input
        $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|max:255',
            'street'         => 'required|string|max:255',
            'zipcode'        => 'required|string|max:20',
            'phone'          => 'required|string|max:20',
            'note'           => 'nullable|string|max:500',
            'payment_method' => 'required'
        ]);

        $userId = auth()->id();
        $totalPrice = \Cart::session($userId)->getTotal();
        $cartContents = \Cart::session($userId)->getContent();
        // Create Order
        $order = Order::create([
            'user_id'        => $userId,
            'name'           => $request->name,
            'email'          => $request->email,
            'street'         => $request->street,
            'zipcode'        => $request->zipcode,
            'phone'          => $request->phone,
            'note'           => $request->note,
            'payment_method' => $request->payment_method,
            'total_price'    => $totalPrice,
            'status'         => 'pending',
        ]);

        foreach ($cartContents as $item) {
            OrderItem::create([
                'order_id'      => $order->id,
                'product_id'    => $item->id, // Product ID from cart
                'product_name'  => $item->name,
                'product_price' => $item->price,
                'quantity'      => $item->quantity,
                'attributes'    => json_encode($item->attributes)
            ]);
        }
        // Clear Cart after successful order
        \Cart::session($userId)->clear();

        return redirect()->route('home')->with('success', 'Your order has been placed successfully!');
    }

    public function processPaypalPayment($amount)
    {
        try {
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();

            $response = $provider->createOrder([
                "intent" => "CAPTURE",
                "application_context" => [
                    "return_url" => route('paypal.success'),
                    "cancel_url" => route('paypal.cancel'),
                    "brand_name" => config('app.name'),
                    "user_action" => "PAY_NOW",
                ],
                "purchase_units" => [[
                    "amount" => [
                        "currency_code" => config('paypal.currency'),
                        "value" => number_format((float)$amount, 2, '.', '')
                    ]
                ]]
            ]);

            if (isset($response['id']) && $response['id'] != null) {
                foreach ($response['links'] as $links) {
                    if ($links['rel'] === 'approve') {
                        return redirect()->away($links['href']);
                    }
                }
            }

            return redirect()
                ->route('checkout')
                ->with('error', $response['message'] ?? 'Something went wrong with PayPal.');

        } catch (\Exception $e) {
            return redirect()
                ->route('checkout')
                ->with('error', 'PayPal error: '.$e->getMessage());
        }
    }


    public function paypalSuccess(Request $request)
    {
        try {
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();

            $response = $provider->capturePaymentOrder($request->token);

            if (isset($response['status']) && $response['status'] === 'COMPLETED') {
                $orderData = session()->get('order_data');
                // dd($orderData);
                if (!$orderData) {
                    throw new \Exception('Session data not found.');
                }

                $order = Order::create([
                    'user_id'        => $orderData['user_id'],
                    'name'           => $orderData['billing']['name'],
                    'email'          => $orderData['billing']['email'],
                    'street'         => $orderData['billing']['street'],
                    'zipcode'        => $orderData['billing']['zipcode'],
                    'phone'          => $orderData['billing']['phone'],
                    'note'           => $orderData['billing']['note'] ?? null,
                    'payment_method' => 'paypal',
                    'total_price'    => $orderData['total_price'],
                    'status'         => 'completed',
                    'transaction_id' => $response['id']
                ]);
                // dd($orderData);
                foreach ($orderData['cart_contents'] as $item) {
                    OrderItem::create([
                        'order_id'      => $order->id,
                        'product_id'    => $item->id,
                        'product_name'  => $item->name,
                        'product_price' => $item->price,
                        'quantity'      => $item->quantity,
                        'attributes'    => json_encode($item->attributes)
                    ]);
                }

                \Cart::session($orderData['user_id'])->clear();
                session()->forget('order_data');

                return redirect()
                    ->route('home')
                    ->with('success', 'Payment successful! Order #'.$order->id.' has been placed.');
            }

            throw new \Exception('Payment not completed: '.($response['message'] ?? 'Unknown error'));

        } catch (\Exception $e) {
            return redirect()
                ->route('checkout')
                ->with('error', 'Order processing failed: '.$e->getMessage());
        }
    }

    public function paypalCancel()
    {
        return redirect()
            ->route('checkout')
            ->with('error', 'You have cancelled the PayPal payment.');
    }



    private function processStripePayment($totalPrice, $customerEmail)
{
    \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

    try {
        $userId = auth()->id();
        $cartContents = \Cart::session($userId)->getContent();

        // Convert cart contents to array to avoid Closure serialization issue
        $cartArray = $cartContents->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'price' => $item->price,
                'quantity' => $item->quantity,
                'attributes' => $item->attributes,
            ];
        })->values()->toArray();

        // Store order data in session
        $orderData = [
            'user_id' => $userId,
            'billing' => [
                'name' => request('name'),
                'email' => $customerEmail,
                'street' => request('street'),
                'zipcode' => request('zipcode'),
                'phone' => request('phone'),
                'note' => request('note'),
            ],
            'total_price' => $totalPrice,
            'cart_contents' => $cartArray,
        ];

        session()->put('order_data', $orderData);

        // Create Stripe Checkout Session
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $this->getLineItems($cartContents),
            'mode' => 'payment',
            'customer_email' => $customerEmail,
            'success_url' => route('stripe.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('stripe.cancel'),
        ]);

        return redirect($session->url);
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Stripe Error: ' . $e->getMessage());
    }
}

    private function getLineItems($cartContents)
    {
        return $cartContents->map(function ($item) {
            return [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $item->name,
                    ],
                    'unit_amount' => intval($item->price * 100),
                ],
                'quantity' => $item->quantity,
            ];
        })->values()->toArray();
    }


    public function StripeSuccess(Request $request)
    {
        if (!$request->session_id) {
            return redirect()->route('stripe.cancel');
        }

        try {
            $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
            $session = $stripe->checkout->sessions->retrieve($request->session_id);

            $orderData = session()->get('order_data');

            if (!$orderData) {
                return redirect()->route('checkout')->with('error', 'Session expired or missing order data.');
            }

            $order = Order::create([
                'user_id' => $orderData['user_id'],
                'name' => $orderData['billing']['name'],
                'email' => $orderData['billing']['email'],
                'street' => $orderData['billing']['street'],
                'zipcode' => $orderData['billing']['zipcode'],
                'phone' => $orderData['billing']['phone'],
                'note' => $orderData['billing']['note'],
                'payment_method' => 'stripe',
                'total_price' => $orderData['total_price'],
                'status' => 'paid',
                'currency' => 'usd',
                'transaction_id' => $session->payment_intent,
            ]);

            foreach ($orderData['cart_contents'] as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'product_name' => $item['name'],
                    'product_price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'attributes' => json_encode($item['attributes']),
                ]);
            }


            \Cart::session($orderData['user_id'])->clear();
            session()->forget('order_data');

            return redirect()->route('home')->with('success', 'Payment successful! Your order has been placed.');
        } catch (\Exception $e) {
            return redirect()->route('checkout')->with('error', 'Stripe error: ' . $e->getMessage());
        }
    }
    public function stripeCancel()
    {
        return redirect()->route('checkout')->with('error', 'Payment was canceled. Please try again.');
    }



    public function processSslPayment(Request $request, $totalPrice)
{
    $userId = auth()->id();
    $cartContents = \Cart::session($userId)->getContent();

    // Generate a unique transaction ID
    $transactionId = 'SSL'.time().rand(1000,9999);

    // Create order first with pending status
    $order = Order::create([
        'user_id'        => $userId,
        'name'           => $request->name,
        'email'          => $request->email,
        'street'         => $request->street,
        'zipcode'        => $request->zipcode,
        'phone'          => $request->phone,
        'note'           => $request->note ?? null,
        'payment_method' => 'sslcommerze',
        'total_price'    => $totalPrice,
        'status'         => 'pending',
        'transaction_id' => $transactionId,
        'currency'       => 'BDT'
    ]);

    // Create order items
    foreach ($cartContents as $item) {
        OrderItem::create([
            'order_id'      => $order->id,
            'product_id'    => $item->id,
            'product_name'  => $item->name,
            'product_price' => $item->price,
            'quantity'      => $item->quantity,
            'attributes'    => json_encode($item->attributes)
        ]);
    }

    // Prepare data for SSLCOMMERZ
    $post_data = [
        'total_amount' => $totalPrice,
        'currency' => "BDT",
        'tran_id' => $transactionId,
        'cus_name' => $request->name,
        'cus_email' => $request->email,
        'cus_add1' => $request->street,
        'cus_add2' => "",
        'cus_city' => "",
        'cus_state' => "",
        'cus_postcode' => $request->zipcode,
        'cus_country' => "Bangladesh",
        'cus_phone' => $request->phone,
        'cus_fax' => "",
        'ship_name' => $request->name,
        'ship_add1' => $request->street,
        'ship_add2' => "",
        'ship_city' => "",
        'ship_state' => "",
        'ship_postcode' => $request->zipcode,
        'ship_country' => "Bangladesh",
        'shipping_method' => "NO",
        'product_name' => "Online Purchase",
        'product_category' => "Goods",
        'product_profile' => "general",
        'value_a' => $order->id, // Store order ID for callback
        'success_url' => route('sslcommerz.success'),
        'fail_url' => route('fail'),
        'cancel_url' => route('cancel'),
    ];

    // Initiate SSLCOMMERZ Payment
    $sslc = new SslCommerzNotification();
    $payment_options = $sslc->makePayment($post_data, 'hosted');

    if (!is_array($payment_options)) {
        $order->update(['status' => 'failed']);
        return redirect()->route('home')->with('error', 'Failed to initialize SSLCommerz payment.');
    }

    // Store order ID in session for later reference
    session()->put('current_order_id', $order->id);

    // Redirect to SSLCommerz payment page
    return redirect()->away($payment_options['GatewayPageURL']);
}

public function success(Request $request)
{
    $tran_id = $request->input('tran_id');
    $amount = $request->input('amount');
    $currency = $request->input('currency');
    $order_id = $request->input('value_a'); // Get order ID from callback

    $sslc = new SslCommerzNotification();

    // Validate the payment
    $validation = $sslc->orderValidate($request->all(), $tran_id, $amount, $currency);

    if ($validation) {
        // Update order status
        $order = Order::find($order_id);
        if ($order) {
            $order->update([
                'status' => 'completed',
                'transaction_id' => $tran_id
            ]);

            // Clear cart
            \Cart::session($order->user_id)->clear();
            session()->forget('current_order_id');

            return redirect()
                ->route('home')
                ->with('success', 'Payment successful! Order #'.$order->id.' has been placed.');
        }
    }

    // If validation fails
    return redirect()
        ->route('home')
        ->with('error', 'Payment validation failed. Please contact support.');
}

public function fail(Request $request)
{
    $tran_id = $request->input('tran_id');
    $order_id = $request->input('value_a');

    // Update order status to failed
    if ($order_id) {
        Order::where('id', $order_id)
            ->update(['status' => 'failed']);
    }

    return redirect()
        ->route('home')
        ->with('error', 'Payment failed. Please try again.');
}

public function cancel(Request $request)
{
    $tran_id = $request->input('tran_id');
    $order_id = $request->input('value_a');

    // Update order status to canceled
    if ($order_id) {
        Order::where('id', $order_id)
            ->update(['status' => 'canceled']);
    }

    return redirect()
        ->route('home')
        ->with('error', 'Payment was canceled. You can try again.');
}

}
