<?php

namespace App\Http\Controllers;

use App\Models\AboutSection;
use App\Models\Facility;
use App\Models\GalleryPhoto;
use App\Models\Offer;
use App\Models\Plan;
use App\Models\Service;
use App\Models\Trainer;
use Illuminate\Http\Response;

class PageController extends Controller
{
    public function about()
    {
        $trainers = Trainer::orderBy('display_order')->take(3)->get();
        $services = Service::visible()->get();
        $aboutSections = AboutSection::visible()->get();

        return view('about', compact('trainers', 'services', 'aboutSections'));
    }

    public function facilities()
    {
        $facilities = Facility::visible()->get();

        return view('facilities', compact('facilities'));
    }

    public function gallery()
    {
        $photos = GalleryPhoto::visible()->get();

        return view('gallery', compact('photos'));
    }

    public function freeTrial()
    {
        $services = Service::visible()->take(6)->get();

        return view('free-trial', compact('services'));
    }

    public function sitemap(): Response
    {
        $routes = ['gym.home', 'gym.services', 'gym.facilities', 'gym.gallery', 'gym.trainers', 'gym.membership', 'gym.offers', 'gym.about', 'gym.contact', 'gym.trial'];

        $lastmod = collect([
            Plan::max('updated_at'),
            Trainer::max('updated_at'),
            Offer::max('updated_at'),
            Service::max('updated_at'),
        ])->filter()->max();

        $urls = collect($routes)
            ->map(fn ($r) => $r === 'gym.home' ? url('/') : route($r))
            ->merge(Service::visible()->get()->map(fn ($service) => route('gym.service', $service)));

        $xml = view('sitemap', [
            'urls' => $urls,
            'lastmod' => $lastmod ? date('Y-m-d', strtotime($lastmod)) : null,
        ])->render();

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }
}
