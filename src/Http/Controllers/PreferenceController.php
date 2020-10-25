<?php

namespace Blog\Http\Controllers;

use Blog\Models\Category;
use Blog\Models\Tag;
use Illuminate\Http\Request;

class PreferenceController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $user = auth()->user();
        return view('blog::pages.preferences.index', [
            'categories' => Category::query()->whereNull('parent_id')->orderBy('title', 'asc')->get(),
            'tags' => Tag::query()->orderBy('name', 'asc')->get(),
            'userCategories' => $user->preferredCategories()->allRelatedIds()->toArray(),
            'userTags' => $user->preferredTags()->allRelatedIds()->toArray(),
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $user->preferredCategories()->sync($request->get('categories', []));
        $user->preferredTags()->sync($request->get('tags', []));
        if ($returnUrl = $request->get('returnUrl')) {
            if (filter_var($returnUrl, FILTER_VALIDATE_URL)) {
                return redirect()->away($returnUrl)->with('message', 'Preferences saved successfully. We will try to recommend contents based on your preferences');
            }
        }
        return redirect()->back()->with('message', 'Preferences saved successfully');
    }
}
