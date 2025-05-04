<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {

    //     $products = Product::where('status','active')->get();
    //     $categories = Category::latest()->take(12)->get();
    //     return view('frontend.pages.home',compact('products','categories'));
    // }

    public function index(Request $request)
    {
        $categories = Category::latest()->take(10)->get();

        // Check if a category filter is applied via slug
        if ($request->has('category')) {
            $selectedCategory = Category::where('slug', $request->category)->first();

            if ($selectedCategory) {
                $products = Product::where('category_id', $selectedCategory->id)
                                   ->where('status', 'active')
                                   ->get();
            } else {
                $products = collect(); // Empty collection if invalid category
            }
        } else {
            $products = Product::where('status', 'active')->get(); // Show all products when no filter
        }

        return view('frontend.pages.home', compact('products', 'categories'));
    }



    public function product(Request $request)
    {
        $query = $request->input('query');

        if ($query) {
            $products = Product::where('name', 'LIKE', "%{$query}%")
                ->orWhere('description', 'LIKE', "%{$query}%")
                ->get();
        } else {
            $products = Product::where('status','active')->get();
        }
        $categories = Category::latest()->take(10)->get();
        return view('frontend.pages.product', compact('products', 'query','categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function details($slug)
    {

        $data['product'] = Product::with('images')->where('slug', $slug)->firstOrFail();

        return view('frontend.components.product.details',$data);
    }


    public function latestProduct(){

        $data['latestProducts'] = Product::where('status','active')->latest()->take(12)->get();

        return view('frontend.new_arrival.index',$data);
    }

    public function popularProduct(){

        $data['popularProducts'] = Product::where('status','active') ->where('is_popular', 1)->get();

        return view('frontend.popular_product.index',$data);
    }

}
