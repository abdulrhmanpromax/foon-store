<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\View\View;

class StorefrontController extends Controller
{
    /**
     * Display public store catalog.
     */
    public function index(): View
    {
        $products = Product::query()
            ->where('is_active', true)
            ->withCount('availableVaultItems')
            ->orderBy('name')
            ->get();

        return view('store.index', compact('products'));
    }
}
