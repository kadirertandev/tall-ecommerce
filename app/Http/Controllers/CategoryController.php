<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index($slug)
  {
    $category = Category::where("slug", $slug)
      ->orWhere("slug", __("categories.dictionary." . $slug))
      ->first();


    if (!$category) {
      return abort(404);
    }

    return view("category.slug", [
      "category" => $category,
      "categoryBrands" => $category->brands,
      "slug" => $slug,
      "category_slug_lang" => __("categories." . $category->slug . ".slug")
    ]);
  }

  public function products()
  {
    // dd(request()->all());
    // $products = Product::where("category_id", request("category_id"))->get();
    $products = Product::when(request()->has("category_id"), function ($query) {
      return $query->where("category_id", request("category_id"));
    })->when(request()->has("brands") && request("brands") != "", function ($query) {
      return $query->whereIn("brand_id", request("brands"));
    })->when(request("min_price") != "", function ($query) {
      return $query->where("price", ">=", request("min_price"));
    })->when(request("max_price") != "", function ($query) {
      return $query->where("price", "<=", request("max_price"));
    })->when(request()->has("sort") && request("sort") != "", function ($query) {
      return $query->orderBy("price", request("sort"));
    })->paginate(6);

    return response()->json($products, 200);
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
  public function show(Category $category)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Category $category)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Category $category)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Category $category)
  {
    //
  }
}
