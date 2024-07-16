<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class ProductController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {

  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    //
  }

  /**
   * Display the specified resource.
   */
  public function show($category_slug, $product_slug)
  {
    $category = Category::where("slug", $category_slug)->orWhere("slug", __("categories.dictionary." . $category_slug))->first();
    $product = Product::where("slug", $product_slug)->first();
    $breadcrumbs = [
      [
        "name" => !Str::startsWith(__('categories.' . $category->slug . '.name'), 'categories.')
          ? __('categories.' . $category->slug . '.name')
          : __('categories.' . __('categories.dictionary.' . $category->slug) . '.name'),
        "url" => route("category-slug", ["slug" => $category->slug])
      ],
      [
        "name" => $product->brand->name,
        "url" => route("brand-slug", ["slug" => $product->brand->name])
      ]
    ];

    if (!$category || !$product) {
      return abort(404);
    }

    return view("products.show", [
      "product" => $product,
      "product_slug" => $product_slug,
      "breadcrumbs" => $breadcrumbs
    ]);
  }

  public function addToCart()
  {
    return response()->json([
      "productId" => request("productId")
    ], 200);
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(string $id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    //
  }
}
