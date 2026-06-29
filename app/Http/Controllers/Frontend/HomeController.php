<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $categories = Category::withCount('portfolios')
            ->latest()
            ->get();

        return view('frontend.home', compact('categories'));
    }

    public function contactUs(): View
    {
        return view('frontend.contact-us');
    }
}
