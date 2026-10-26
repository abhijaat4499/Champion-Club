<?php

namespace App\Http\Controllers;

use App\Models\AboutSection;
use App\Models\Service;
use App\Support\ImageUpload;
use App\Support\SiteSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminSiteController extends Controller
{
    public function edit()
    {
        return view('admin.site.edit', [
            'days' => SiteSettings::DAYS,
            'icons' => Service::ICONS + ['calendar' => 'Calendar', 'clock' => 'Clock', 'star' => 'Star'],
            'story' => config('gym.about_story') ?: SiteSettings::defaultStory(),
            'aboutSections' => AboutSection::orderBy('display_order')->orderBy('id')->get(),
        ]);
    }

    public function update(Request $request)
    {
        $request->merge([
            'hours' => array_values(array_filter((array) $request->input('hours', []), fn ($row) => filled($row['opens'] ?? null) || filled($row['closes'] ?? null))),
        ]);

        $days = implode(',', array_keys(SiteSettings::DAYS));
        $icons = implode(',', array_keys(Service::ICONS + ['calendar' => '', 'clock' => '', 'star' => '']));

        $data = $request->validate([
            'phone' => 'required|digits:10|starts_with:6,7,8,9',
            'whatsapp' => 'required|digits:10|starts_with:6,7,8,9',
            'email' => 'required|email|max:255',
            'whatsapp_message' => 'required|string|max:200',
            'address.street' => 'required|string|max:200',
            'address.locality' => 'required|string|max:80',
            'address.region' => 'required|string|max:80',
            'address.maps_url' => ['nullable', 'url', 'max:600', 'regex:~^https?://(www\.)?(google\.[a-z.]+/maps|maps\.google\.[a-z.]+|maps\.app\.goo\.gl|goo\.gl/maps)~i'],
            'hours' => 'required|array|min:1|max:7',
            'hours.*.from' => "required|in:{$days}",
            'hours.*.to' => "required|in:{$days}",
            'hours.*.opens' => 'required|date_format:H:i',
            'hours.*.closes' => 'required|date_format:H:i|after:hours.*.opens',
            'social.instagram' => 'nullable|url|max:255',
            'social.facebook' => 'nullable|url|max:255',
            'about_image_main' => ImageUpload::rule(),
            'about_image_main_mode' => 'nullable|in:keep,default,hide',
            'years_running' => 'required|integer|min:0|max:200',
            'about_title' => 'required|string|max:150',
            'about_story' => 'required|string|max:5000',
            'highlights' => 'required|array|size:4',
            'highlights.*.value' => 'required|integer|min:0|max:10000000',
            'highlights.*.suffix' => 'nullable|string|max:3',
            'highlights.*.label' => 'required|string|max:40',
            'highlights.*.icon' => "required|in:{$icons}",
        ], [
            'phone.digits' => 'Phone number must be a 10-digit mobile number.',
            'whatsapp.digits' => 'WhatsApp number must be a 10-digit mobile number.',
            'phone.starts_with' => 'Please enter a valid Indian mobile number.',
            'whatsapp.starts_with' => 'Please enter a valid Indian mobile number.',
            'hours.required' => 'Add at least one opening hours row.',
            'hours.*.closes.after' => 'Closing time must be after opening time.',
            'hours.*.opens.required' => 'Please enter the opening time.',
            'hours.*.closes.required' => 'Please enter the closing time.',
            'address.maps_url.url' => 'Please paste the full Google Maps link, starting with https://',
            'address.maps_url.regex' => 'Please paste a Google Maps link (google.com/maps or maps.app.goo.gl).',
        ]);

        $data['hours'] = array_map(fn ($row) => SiteSettings::hoursRow($row['from'], $row['to'], $row['opens'], $row['closes']), $data['hours']);
        $data['highlights'] = array_map(fn ($row) => [
            'value' => (int) $row['value'],
            'suffix' => (string) ($row['suffix'] ?? ''),
            'label' => $row['label'],
            'icon' => $row['icon'],
        ], $data['highlights']);
        $data['social'] = [
            'instagram' => $data['social']['instagram'] ?? null,
            'facebook' => $data['social']['facebook'] ?? null,
            'youtube' => config('gym.social.youtube'),
        ];
        $data['address']['country'] = config('gym.address.country', 'IN');
        $data['address']['maps_url'] = trim((string) ($data['address']['maps_url'] ?? '')) ?: null;
        $data['years_running'] = (int) $data['years_running'];
        $data['about_images'] = $this->aboutImages($request);

        SiteSettings::save($data);

        return redirect()->route('admin.site.edit')->with('success', 'Website details updated.');
    }

    private function aboutImages(Request $request): array
    {
        $images = [];

        foreach (array_keys(SiteSettings::ABOUT_IMAGE_DEFAULTS) as $slot) {
            $current = config("gym.about_images.{$slot}");
            $uploaded = $current && $current !== 'none' ? $current : null;
            $mode = $request->input("about_image_{$slot}_mode", 'keep');

            if ($request->hasFile("about_image_{$slot}")) {
                $value = ImageUpload::store($request->file("about_image_{$slot}"), 'about', 1200)['path'];
            } elseif ($mode === 'default') {
                $value = null;
            } elseif ($mode === 'hide') {
                $value = 'none';
            } else {
                $value = $current;
            }

            if ($uploaded && $uploaded !== $value) {
                Storage::disk('public')->delete($uploaded);
            }

            $images[$slot] = $value;
        }

        return $images;
    }
}
