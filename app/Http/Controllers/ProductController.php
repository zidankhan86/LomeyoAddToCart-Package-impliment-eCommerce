<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductSize;
use Illuminate\Support\Str;
use App\Models\ImageGallery;
use App\Models\ProductColor;
use Illuminate\Http\Request;
use App\Models\ProductVariant;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function create()
    {
        $data['categories'] = Category::get();
       return view('backend.product.create',$data);
    }
     public function index()
     {
        $data['products'] = Product::all();

        return view('backend.product.index', $data);
     }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|max:255',
    //         'price' => 'required|numeric',
    //         'image' => 'required|image|mimes:jpeg,png,jpg,gif',
    //         'description' => 'required',
    //         'status'=>'required',
    //         'is_popular' =>'required'
    //     ]);

    //     $imageName = null;
    //     if ($request->hasFile('image')) {
    //         $imageName = date('YmdHis') . '.' . $request->file('image')->getClientOriginalExtension();
    //         $request->file('image')->storeAs('uploads', $imageName, 'public');
    //     }

    //     // Create a new Product instance
    //     $product = new Product;
    //     $product->name = $request->name;
    //     $product->category_id = $request->category_id;
    //     $product->slug = Str::slug($request->name);
    //     $product->price = $request->price;
    //     $product->image = '/public/uploads/' .$imageName;
    //     $product->description = $request->description;
    //     $product->is_popular = $request->is_popular;
    //     $product->status = $request->status;

    //     $product->save();

    //     if ($request->hasFile('images')) {
    //         foreach ($request->file('images') as $file) {
    //             if ($file->isValid()) {
    //                 $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
    //                 $filePath = $file->storeAs('uploads', $filename, 'public');
    //                 ImageGallery::create([
    //                     'product_id' => $product->id,
    //                     'images' => '/public/uploads/' . $filename,
    //                 ]);
    //             }
    //         }
    //     }

    //     return redirect()->route('product.index')->with('success', 'Product created successfully');

    // }



    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'price' => 'required|numeric',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif',
            'description' => 'required',
            'status' => 'required',
            'is_popular' => 'required',
        ]);

        $imageName = null;
        if ($request->hasFile('image')) {
            $imageName = date('YmdHis') . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->storeAs('uploads', $imageName, 'public');
        }

        $product = new Product;
        $product->name = $request->name;
        $product->category_id = $request->category_id;
        $product->slug = Str::slug($request->name);
        $product->price = $request->price;
        $product->image = '/public/uploads/' . $imageName;
        $product->description = $request->description;
        $product->is_popular = $request->is_popular;
        $product->status = $request->status;
        $product->save();

        // ✅ Save image gallery
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                if ($file->isValid()) {
                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('uploads', $filename, 'public');
                    ImageGallery::create([
                        'product_id' => $product->id,
                        'images' => '/public/uploads/' . $filename,
                    ]);
                }
            }
        }

        // ✅ Save Variants with color and size IDs
        if ($request->has('variants')) {
            foreach ($request->variants as $variant) {
                // Find or create the color
                $color = ProductColor::firstOrCreate(
                    ['product_id' => $product->id, 'color' => $variant['color']]
                );

                // Find or create the size
                $size = ProductSize::firstOrCreate(
                    ['size' => $variant['size']]
                );

                // Create the product variant
                ProductVariant::create([
                    'product_id' => $product->id,
                    'product_color_id' => $color->id,
                    'product_size_id' => $size->id,
                    'stock' => $variant['stock'],
                ]);
            }
        }

        return redirect()->route('product.index')->with('success', 'Product created successfully');
    }



    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data['product'] = Product::find($id);
        $data['categories'] =Category::get();
        $data['variants'] = ProductVariant::get();
        return view('backend.product.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
{
    $product = Product::find($id);

    $request->validate([
        'name' => 'required|max:255',
        'price' => 'required|numeric',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif', // image is optional during update
        'description' => 'required',
    ]);

    // Handle image upload if a new image is provided
    if ($request->hasFile('image')) {
        // Generate a unique name for the new image
        $imageName = date('YmdHis') . '.' . $request->file('image')->getClientOriginalExtension();

        // Delete the old image if it exists
        if ($product->image && \Storage::disk('public')->exists('uploads/' . $product->image)) {
            \Storage::disk('public')->delete('uploads/' . $product->image);
        }

        // Store the new image
        $request->file('image')->storeAs('uploads', $imageName, 'public');

        // Update the product's image field
        $product->image = '/public/uploads/'.$imageName;
    }

    // Update the product's other fields
    $product->name = $request->input('name');
    $product->price = $request->input('price');
    $product->description = $request->input('description');
    $product->status = $request->input('status');
    $product->is_popular = $request->input('is_popular');
    // Save the updated product to the database
    $product->save();

    return redirect()->route('product.index')->with('success', 'Product updated successfully');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
