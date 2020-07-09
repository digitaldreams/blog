<?php

namespace Blog\Http\Requests\Posts;

use Illuminate\Foundation\Http\FormRequest;

class Update extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|max:255|unique:blog_posts,title,' . $this->route('post')->id,
            'body' => 'required',
            'category_id' => 'required|exists:blog_categories,id|numeric',
            'image' => 'image|max:2048',
        ];
    }
}
