<?php

namespace Blog\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UniqueSlugGeneratorService
{
    /**
     * Create Slug for Model.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param                                     $title
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createSlug(Model $model, $title)
    {
        $baseSlug = $slug = $this->makeSlug($title);

        for ($i = 1; $model->where('slug', $slug)->exists(); ++$i) {
            $slug = $baseSlug . '-' . $i;
        }
        $model->slug = $slug;

        return $model;
    }

    /**
     * Convert a string to an English lang slug.
     *
     * @param string $string
     *
     * @return string
     */
    private function makeSlug(string $string): string
    {
        return Str::of($string)
            ->lower()
            ->replaceMatches('/[^a-z0-9-\s]+/', '')
            ->replaceMatches('/[\s-]+/', '-');
    }
}
