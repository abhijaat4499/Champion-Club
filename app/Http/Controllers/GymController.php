<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\GalleryPhoto;
use App\Models\HeroSlide;
use App\Models\Offer;
use App\Models\Plan;
use App\Models\Review;
use App\Models\Trainer;
use App\Models\VideoTestimonial;


class GymController extends Controller
{
    public function index()
    {
        $plans = Plan::orderBy('display_order')->get();
     $trainers = Trainer::orderBy('display_order')->take(8)->get();

        $featuredOffer = Offer::visible()->first();
        $moreOffers = $featuredOffer ? Offer::visible()->count() - 1 : 0;
        $facilities = Facility::visible()->take(5)->get();
        $galleryPhotos = GalleryPhoto::visible()->take(5)->get();
        $videos = VideoTestimonial::visible()->get();
        $reviews = Review::visible()->get();
        $heroSlides = HeroSlide::forHome();

        return view('home_page', compact('plans' , 'trainers', 'featuredOffer', 'moreOffers', 'facilities', 'galleryPhotos', 'videos', 'reviews', 'heroSlides'));
    }
}
