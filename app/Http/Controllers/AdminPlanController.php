<?php


namespace App\Http\Controllers;
use App\Models\Plan;




use Illuminate\Http\Request;

class AdminPlanController extends Controller
{
      public function index()
    {
        $plans = Plan::orderBy('display_order')->get();

        return view('admin.plans.index', compact('plans'));
    }


    public function create()
{
    return view('admin.plans.create');
}

public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'price' => 'required|integer|min:0',
        'period' => 'required|string|max:50',
        'features' => 'required|string',
        'color' => 'required|in:bronze,gold,silver',
        'button_text' => 'required|string|max:50',
        'display_order' => 'required|integer',
    ]);

    $validated['is_featured'] = $request->has('is_featured');

    Plan::create($validated);

    return redirect()->route('admin.plans.index')->with('success', 'Plan created.');
}


public function edit(Plan $plan)
{
    return view('admin.plans.edit', compact('plan'));
}


public function update(Request $request, Plan $plan)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'price' => 'required|integer|min:0',
        'period' => 'required|string|max:50',
        'features' => 'required|string',
        'color' => 'required|in:bronze,gold,silver',
        'button_text' => 'required|string|max:50',
        'display_order' => 'required|integer',
    ]);

    $validated['is_featured'] = $request->has('is_featured');

    $plan->update($validated);

    return redirect()->route('admin.plans.index')->with('success', 'Plan updated.');
}

 public function destroy( Plan $plan)
{
   $plan->delete();
    return redirect()->route('admin.plans.index')->with('success', 'Plan deleted.');
}

}
