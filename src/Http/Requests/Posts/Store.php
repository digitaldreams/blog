<?php

namespace Blog\Http\Requests\Posts;

use Illuminate\Foundation\Http\FormRequest;
use Blog\Models\Post;
class Store extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check() && auth()->user()->can('create', Post::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|max:255',
            'slug' => 'nullable|unique:blog_posts,slug|max:255',
            'body' => 'required|string',
            'category_id' => 'required|exists:blog_categories,id|numeric',
            'image' => 'image|max:2048',
            #'published_at' => 'nullable|date',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [

        ];
    }

}
