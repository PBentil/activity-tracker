<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityUpdate;
use Illuminate\Http\Request;

class ActivityUpdateController extends Controller
{
    public function store(Request $request, Activity $activity)
    {
        $request->validate([
            'status' => 'required|in:done,pending',
            'remark' => 'nullable|string',
        ]);

        ActivityUpdate::create([
            'activity_id' => $activity->id,
            'user_id' => auth()->id(),
            'status' => $request->status,
            'remark' => $request->remark,
            'updated_at_time' => now(),
        ]);

        return redirect()->route('activities.show', $activity)
            ->with('success', 'Activity updated successfully.');
    }

    public function destroy(ActivityUpdate $activityUpdate)
    {
        $activityUpdate->delete();

        return redirect()->back()
            ->with('success', 'Update deleted successfully.');
    }
}
