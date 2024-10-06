<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\{
    Faq,
    Translation
};

class FaqController extends Controller
{
    public function index()
    {
        $lang = request()->query('lang', 'bg');

        $query = Faq::select(
                'faqs.id',
                'faqs.order',
                'translations.title',
                'translations.description'
            )
            ->with('translations')
            ->leftJoin('translations', function ($q) use ($lang) {
                $q->on('translations.parent_id', 'faqs.id')
                ->where('translations.model', Faq::class)
                ->where('translations.language', $lang);
            });
        
        $faqs = $query->get();

        return $faqs;
    }

    public function storeMany(Request $request)
    {
        $faqsList = $request->faqs;

        $result = DB::transaction(function () use ($faqsList) {
            foreach ($faqsList as $faqData) {
                $faq = Faq::create([ 'order' => $faqData['order'] ]);

                $information = $faqData['information'];

                foreach($information as $key=>$info) {
                    Translation::create([
                        'parent_id' => $faq->id,
                        'model' => Faq::class,
                        'title' => $info['title'],
                        'description' => $info['description'],
                        'language' => $key
                    ]);
                }
            }

            return 'Create successful';
        });

        return $result;
    }

    public function updateMany(Request $request)
    {
        $faqsList = $request->faqs;

        $result = DB::transaction(function () use ($faqsList) {
            foreach ($faqsList as $faqData) {
                $faq = Faq::find($faqData['id']);
                
                $faq->update([ 'order' => $faqData['order'] ]);
    
                $information = $faqData['information'];

                foreach($information as $key=>$info) {
                    $newInfo = $faq->translations()->updateOrCreate(['id' => $info['id'] ?? null], [
                        'title' => $info['title'],
                        'description' => $info['description'],
                    ]);
                }
            }

            return 'Update successful';
        });

        return $result;
    }

    public function deleteMany(Request $request)
    {
        $ids = $request->ids;

        $result = DB::transaction(function () use ($ids) {
            foreach($ids as $id) {
                $faq = Faq::find($id);

                $faq->translations()->delete();

                $faq->delete();
            }

            return 'Delete successful';
        });

        return $result;
    }
}
