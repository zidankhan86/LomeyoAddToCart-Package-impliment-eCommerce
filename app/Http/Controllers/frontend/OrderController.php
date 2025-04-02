<?php

namespace App\Http\Controllers\frontend;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use App\Models\Order;
use App\Models\OrderItem;
use Darryldecode\Cart\Cart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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

        $userId = auth()->id();
        $cartContents = \Cart::session($userId)->getContent();
        $subtotal = \Cart::session($userId)->getSubTotal();
        $totalPrice = \Cart::session($userId)->getTotal();

        if ($request->payment_method === 'stripe') {
            $paymentSuccess = $this->processStripePayment($request, $totalPrice);

            if ($paymentSuccess) {
                return redirect($paymentSuccess);
            } else {
                return redirect()->back()->with('error', 'Stripe Payment failed. Please try again.');
            }
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
}
