<?php

namespace Webkul\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Services\StockDocumentService;
use Webkul\Product\Repositories\ProductRepository;
use Illuminate\Support\Facades\DB;

class StockDocumentController extends Controller
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function create()
    {
        return view('admin::stock_documents.create');
    }

    public function store(Request $request, StockDocumentService $service)
    {
        $data = $request->only(['document_number', 'note', 'created_by']);
        $products = $request->input('products', []);
        $service->importStock($data, $products);
        return redirect()->back()->with('success', 'Nhập hàng thành công!');
    }

    /**
     * Search products by product_number for autocomplete
     */
    public function searchProducts(Request $request)
    {
        $query = $request->get('q', '');

        $products = DB::table('products')
            ->where('sku', 'LIKE', "%{$query}%")
            ->orWhere('id', 'LIKE', "%{$query}%")
            ->limit(10)
            ->get(['id', 'sku', 'type']);

        return response()->json($products);
    }

    /**
     * Get product details by SKU
     */
    public function getProductBySku(Request $request)
    {
        $sku = $request->get('sku', '');

        $product = DB::table('products')
            ->where('sku', $sku)
            ->first();

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        // Get product flat data for additional info
        $productFlat = DB::table('product_flat')
            ->where('product_id', $product->id)
            ->first();

        // Get color and size from product_super_attributes if configurable
        $attributes = DB::table('product_super_attributes')
            ->join('attributes', 'product_super_attributes.attribute_id', '=', 'attributes.id')
            ->where('product_super_attributes.product_id', $product->id)
            ->get(['attributes.code', 'attributes.admin_name']);

        $response = [
            'id' => $product->id,
            'sku' => $product->sku,
            'type' => $product->type,
            'name' => $productFlat->name ?? '',
            'price' => $productFlat->price ?? 0,
        ];

        return response()->json($response);
    }

    /**
     * Get distinct colors from products
     */
    public function getColors(Request $request)
    {
        $query = $request->get('q', '');

        // Get colors from product_flat table color attribute
        $colors = DB::table('product_attribute_values')
            ->join('attributes', 'product_attribute_values.attribute_id', '=', 'attributes.id')
            ->where('attributes.code', 'color')
            ->where('product_attribute_values.text_value', 'LIKE', "%{$query}%")
            ->distinct()
            ->limit(10)
            ->pluck('product_attribute_values.text_value');

        return response()->json($colors);
    }

    /**
     * Get distinct sizes from products
     */
    public function getSizes(Request $request)
    {
        $query = $request->get('q', '');

        // Get sizes from product_flat table size attribute
        $sizes = DB::table('product_attribute_values')
            ->join('attributes', 'product_attribute_values.attribute_id', '=', 'attributes.id')
            ->where('attributes.code', 'size')
            ->where('product_attribute_values.text_value', 'LIKE', "%{$query}%")
            ->distinct()
            ->limit(10)
            ->pluck('product_attribute_values.text_value');

        return response()->json($sizes);
    }
}
