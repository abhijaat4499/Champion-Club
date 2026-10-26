<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use Illuminate\Http\Request;

class AdminOfferController extends Controller
{
    public function index()
    {
        $offers = Offer::orderBy('display_order')->orderBy('valid_until')->get();

        return view('admin.offers.index', compact('offers'));
    }

    public function create()
    {
        return view('admin.offers.create', ['events' => Offer::EVENTS, 'newYearRange' => Offer::newYearRange()]);
    }

    public function store(Request $request)
    {
        Offer::create($this->validated($request, Offer::newYearRange(), 'after_or_equal:' . Offer::today()->toDateString()));

        return redirect()->route('admin.offers.index')->with('success', 'Offer created.');
    }

    public function edit(Offer $offer)
    {
        return view('admin.offers.edit', ['offer' => $offer, 'events' => Offer::EVENTS, 'newYearRange' => Offer::newYearRange($offer->new_year)]);
    }

    public function update(Request $request, Offer $offer)
    {
        $offer->update($this->validated($request, Offer::newYearRange($offer->new_year)));

        return redirect()->route('admin.offers.index')->with('success', 'Offer updated.');
    }

    public function destroy(Offer $offer)
    {
        $offer->delete();

        return redirect()->route('admin.offers.index')->with('success', 'Offer deleted.');
    }

    private function validated(Request $request, array $newYearRange, ?string $dateRule = null): array
    {
        [$minYear, $maxYear] = $newYearRange;

        $validated = $request->validate([
            'title' => 'required|string|max:120',
            'event' => 'required|in:' . implode(',', array_keys(Offer::EVENTS)),
            'new_year' => "nullable|required_if:event,new_year|integer|between:{$minYear},{$maxYear}",
            'price' => 'required|integer|min:0',
            'original_price' => 'nullable|integer|gt:price',
            'starts_on' => 'required|date',
            'valid_until' => array_filter(['required', 'date', 'after_or_equal:starts_on', $dateRule]),
            'description' => 'required|string|max:1000',
            'display_order' => 'required|integer|min:0',
        ], [
            'original_price.gt' => 'Original price must be greater than the offer price.',
            'starts_on.required' => 'Please choose the date the offer starts.',
            'valid_until.after_or_equal' => 'Last date must be on or after the start date, and not in the past.',
            'new_year.required_if' => 'Please enter the New Year, e.g. ' . Offer::suggestedNewYear() . '.',
            'new_year.integer' => 'Please enter a year in numbers, e.g. ' . Offer::suggestedNewYear() . '.',
            'new_year.between' => "The New Year must be between {$minYear} and {$maxYear}.",
        ]);

        if ($validated['event'] !== 'new_year') {
            $validated['new_year'] = null;
        }

        $validated['is_active'] = $request->boolean('is_active');

        return $validated;
    }
}
