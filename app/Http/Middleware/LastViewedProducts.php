<?php

namespace App\Http\Middleware;

use App\Models\Product;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class LastViewedProducts
{
  /**
   * Handle an incoming request.
   *
   * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
   */
  public function handle(Request $request, Closure $next): Response
  {
    if ($request->routeIs("products.show")) {
      /* Session::remove("last_viewed_products");
      return to_route("aboutus"); */
      $product = Product::where("slug", $request->product_slug)->first();

      if ($product) {
        $last_viewed_products = Session::get("last_viewed_products", []);

        if (!key_exists($product->id, $last_viewed_products)) {
          $last_viewed_products[$product->id] =
            [
              "id" => $product->id,
              "count" => 1
            ];
          Session::put("last_viewed_products", $last_viewed_products);
        } else {
          $last_viewed_products[$product->id]["count"] += 1;
          Session::put("last_viewed_products", $last_viewed_products);
        }
      }
    }
    return $next($request);
  }
}
