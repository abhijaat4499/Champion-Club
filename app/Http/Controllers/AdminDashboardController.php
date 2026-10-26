<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AdminDashboardController extends Controller
{
    const DISPLAY_TIMEZONE = 'Asia/Kolkata';

    const PER_PAGE = 25;

    const SORT_OPTIONS = [
        'newest' => 'Newest first',
        'oldest' => 'Oldest first',
        'name_asc' => 'Name (A–Z)',
        'name_desc' => 'Name (Z–A)',
        'interest' => 'Interest / plan',
    ];

    public function index(Request $request)
    {
        $filters = $request->validate([
            'from' => 'nullable|date',
            'to' => 'nullable|date|after_or_equal:from',
            'sort' => 'nullable|in:' . implode(',', array_keys(self::SORT_OPTIONS)),
        ]);

        $from = $filters['from'] ?? null;
        $to = $filters['to'] ?? null;
        $sort = $filters['sort'] ?? 'newest';

        $query = Contact::query();

        if ($from) {
            $query->where('created_at', '>=', Carbon::parse($from, self::DISPLAY_TIMEZONE)->startOfDay()->utc());
        }

        if ($to) {
            $query->where('created_at', '<=', Carbon::parse($to, self::DISPLAY_TIMEZONE)->endOfDay()->utc());
        }

        match ($sort) {
            'oldest' => $query->oldest(),
            'name_asc' => $query->orderBy('name'),
            'name_desc' => $query->orderByDesc('name'),
            'interest' => $query->orderByRaw('interest IS NULL')->orderBy('interest')->latest(),
            default => $query->latest(),
        };

        $enquiries = $query->paginate(self::PER_PAGE)->withQueryString();
        $sortOptions = self::SORT_OPTIONS;
        $timezone = self::DISPLAY_TIMEZONE;

        return view('admin.dashboard', compact('enquiries', 'from', 'to', 'sort', 'sortOptions', 'timezone'));
    }
}
