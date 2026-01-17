<?php

namespace Webkul\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Services\StockDocumentService;

class StockDocumentController extends Controller
{
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
}
