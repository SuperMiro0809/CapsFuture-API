<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Traits\PostTrait;
use App\Models\{
    Post,
    Translation
};

class PostController extends Controller
{
    use PostTrait;

    public function index()
    {
        $lang = request()->query('lang', 'bg');

        $posts = $this->getPosts($lang);

        return $posts;
    }

    public function getAll()
    {
        $lang = request()->query('lang', 'bg');

        $posts = $this->getPosts($lang, null, true);

        return $posts;
    }

    public function store(Request $request)
    {
        $information = json_decode($request->information, true);

        $title_image = $request->file('title_image');

        $title_image_path = $title_image->store('posts', 'public');

        $result = DB::transaction(function () use ($request, $information, $title_image_path) {
            $post = Post::create([
                'slug' => Str::slug($information['en']['title']),
                'title_image_path' => $title_image_path,
                'active' => $request->active
            ]);

            foreach($information as $key=>$info) {
                Translation::create([
                    'parent_id' => $post->id,
                    'model' => Post::class,
                    'title' => $info['title'],
                    'short_description' => $info['short_description'],
                    'description' => $info['description'],
                    'language' => $key
                ]);
            }

            return $post;
        });

        return $result;
    }

    public function update(Request $request, $id)
    {
        $information = json_decode($request->information, true);

        $result = DB::transaction(function () use ($request, $id, $information) {
            $post = Post::find($id);

            $title_image_path = $post->title_image_path;

            if($title_image = $request->file('title_image')) {
                Storage::delete('public/' . $title_image_path);
                $title_image_path = $title_image->store('posts', 'public');
            }

            $post->update([
                'slug' => Str::slug($information['en']['title']),
                'title_image_path' => $title_image_path,
                'active' => $request->active
            ]);

            foreach($information as $key=>$info) {
                $post->translations()->updateOrCreate(['id' => $info['id'] ?? null], [
                    'title' => $info['title'],
                    'short_description' => $info['short_description'],
                    'description' => $info['description'],
                ]);
            }

            return $post;
        });

        return $result;
    }

    public function destroy($id)
    {
        $result = DB::transaction(function () use ($id) {
            $post = Post::find($id);

            Storage::delete('public/posts/' . $post->title_image_path);

            $post->translations()->delete();

            $post->delete();

            return 'Delete successful';
        });

        return $result;
    }

    public function show($slug)
    {
        $lang = request()->query('lang', 'bg');

        $post = $this->getPosts($lang, $slug);

        return $post;
    }
}
