<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\View\View;

class CategoryController extends Controller
{
    // public function show($slug)
    // {
    //     $category = Category::where('slug', $slug)
    //         ->with('portfolios')
    //         ->firstOrFail();

    //     return view('frontend.category', compact('category'));

    //     $category = Category::where('slug', $slug)->firstOrFail();

    //     $portfolios = $category->portfolios()->paginate(12);

    //     return view('frontend.category', compact('category', 'portfolios'));
    // }

    // public function show(Category $category)
    // {
    //     $portfolios = $category->portfolios()->paginate(12);

    //     $baItems = $portfolios->map(fn($p) => [
    //     'before' => asset('storage/'.$p->before_image),
    //     'after'  => asset('storage/'.$p->after_image),
    // ])->values();

    //     return view('frontend.category', compact('category', 'portfolios'));
    // }



    // public function show(Category $category)
    // {
    //     // 1. Current category ke portfolios ko paginate karein
    //     $portfolios = $category->portfolios()->paginate(12);

    //     // 2. Before/After items ki array mapped list (Agar aap ise blade ya slider me inject kar rahe hain)
    //     $baItems = $portfolios->map(fn($p) => [
    //         'before' => asset('storage/'.$p->before_image),
    //         'after'  => asset('storage/'.$p->after_image),
    //     ])->values();

    //     /* ----------------------------------------------------
    //        DYNAMIC NEXT / PREV CATEGORY LOGIC
    //     ---------------------------------------------------- */

    //     // Current ID se badi ID wali pehli category uthayein (Next)
    //     $nextCategory = Category::where('id', '>', $category->id)
    //                             ->orderBy('id', 'asc')
    //                             ->first();

    //     // Agar aage koi category nahi hai, to pehli category par wrap-around karein
    //     if (!$nextCategory) {
    //         $nextCategory = Category::orderBy('id', 'asc')->first();
    //     }

    //     // Current ID se choti ID wali pehli category uthayein (Prev)
    //     $prevCategory = Category::where('id', '<', $category->id)
    //                             ->orderBy('id', 'desc')
    //                             ->first();

    //     // Agar pehle koi category nahi hai, to aakhri category par wrap-around karein
    //     if (!$prevCategory) {
    //         $prevCategory = Category::orderBy('id', 'desc')->first();
    //     }

    //     // 3. Sab variables view block me send karein
    //     return view('frontend.category', compact(
    //         'category', 
    //         'portfolios', 
    //         'baItems', 
    //         'nextCategory', 
    //         'prevCategory'
    //     ));
    // }

    public function show(Category $category, Request $request): View
    {
        // URL se value pakdein, default 12
        $perPage = $request->input('per_page', 12);

        // Strict validation array
        if (!in_array($perPage, [4, 8, 12, 16])) {
            $perPage = 12;
        }

        $portfolios = $category->portfolios()->paginate($perPage);

        // Pagination links me per_page query string lock rakhne ke liye
        $portfolios->appends(['per_page' => $perPage]);

        $baItems = $portfolios->getCollection()->map(fn($p) => [
            'before' => asset('storage/' . $p->before_image),
            'after'  => asset('storage/' . $p->after_image),
        ])->values();

        $nextCategory = Category::where('id', '>', $category->id)->orderBy('id', 'asc')->first() ?: Category::orderBy('id', 'asc')->first();
        $prevCategory = Category::where('id', '<', $category->id)->orderBy('id', 'desc')->first() ?: Category::orderBy('id', 'desc')->first();

        return view('frontend.category', compact('category', 'portfolios', 'baItems', 'nextCategory', 'prevCategory'));
    }
}
