<?php
use App\Http\Controllers\GymController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminPlanController;
use App\Http\Controllers\AdminTrainerController;
use App\Http\Controllers\AdminPasswordResetController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\AdminOfferController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\AdminServiceController;
use App\Http\Controllers\AdminSiteController;
use App\Http\Controllers\AdminFacilityController;
use App\Http\Controllers\AdminGalleryController;
use App\Http\Controllers\AdminVideoTestimonialController;
use App\Http\Controllers\AdminAboutSectionController;
use App\Http\Controllers\AdminTestimonialController;
use App\Http\Controllers\AdminHeroSlideController;




use Illuminate\Support\Facades\Route;

Route::get('/', [GymController::class, 'index']);

Route::get('/gym', [GymController::class, 'index'])->name('gym.home');
Route::get('/contact', [ContactController::class, 'index'])->name('gym.contact');
Route::post('/contact', [ContactController::class, 'store'])->name('store.contact');
Route::post('/reviews', [ReviewController::class, 'store'])->name('gym.reviews.store');
Route::get('/thank-you', [ContactController::class, 'thankYou'])->name('gym.thankyou');

Route::permanentRedirect('/program', '/services');
Route::get('/services', [ServiceController::class, 'index'])->name('gym.services');
Route::get('/services/{service:slug}', [ServiceController::class, 'show'])->name('gym.service');
Route::get('/services/{service:slug}/book', [BookingController::class, 'service'])->name('gym.book.service');
Route::get('/membership/{plan}/join', [BookingController::class, 'plan'])->name('gym.book.plan');
Route::get('/offers/{offer}/claim', [BookingController::class, 'offer'])->name('gym.book.offer');
Route::get('/trainers/{trainer}/book', [BookingController::class, 'trainer'])->name('gym.book.trainer');
Route::get('/membership', [MembershipController::class, 'index'])->name('gym.membership');
Route::get('/trainers', [TrainerController::class, 'index'])->name('gym.trainers');
Route::get('/offers', [OfferController::class, 'index'])->name('gym.offers');
Route::get('/about', [PageController::class, 'about'])->name('gym.about');
Route::get('/facilities', [PageController::class, 'facilities'])->name('gym.facilities');
Route::get('/gallery', [PageController::class, 'gallery'])->name('gym.gallery');
Route::get('/free-trial', [PageController::class, 'freeTrial'])->name('gym.trial');
Route::get('/sitemap.xml', [PageController::class, 'sitemap'])->name('sitemap');

// admin routes below->x

Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::get('/admin/verify-otp', [AdminAuthController::class, 'showOtpForm'])->name('admin.otp.form');
Route::post('/admin/verify-otp', [AdminAuthController::class, 'verifyOtp'])->name('admin.otp.verify');
Route::post('/admin/verify-otp/resend', [AdminAuthController::class, 'resendOtp'])->middleware('throttle:6,10')->name('admin.otp.resend');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
Route::get('/admin/forgot-password', [AdminPasswordResetController::class, 'showEmailForm'])->name('admin.password.request');
Route::post('/admin/forgot-password', [AdminPasswordResetController::class, 'sendCode'])->name('admin.password.email');
Route::get('/admin/reset-otp', [AdminPasswordResetController::class, 'showOtpForm'])->name('admin.password.otp');
Route::post('/admin/reset-otp', [AdminPasswordResetController::class, 'verifyOtp'])->name('admin.password.verify');
Route::post('/admin/reset-otp/resend', [AdminPasswordResetController::class, 'resendCode'])->middleware('throttle:6,10')->name('admin.password.resend');
Route::get('/admin/reset-password', [AdminPasswordResetController::class, 'showResetForm'])->name('admin.password.reset');
Route::post('/admin/reset-password', [AdminPasswordResetController::class, 'resetPassword'])->name('admin.password.update');

Route::middleware('auth:admin')->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/plans', [AdminPlanController::class, 'index'])->name('admin.plans.index');
    Route::get('/admin/plans/create', [AdminPlanController::class, 'create'])->name('admin.plans.create');
    Route::post('/admin/plans', [AdminPlanController::class, 'store'])->name('admin.plans.store');
    

    Route::get('/admin/plans/{plan}/edit', [AdminPlanController::class, 'edit'])->name('admin.plans.edit');
    Route::put('/admin/plans/{plan}', [AdminPlanController::class, 'update'])->name('admin.plans.update');
    Route::delete('/admin/plans/{plan}', [AdminPlanController::class, 'destroy'])->name('admin.plans.destroy');
    Route::get('/admin/trainers', [AdminTrainerController::class, 'index'])->name('admin.trainers.index');
    Route::get('/admin/trainers/create', [AdminTrainerController::class, 'create'])->name('admin.trainers.create');
    Route::post('/admin/trainers', [AdminTrainerController::class, 'store'])->name('admin.trainers.store');
Route::put('/admin/trainers/{trainer}', [AdminTrainerController::class, 'update'])->name('admin.trainers.update');
Route::delete('/admin/trainers/{trainer}', [AdminTrainerController::class, 'destroy'])->name('admin.trainers.destroy');
Route::get('/admin/trainers/{trainer}/edit', [AdminTrainerController::class, 'edit'])->name('admin.trainers.edit');
Route::get('/admin/change-password', [AdminAuthController::class, 'showChangePasswordForm'])->name('admin.password.change');
Route::put('/admin/change-password', [AdminAuthController::class, 'changePassword'])->name('admin.password.change.update');

    Route::get('/admin/offers', [AdminOfferController::class, 'index'])->name('admin.offers.index');
    Route::get('/admin/offers/create', [AdminOfferController::class, 'create'])->name('admin.offers.create');
    Route::post('/admin/offers', [AdminOfferController::class, 'store'])->name('admin.offers.store');
    Route::get('/admin/offers/{offer}/edit', [AdminOfferController::class, 'edit'])->name('admin.offers.edit');
    Route::put('/admin/offers/{offer}', [AdminOfferController::class, 'update'])->name('admin.offers.update');
    Route::delete('/admin/offers/{offer}', [AdminOfferController::class, 'destroy'])->name('admin.offers.destroy');

    Route::get('/admin/services', [AdminServiceController::class, 'index'])->name('admin.services.index');
    Route::get('/admin/services/create', [AdminServiceController::class, 'create'])->name('admin.services.create');
    Route::post('/admin/services', [AdminServiceController::class, 'store'])->name('admin.services.store');
    Route::get('/admin/services/{service}/edit', [AdminServiceController::class, 'edit'])->name('admin.services.edit');
    Route::put('/admin/services/{service}', [AdminServiceController::class, 'update'])->name('admin.services.update');
    Route::delete('/admin/services/{service}', [AdminServiceController::class, 'destroy'])->name('admin.services.destroy');

    Route::get('/admin/facilities', [AdminFacilityController::class, 'index'])->name('admin.facilities.index');
    Route::get('/admin/facilities/create', [AdminFacilityController::class, 'create'])->name('admin.facilities.create');
    Route::post('/admin/facilities', [AdminFacilityController::class, 'store'])->name('admin.facilities.store');
    Route::get('/admin/facilities/{facility}/edit', [AdminFacilityController::class, 'edit'])->name('admin.facilities.edit');
    Route::put('/admin/facilities/{facility}', [AdminFacilityController::class, 'update'])->name('admin.facilities.update');
    Route::delete('/admin/facilities/{facility}', [AdminFacilityController::class, 'destroy'])->name('admin.facilities.destroy');

    Route::get('/admin/gallery', [AdminGalleryController::class, 'index'])->name('admin.gallery.index');
    Route::get('/admin/gallery/create', [AdminGalleryController::class, 'create'])->name('admin.gallery.create');
    Route::post('/admin/gallery', [AdminGalleryController::class, 'store'])->name('admin.gallery.store');
    Route::post('/admin/gallery/reorder', [AdminGalleryController::class, 'reorder'])->name('admin.gallery.reorder');
    Route::delete('/admin/gallery/bulk', [AdminGalleryController::class, 'bulkDestroy'])->name('admin.gallery.bulk-destroy');
    Route::get('/admin/gallery/{photo}/edit', [AdminGalleryController::class, 'edit'])->name('admin.gallery.edit');
    Route::put('/admin/gallery/{photo}', [AdminGalleryController::class, 'update'])->name('admin.gallery.update');
    Route::delete('/admin/gallery/{photo}', [AdminGalleryController::class, 'destroy'])->name('admin.gallery.destroy');

    Route::get('/admin/homepage', [AdminHeroSlideController::class, 'index'])->name('admin.hero.index');
    Route::get('/admin/homepage/slides/create', [AdminHeroSlideController::class, 'create'])->name('admin.hero.create');
    Route::post('/admin/homepage/slides', [AdminHeroSlideController::class, 'store'])->name('admin.hero.store');
    Route::get('/admin/homepage/slides/{slide}/edit', [AdminHeroSlideController::class, 'edit'])->name('admin.hero.edit');
    Route::put('/admin/homepage/slides/{slide}', [AdminHeroSlideController::class, 'update'])->name('admin.hero.update');
    Route::delete('/admin/homepage/slides/{slide}', [AdminHeroSlideController::class, 'destroy'])->name('admin.hero.destroy');

    Route::get('/admin/testimonials', [AdminTestimonialController::class, 'index'])->name('admin.testimonials.index');
    Route::get('/admin/testimonials/create', [AdminTestimonialController::class, 'create'])->name('admin.testimonials.create');
    Route::post('/admin/testimonials', [AdminTestimonialController::class, 'store'])->name('admin.testimonials.store');
    Route::put('/admin/testimonials/youtube', [AdminTestimonialController::class, 'updateYoutube'])->name('admin.testimonials.youtube');
    Route::patch('/admin/testimonials/{review}/approve', [AdminTestimonialController::class, 'approve'])->name('admin.testimonials.approve');
    Route::get('/admin/testimonials/{review}/edit', [AdminTestimonialController::class, 'edit'])->name('admin.testimonials.edit');
    Route::put('/admin/testimonials/{review}', [AdminTestimonialController::class, 'update'])->name('admin.testimonials.update');
    Route::delete('/admin/testimonials/{review}', [AdminTestimonialController::class, 'destroy'])->name('admin.testimonials.destroy');

    Route::get('/admin/videos', [AdminVideoTestimonialController::class, 'index'])->name('admin.videos.index');
    Route::get('/admin/videos/create', [AdminVideoTestimonialController::class, 'create'])->name('admin.videos.create');
    Route::post('/admin/videos', [AdminVideoTestimonialController::class, 'store'])->name('admin.videos.store');
    Route::get('/admin/videos/{video}/edit', [AdminVideoTestimonialController::class, 'edit'])->name('admin.videos.edit');
    Route::put('/admin/videos/{video}', [AdminVideoTestimonialController::class, 'update'])->name('admin.videos.update');
    Route::delete('/admin/videos/{video}', [AdminVideoTestimonialController::class, 'destroy'])->name('admin.videos.destroy');

    Route::get('/admin/about-sections', [AdminAboutSectionController::class, 'index'])->name('admin.about-sections.index');
    Route::get('/admin/about-sections/create', [AdminAboutSectionController::class, 'create'])->name('admin.about-sections.create');
    Route::post('/admin/about-sections', [AdminAboutSectionController::class, 'store'])->name('admin.about-sections.store');
    Route::get('/admin/about-sections/{section}/edit', [AdminAboutSectionController::class, 'edit'])->name('admin.about-sections.edit');
    Route::put('/admin/about-sections/{section}', [AdminAboutSectionController::class, 'update'])->name('admin.about-sections.update');
    Route::delete('/admin/about-sections/{section}', [AdminAboutSectionController::class, 'destroy'])->name('admin.about-sections.destroy');

    Route::get('/admin/about', [AdminSiteController::class, 'edit'])->name('admin.site.edit');
    Route::put('/admin/about', [AdminSiteController::class, 'update'])->name('admin.site.update');
    }
);



