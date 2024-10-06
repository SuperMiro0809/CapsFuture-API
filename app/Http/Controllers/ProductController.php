<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Traits\ProductTrait;
use App\Models\{
    Product,
    ProductFile,
    Translation
};

class ProductController extends Controller
{
    use ProductTrait;

    public function index()
    {
        $lang = request()->query('lang', 'bg');

        $products = $this->getProducts($lang);

        return $products;
    }

    public function getAll()
    {
        $lang = request()->query('lang', 'bg');

        $products = $this->getProducts($lang, null, true);

        return $products;
    }

    public function store(Request $request)
    {
        $information = json_decode($request->information, true);
        $images = $request->file('images');

        $result = DB::transaction(function () use ($request, $images, $information) {
            $product = Product::create([
                'slug' => Str::slug($information['en']['title']),
                'price' => $request->price,
                'active' => $request->active,
                'show_on_home_page' => $request->show_on_home_page
            ]);


            foreach($images as $image) {
                $file_path = $image->store('products/' . $product->id, 'public');

                ProductFile::create([
                    'filepath' => $file_path,
                    'product_id' => $product->id,
                    'filename' => $image->hashName()
                ]);
            }

            foreach($information as $key=>$info) {
                Translation::create([
                    'parent_id' => $product->id,
                    'model' => Product::class,
                    'title' => $info['title'],
                    'short_description' => $info['short_description'],
                    'description' => $info['description'],
                    'language' => $key
                ]);
            }

            return $product;
        });

        return $result;
    }

    public function update(Request $request, $id)
    {
        $information = json_decode($request->information, true);
        $images = $request->file('images');
        $deleteImagesIds = json_decode($request->deleteImagesIds, true);

        $result = DB::transaction(function () use ($request, $id, $images, $information, $deleteImagesIds) {
            $product = Product::find($id);

            if($images) {
                foreach($images as $image) {
                    $file_path = $image->store('products/' . $id, 'public');
    
                    ProductFile::create([
                        'filepath' => $file_path,
                        'product_id' => $product->id,
                        'filename' => $image->hashName()
                    ]);
                }
            }

            if($deleteImagesIds) {
                foreach($deleteImagesIds as $deleteImageId) {
                    $productFile = ProductFile::find($deleteImageId);
    
                    Storage::delete('public/' . $productFile->filepath);
    
                    $productFile->delete();
                }
            }

            $updateData = [];

            if($request->has('active')) {
                $updateData['active'] = $request->active;
            }

            if($request->has('price')) {
                $updateData['price'] = $request->price;
            }

            if($request->has('show_on_home_page')) {
                $updateData['show_on_home_page'] = $request->show_on_home_page;
            }

            $product->update($updateData);

            if($information) {
                foreach($information as $key=>$info) {
                    $newInfo = $product->translations()->updateOrCreate(['id' => $info['id'] ?? null], [
                        'title' => $info['title'],
                        'short_description' => $info['short_description'],
                        'description' => $info['description'],
                    ]);
                }

                $product->update(['slug' => Str::slug($information['en']['title'])]);
            }

            return $product;
        });

        return $result;
    }

    public function destroy($id)
    {
        $result = DB::transaction(function () use ($id) {
            $product = Product::find($id);

            $product->files()->delete();

            Storage::deleteDirectory('public/products/' . $id);

            $product->translations()->delete();

            $product->delete();

            return 'Delete successful';
        });

        return $result;
    }

    public function deleteMany(Request $request)
    {
        $ids = $request->ids;

        $result = DB::transaction(function () use ($ids) {
            foreach($ids as $id) {
                $product = Product::find($id);

                $product->files()->delete();

                Storage::deleteDirectory('public/products/' . $id);

                $product->translations()->delete();

                $product->delete();
            }

            return 'Delete successful';
        });

        return $result;
    }

    public function show($id)
    {
        $lang = request()->query('lang', 'bg');

        $product = $this->getProducts($lang, $id);

        return $product;
    }

    public function showBySlug($slug)
    {
        $lang = request()->query('lang', 'bg');

        $product = $this->getProducts($lang, null, false, false, $slug);

        return $product;
    }

    public function latest()
    {
        $lang = request()->query('lang', 'bg');

        $products = $this->getProducts($lang, null, false, true);

        return $products;
    }
}
