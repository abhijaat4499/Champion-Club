<?php

namespace App\Models;

use App\Support\GymContact;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;

class HeroSlide extends Model
{
    const PATTERNS = [
        'spotlight' => ['label' => 'Spotlight', 'hint' => 'Big heading on the left, straight over the photo.'],
        'card' => ['label' => 'Glass card', 'hint' => 'Text inside a frosted card on the right side.'],
        'center' => ['label' => 'Centered', 'hint' => 'Heading and buttons in the middle of the photo.'],
    ];

    const POINT_STYLES = [
        'none' => 'No points',
        'capsules' => 'Capsules',
        'checks' => 'Check points',
    ];

    const POSITIONS = [
        'center' => ['label' => 'Center', 'css' => '50% 50%'],
        'top' => ['label' => 'Top', 'css' => '50% 20%'],
        'bottom' => ['label' => 'Bottom', 'css' => '50% 80%'],
        'left' => ['label' => 'Left', 'css' => '25% 50%'],
        'right' => ['label' => 'Right', 'css' => '75% 50%'],
    ];

    const TARGETS = [
        'trial' => 'Free trial page',
        'whatsapp' => 'WhatsApp chat',
        'call' => 'Call the gym',
        'services' => 'Services page',
        'membership' => 'Membership page',
        'offers' => 'Offers page',
        'trainers' => 'Trainers page',
        'facilities' => 'Facilities page',
        'gallery' => 'Gallery page',
        'about' => 'About page',
        'contact' => 'Contact page',
        'directions' => 'Google Maps directions',
    ];

    protected $fillable = [
        'tab', 'label', 'title', 'highlight', 'description', 'pattern', 'points_style', 'points',
        'image', 'image_mobile', 'site_image', 'image_position',
        'button_text', 'button_target', 'button2_text', 'button2_target', 'is_active', 'display_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public static function defaults(): array
    {
        return [
            [
                'tab' => 'Strength',
                'label' => 'Strength & Conditioning · ' . config('gym.address.locality'),
                'title' => 'Every champion started as someone who refused to quit.',
                'highlight' => 'refused to quit.',
                'description' => "A strength and conditioning gym for people training for their next personal best — not someone else's idea of fit. Real coaching, real equipment, real results.",
                'pattern' => 'spotlight',
                'points_style' => 'none',
                'site_image' => 'hero-strength',
                'button_text' => 'Book a free trial',
                'button_target' => 'trial',
                'button2_text' => 'Chat with us',
                'button2_target' => 'whatsapp',
            ],
            [
                'tab' => 'Coaching',
                'label' => 'Personal coaching',
                'title' => 'Coached, not just supervised.',
                'description' => 'Every session is supervised by a certified trainer who corrects your form, not just your rep count.',
                'pattern' => 'card',
                'points_style' => 'checks',
                'points' => "Technique work every session\nProgress tracked every 4 weeks\nWeekly check-ins on personal plans",
                'site_image' => 'hero-coaching',
                'button_text' => 'Talk to a coach',
                'button_target' => 'whatsapp',
                'button2_text' => 'View services',
                'button2_target' => 'services',
            ],
            [
                'tab' => 'Facility',
                'label' => 'The training floor',
                'title' => '20,000 sq ft built for serious training.',
                'highlight' => '20,000 sq ft',
                'pattern' => 'center',
                'points_style' => 'capsules',
                'points' => "Competition-grade platforms\nFree weights\nLockers & showers",
                'site_image' => 'hero-facility',
                'button_text' => 'Explore facilities',
                'button_target' => 'facilities',
                'button2_text' => 'Visit us',
                'button2_target' => 'directions',
            ],
        ];
    }

    public static function forHome()
    {
        $slides = self::visible()->get();

        if ($slides->isNotEmpty()) {
            return $slides;
        }

        return collect(self::defaults())->map(fn ($data) => new self($data + ['is_active' => true]));
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('display_order')->orderBy('id');
    }

    public function patternName(): string
    {
        return array_key_exists($this->pattern, self::PATTERNS) ? $this->pattern : 'spotlight';
    }

    public function pointList(): array
    {
        if ($this->points_style === 'none') {
            return [];
        }

        return array_values(array_filter(array_map('trim', explode("\n", (string) $this->points))));
    }

    public function focus(): string
    {
        return 'object-position: ' . (self::POSITIONS[$this->image_position]['css'] ?? '50% 50%');
    }

    public function titleHtml(): HtmlString
    {
        $title = e($this->title);
        $highlight = trim((string) $this->highlight);

        if ($highlight !== '' && str_contains($this->title, $highlight)) {
            $escaped = e($highlight);
            $title = preg_replace('/' . preg_quote($escaped, '/') . '/', '<span class="text-gold-400">' . $escaped . '</span>', $title, 1);
        }

        return new HtmlString($title);
    }

    public function buttons(): array
    {
        $buttons = [];

        foreach ([['button_text', 'button_target'], ['button2_text', 'button2_target']] as [$text, $target]) {
            if ($this->{$text} && array_key_exists($this->{$target}, self::TARGETS)) {
                $buttons[] = ['text' => $this->{$text}, 'target' => $this->{$target}];
            }
        }

        return $buttons;
    }

    public static function targetUrl(string $target): string
    {
        $contact = new GymContact();

        return match ($target) {
            'whatsapp' => $contact->whatsappUrl(),
            'call' => $contact->callUrl(),
            'directions' => $contact->mapsUrl(),
            'trial' => route('gym.trial'),
            default => route('gym.' . $target),
        };
    }

    public static function targetIcon(string $target): ?string
    {
        return ['whatsapp' => 'whatsapp', 'call' => 'phone', 'directions' => 'pin'][$target] ?? null;
    }

    public static function isExternal(string $target): bool
    {
        return in_array($target, ['whatsapp', 'directions'], true);
    }
}
