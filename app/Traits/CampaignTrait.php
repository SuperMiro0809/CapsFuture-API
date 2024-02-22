<?php

namespace App\Traits;
use Illuminate\Support\Facades\Storage;
use App\Models\Campaign;

trait CampaignTrait {
    public function getCampaigns($lang, $id=null, $all=false, $upcoming=false) {
        $query = Campaign::select(
                    'campaigns.*',
                    'translations.title',
                    'translations.short_description',
                    'translations.description'
                )
                ->with(['cities', 'translations'])
                ->leftJoin('translations', function ($q) use ($lang) {
                    $q->on('translations.parent_id', 'campaigns.id')
                    ->where('translations.model', Campaign::class)
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

        if(request()->query('city')) {
            $query->whereHas('cities', function ($q) {
                $q->where('city', request()->query('city'));
            });
        }

        if(request()->query('date')) {
            $query->where('date', request()->query('date'));
        }

        if(request()->query('active')) {
            switch (request()->query('active')) {
                case 'upcoming':
                    $query->whereDate('date', '>=', date('Y-m-d'));
                    break;
                case 'past':
                    $query->whereDate('date', '<', date('Y-m-d'));
                    break;
            }
        }

        if($upcoming) {
            $query->whereDate('date', '>=', date('Y-m-d'));
        }

        if(request()->has(['field', 'direction'])){
            $query->orderBy(request()->query('field'), request()->query('direction'));
        }

        if($id) {
            $campaigns = $query->where('campaigns.id', $id)->first();
        }else if($all) {
            $campaigns = $query->get();
        }else {
            if(request()->query('limit')) {
                $campaigns = $query->paginate(request()->query('limit'))->withQueryString();
            }else {
                $campaigns = $query->paginate(10)->withQueryString();
            }
        }

        return $campaigns;
    }
}