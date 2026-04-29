<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index()
    {
        $activities = Activity::with('updates.user')
            ->whereDate('activity_date', today())
            ->get();

        return view('activities.index', compact('activities'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string',
            'activity_date' => 'required|date',
        ]);

        Activity::create($request->all());

        return redirect()->route('activities.index')
            ->with('success', 'Activity created successfully.');
    }

    public function show(Activity $activity)
    {
        $activity->load('updates.user');

        return view('activities.show', compact('activity'));
    }

    public function report(Request $request)
    {
        $from = $request->from ?? now()->startOfMonth()->toDateString();
        $to = $request->to ?? now()->toDateString();

        $activities = Activity::with('updates.user')
            ->whereBetween('activity_date', [$from, $to])
            ->get();

        return view('activities.report', compact('activities', 'from', 'to'));
    }
}
