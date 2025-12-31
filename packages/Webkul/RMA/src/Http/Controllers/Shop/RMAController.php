<?php

namespace Webkul\RMA\Http\Controllers\Shop;

use Illuminate\View\View;
use Webkul\Shop\Http\Controllers\Controller;

class RMAController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('rma::shop.index');
    }
}
