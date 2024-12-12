<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Container\Attributes\Storage;

class ProductController extends Controller
{
    /**
     * Display all products.
     */
    public function index()
    {
        $products = Product::all(); // Fetch all products
        return response()->json($products, 200); // Pass products to the view
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle Image Upload
        $path = $request->file('image')
            ? $request->file('image')->store('images', 'public')
            : null;

        $product = Product::create([
            'name' => $validatedData['name'],
            'sku' => $validatedData['sku'],
            'price' => $validatedData['price'],
            'image' => $path,
        ]);

        return response()->json($product, 201);
    }

    public function update(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:255|unique:products,sku,' . $product->id,
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle Image Upload
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::delete('public/' . $product->image); // Delete old image
            }
            $path = $request->file('image')->store('images', 'public');
        } else {
            $path = $product->image;
        }

        $product->update([
            'name' => $validatedData['name'],
            'sku' => $validatedData['sku'],
            'price' => $validatedData['price'],
            'image' => $path,
        ]);

        return response()->json($product, 200);
    }


    public function destroy($id)
    {

        $product = Product::findorFail($id);
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully'], 200);
    }
}