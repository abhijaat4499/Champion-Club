<?php

namespace App\Support;

use App\Models\Offer;
use App\Models\Plan;
use App\Models\Service;
use App\Models\Trainer;
use Illuminate\Support\Str;

class Enquiry
{
    const TYPES = ['plan', 'offer', 'trainer', 'service'];

    public static function find(?string $type, $id): ?array
    {
        if (! in_array($type, self::TYPES, true) || ! ctype_digit((string) $id)) {
            return null;
        }

        $model = match ($type) {
            'plan' => Plan::find($id),
            'offer' => Offer::visible()->find($id),
            'trainer' => Trainer::find($id),
            'service' => Service::where('is_active', true)->find($id),
        };

        return $model ? self::for($model) : null;
    }

    public static function for(Plan|Offer|Trainer|Service $model): array
    {
        return match (true) {
            $model instanceof Plan => self::plan($model),
            $model instanceof Offer => self::offer($model),
            $model instanceof Trainer => self::trainer($model),
            $model instanceof Service => self::service($model),
        };
    }

    public static function readable(string $text): string
    {
        return mb_strtoupper($text) === $text ? Str::title(mb_strtolower($text)) : $text;
    }

    public static function trainerName(Trainer $trainer): string
    {
        return Str::title($trainer->name);
    }

    private static function plan(Plan $plan): array
    {
        $name = Str::ucfirst($plan->name);
        $price = '₹' . number_format($plan->price) . $plan->period;

        return [
            'type' => 'plan',
            'id' => $plan->id,
            'interest' => 'membership',
            'subject' => "{$name} membership ({$price})",
            'crumb' => ['label' => 'Membership', 'url' => route('gym.membership')],
            'eyebrow' => 'Membership',
            'title' => "Join the {$name} plan.",
            'lead' => 'Share your details and our team will call to activate your membership and fix your first session.',
            'image' => 'membership-bg',
            'card' => [
                'label' => $plan->is_featured ? 'Most popular' : 'Your plan',
                'name' => $name,
                'price' => '₹' . number_format($plan->price),
                'period' => $plan->period,
                'original' => null,
                'points' => $plan->featureList(),
                'note' => 'No lock-in contracts. Pause or cancel any time.',
            ],
            'formTitle' => "Get the {$name} membership",
            'formText' => "We'll call you to confirm the plan and your start date.",
            'button' => 'Request this membership',
            'whatsapp' => "Hi, I want to join the {$name} membership ({$price})",
            'pageTitle' => "Join the {$name} Membership",
            'steps' => [
                ['title' => 'Request', 'text' => 'Send the form, call us, or message us on WhatsApp.'],
                ['title' => 'Confirm', 'text' => 'We call to confirm your plan, start date and timings.'],
                ['title' => 'Start training', 'text' => 'Walk in, meet your coach and begin your first session.'],
            ],
        ];
    }

    private static function offer(Offer $offer): array
    {
        $name = self::readable($offer->title);
        $price = '₹' . number_format($offer->price);
        $daysLeft = $offer->daysLeft();
        $deadline = 'Valid till ' . $offer->valid_until->format('d M Y') . ' · ' . match (true) {
            $daysLeft <= 0 => 'Last day today',
            $daysLeft === 1 => '1 day left',
            default => $daysLeft . ' days left',
        };

        return [
            'type' => 'offer',
            'id' => $offer->id,
            'interest' => 'offer',
            'subject' => $offer->event === 'other' ? "{$name} ({$price})" : "{$name} — {$offer->greeting()} ({$price})",
            'crumb' => ['label' => 'Offers', 'url' => route('gym.offers')],
            'eyebrow' => $offer->greeting(),
            'title' => "Claim the {$name}.",
            'lead' => 'Lock in this offer price before the last date. Share your details and our team will call to reserve it for you.',
            'image' => 'page-offers',
            'card' => [
                'label' => $offer->greeting(),
                'name' => $name,
                'price' => $price,
                'period' => null,
                'original' => $offer->discountPercent() ? '₹' . number_format($offer->original_price) : null,
                'discount' => $offer->discountPercent(),
                'points' => $offer->descriptionLines(),
                'note' => $deadline,
            ],
            'formTitle' => 'Claim this offer',
            'formText' => "We'll call you to reserve the offer price for you.",
            'button' => 'Claim this offer',
            'whatsapp' => "Hi, I want to claim the {$name} offer ({$price})",
            'pageTitle' => "Claim the {$name}",
            'steps' => [
                ['title' => 'Claim', 'text' => 'Send the form before the last date, or message us on WhatsApp.'],
                ['title' => 'Confirm', 'text' => 'We call to confirm the offer and reserve your price.'],
                ['title' => 'Start training', 'text' => 'Walk in, meet your coach and start at the offer price.'],
            ],
        ];
    }

    private static function trainer(Trainer $trainer): array
    {
        $name = self::trainerName($trainer);

        return [
            'type' => 'trainer',
            'id' => $trainer->id,
            'interest' => 'coaching',
            'subject' => "Personal coaching with {$name}",
            'crumb' => ['label' => 'Trainers', 'url' => route('gym.trainers')],
            'eyebrow' => 'Personal coaching',
            'title' => "Personal coaching with {$name}.",
            'lead' => "Book a one-on-one slot with {$name}. Share your details and we'll call to fix a time that suits you.",
            'image' => 'page-trainers',
            'trainer' => [
                'name' => $name,
                'role' => Str::ucfirst($trainer->role),
                'bio' => $trainer->bio,
                'photo' => $trainer->photo ? asset('storage/' . $trainer->photo) : null,
                'initials' => $trainer->initials(),
            ],
            'formTitle' => "Book a slot with {$name}",
            'formText' => "We'll call you to fix your session time with {$name}.",
            'button' => 'Book my slot',
            'whatsapp' => "Hi, I want to book personal coaching with {$name}",
            'pageTitle' => "Personal Coaching with {$name}",
            'steps' => [
                ['title' => 'Book', 'text' => 'Send the form, call us, or message us on WhatsApp.'],
                ['title' => 'Fix a time', 'text' => "We call to fix a slot that suits you and {$name}."],
                ['title' => 'Train', 'text' => 'Meet your coach on the floor for your one-on-one session.'],
            ],
        ];
    }

    private static function service(Service $service): array
    {
        $title = $service->title;

        return [
            'type' => 'service',
            'id' => $service->id,
            'interest' => 'service',
            'subject' => "Service: {$title}",
            'crumb' => ['label' => 'Services', 'url' => route('gym.services')],
            'eyebrow' => 'Service',
            'title' => "Book {$title}.",
            'lead' => "Share your details and our team will call you about {$title} and fix a time for your first session.",
            'image' => Service::FALLBACK_IMAGE,
            'bgUrl' => $service->imageUrl(),
            'bgFocus' => $service->heroFocus(),
            'card' => [
                'label' => 'Service',
                'name' => $title,
                'summary' => $service->summary,
                'price' => null,
                'period' => null,
                'original' => null,
                'points' => $service->highlightList(),
                'note' => null,
                'url' => route('gym.service', $service),
            ],
            'formTitle' => "Book {$title}",
            'formText' => "We'll call you to fix a time for {$title}.",
            'button' => 'Book this service',
            'whatsapp' => "Hi, I want to book {$title}",
            'pageTitle' => "Book {$title}",
            'steps' => [
                ['title' => 'Book', 'text' => 'Send the form, call us, or message us on WhatsApp.'],
                ['title' => 'Fix a time', 'text' => "We call to fix a time for your first {$title} session."],
                ['title' => 'Train', 'text' => 'Walk in, meet your coach and start training.'],
            ],
        ];
    }
}
