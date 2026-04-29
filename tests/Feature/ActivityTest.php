<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function unauthenticated_user_cannot_access_activities()
    {
        $response = $this->get('/activities');
        $response->assertRedirect('/login');
    }

    /** @test */
    public function authenticated_user_can_view_activities_page()
    {
        $response = $this->actingAs($this->user)->get('/activities');
        $response->assertStatus(200);
    }

    /** @test */
    public function authenticated_user_can_create_an_activity()
    {
        $response = $this->actingAs($this->user)->post('/activities', [
            'title' => 'Daily SMS count in comparison to SMS count from logs',
            'category' => 'sms',
            'description' => 'Check SMS count against logs',
            'activity_date' => today()->toDateString(),
        ]);

        $response->assertRedirect('/activities');
        $this->assertDatabaseHas('activities', [
            'title' => 'Daily SMS count in comparison to SMS count from logs',
            'category' => 'sms',
        ]);
    }

    /** @test */
    public function activity_requires_a_title()
    {
        $response = $this->actingAs($this->user)->post('/activities', [
            'title' => '',
            'category' => 'sms',
            'activity_date' => today()->toDateString(),
        ]);

        $response->assertSessionHasErrors('title');
    }

    /** @test */
    public function activity_requires_a_category()
    {
        $response = $this->actingAs($this->user)->post('/activities', [
            'title' => 'Test Activity',
            'category' => '',
            'activity_date' => today()->toDateString(),
        ]);

        $response->assertSessionHasErrors('category');
    }

    /** @test */
    public function authenticated_user_can_view_activity_details()
    {
        $activity = Activity::factory()->create();

        $response = $this->actingAs($this->user)->get("/activities/{$activity->id}");
        $response->assertStatus(200);
    }

    /** @test */
    public function authenticated_user_can_update_activity_status()
    {
        $activity = Activity::factory()->create();

        $response = $this->actingAs($this->user)->post("/activities/{$activity->id}/updates", [
            'status' => 'done',
            'remark' => 'Completed successfully',
        ]);

        $response->assertRedirect("/activities/{$activity->id}");
        $this->assertDatabaseHas('activity_updates', [
            'activity_id' => $activity->id,
            'user_id' => $this->user->id,
            'status' => 'done',
            'remark' => 'Completed successfully',
        ]);
    }

    /** @test */
    public function authenticated_user_can_view_reports_page()
    {
        $response = $this->actingAs($this->user)->get('/activities/report');
        $response->assertStatus(200);
    }
}
