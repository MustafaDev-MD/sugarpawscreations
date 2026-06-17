<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('portfolios')
            ->latest()
            ->get();

        return view('frontend.home', compact('categories'));
    }
}
