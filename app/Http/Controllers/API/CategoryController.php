<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class CategoryController extends Controller
{
    public function categories()
    {
        $categories = Category::all();
        return response()->json($categories);
    }
    public function category($id)
    {
        $category = Category::find($id);
        if (empty($category)) {
            return response()->json([
                "status" => false,
                "data" => [
                    "message" => "Category doesnt exist"
                ]
            ]);
        }
        return response()->json([
            "status" => true,
            "data" => [
                $category
            ]
        ], 200);
    }
    public function categoryProduct($categoryId, $productId)
    {
        $validator = Validator::make(["categoryId" => $categoryId, "productId" => $productId], [
            "categoryId" => 'required|integer|exists:categories,id',
            "productId" => 'required|integer|exists:products,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "message" => "Validation error",
                "error" => $validator->errors()
            ], 400);
        }
        $product = Product::find($productId);
        return response()->json([
            "status" => true,
            "data" => [$product]
        ]);
    }
    public function categoryProducts($id)
    {
        $category = Category::where("id", $id);
        if (empty($category)) {
            return response()->json([
                "status" => false,
                "data" => [
                    "message" => "Category doesnt exist"
                ]
            ]);
        }
        $products = Product::join('categories', 'products.category_id', '=', 'categories.id')
            ->where('categories.id', $id)
            ->select('products.*', 'categories.name as category_name')
            ->get();
        return response()->json([
            "status" => true,
            "data" => [
                $products
            ]
        ], 200);
    }

    public function addCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "message" => "Validation error",
                "error" => $validator->errors()
            ], 400);
        }
        Category::create(["name" => $request->name]);
        return response()->json([
            "status" => true,
            "message" => "Category has been created"
        ], 201);
    }
    public function categoryUpdate($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string|min:2|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "message" => "Validation error",
                "error" => $validator->errors()
            ], 400);
        }
        $product = Category::find($id)->first();
        $product->name = $request->name ?? $product->name;
        $product->save();
        return response()->json([
            "status" => true,
            "message" => "Category updated",
        ], 200);
    }
    public function addProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2|max:255',
            'description' => 'required|string|min:5',
            'price' => 'required|numeric|min:0',
            'storage_amount' => 'required|integer|min:0',
            'category_id' => 'required|integer|exists:categories,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "message" => "Validation error",
                "error" => $validator->errors()
            ], 400);
        }
        $product = Product::create(request()->all());
        return response()->json(["status" => true, "data" => ["message" => "Product has been created", "data" => ["product" => $product]]], 201);
    }
    public function productUpdate($categoryId, $productId, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string|min:2|max:255',
            'description' => 'string|min:5',
            'price' => 'numeric|min:0',
            'storage_amount' => 'integer|min:0',
            'category_id' => 'integer|exists:categories,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "message" => "Validation error",
                "error" => $validator->errors()
            ], 400);
        }
        $product = Product::find($productId)->first();
        $product->name = $request->name ?? $product->name;
        $product->description = $request->description ?? $product->description;
        $product->price = $request->price ?? $product->price;
        $product->storage_amount = $request->storage_amount ?? $product->storage_amount;
        $product->category_id = $request->category_id ?? $product->category_id;
        $product->save();
        return response()->json([
            "status" => true,
            "message" => "Product updated",
        ], 200);
    }
}
