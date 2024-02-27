<?php

namespace App\Traits;
use Illuminate\Support\Facades\Storage;
use App\Models\Post;

trait PostTrait {
    public function getPosts($lang, $slug=null, $all=false) {
        $query = Post::select(
                    'posts.*',
                    'translations.title',
                    'translations.short_description',
                    'translations.description'
                )
                ->with('translations')
                ->leftJoin('translations', function ($q) use ($lang) {
                    $q->on('translations.parent_id', 'posts.id')
                    ->where('translations.model', Post::class)
                    ->where('translations.language', $lang);
                });

        if(request()->query('title')) {
            $query->where('translations.title', 'LIKE', '%'.request()->query('title').'%');
        }

        if(request()->query('short_description')) {
            $query->where('translations.short_description', 'LIKE', '%'.request()->query('short_description').'%');
        }

        if(request()->query('description')) {
            $query->where('translations.description', 'LIKE', '%'.request()->query('description').'%');
        }

        if(request()->query('search')) {
            $query->where(function ($q) {
                $q->where('translations.title', 'LIKE', '%'.request()->query('search').'%')
                    ->orWhere('translations.short_description', 'LIKE', '%'.request()->query('search').'%');
            });
        }

        if(request()->has(['field', 'direction'])){
            $query->orderBy(request()->query('field'), request()->query('direction'));
        }

        if($slug) {
            $posts = $query->where('posts.slug', $slug)->first();
        }else if($all) {
            $posts = $query->get();
        }else {
            if(request()->query('limit')) {
                $posts = $query->paginate(request()->query('limit'))->withQueryString();
            }else {
                $posts = $query->paginate(10)->withQueryString();
            }
        }

        return $posts;
    }
}