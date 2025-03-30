<?php

namespace App\Http\Controllers\frontend;

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
            $paypalRedirect = $this->processPaypalPayment($request, $totalPrice);

            if (!empty($paypalRedirect) && filter_var($paypalRedirect, FILTER_VALIDATE_URL)) {
                return redirect()->away(trim($paypalRedirect));
            } else {
                return redirect()->back()->with('error', 'PayPal payment failed. Please try again.');
            }
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
}
