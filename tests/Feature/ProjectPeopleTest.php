<?php

namespace Tests\Feature;

use App\Person;
use Facades\Tests\Setup\ProjectFactory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectPeopleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_cannot_add_tasks_to_projects()
    {
        $project = factory('App\Project')->create();

        $this->post($project->path() . '/people')->assertRedirect('login');
    }

    /** @test */
    public function only_the_owner_of_a_project_may_add_people()
    {
        $this->signIn();

        $project = factory('App\Project')->create();

        $attributes = [
            'name' => 'Doe',
            'firstname' => 'John'
        ];

        $this->post($project->path() . '/people', $attributes)
            ->assertStatus(403);

        $this->assertDatabaseMissing('people', $attributes);
    }

    /** @test */
    public function a_project_can_have_people()
    {
        $project = ProjectFactory::create();

        $attributes = [
            'name' => 'Doe',
            'firstname' => 'John'
        ];

        $response = $this->actingAs($project->owner)
            ->post($project->path() . '/people', $attributes);

        $person = Person::first();

        $response->assertRedirect($person->path());

        $this->get($project->path())
            ->assertSee($attributes['name'])
            ->assertSee($attributes['firstname']);
    }

    /** @test */
    public function only_the_owner_of_a_project_may_update_a_person()
    {
        $this->signIn();

        $project = ProjectFactory::withPeople(1)->create();

        $this->patch($project->people[0]->path(), $attributes = ['name' => 'Changed'])
            ->assertStatus(403);

        $this->assertDatabaseMissing('people', $attributes);
    }

    /** @test */
    function a_person_can_be_updated()
    {
        $project = ProjectFactory::withPeople(1)->create();

        $this->actingAs($project->owner)
            ->patch($project->people[0]->path(), [
                'name' => 'Changed',
                'firstname' => 'Changed'
            ]);

        $this->assertDatabaseHas('people', [
            'name' => 'Changed',
            'firstname' => 'Changed'
        ]);
    }

    /** @test */
    public function a_person_can_only_be_viewed_by_projects_owner_and_invited_users()
    {
        $project = ProjectFactory::ownedBy($john = factory('App\User')->create())
            ->withPeople(1)
            ->create();

        $sally = factory('App\User')->create();

        $this->actingAs($sally)
            ->get($project->people[0]->path())
            ->assertStatus(403);

        $project->invite($sally);

        $this->actingAs($sally)
            ->get($project->people[0]->path())
            ->assertStatus(200);
    }

    /** @test */
    function a_user_can_update_a_persons_notes()
    {
        $project = ProjectFactory::withPeople(1)->create();

        $this->actingAs($project->owner)
            ->patch($project->people[0]->path(), ['notes' => 'Changed']);

        $this->assertDatabaseHas('people', ['notes' => 'Changed']);
    }

    /** @test */
    function unauthorized_users_cannot_delete_people()
    {
        $person = factory('App\Person')->create();

        $this->delete($person->path())
            ->assertRedirect('/login');
    }

    /** @test */
    public function a_user_can_delete_a_person()
    {
        $project = ProjectFactory::withPeople(1)->create();

        $this->actingAs($project->owner)
            ->delete($project->people[0]->path())
            ->assertRedirect($project->path());

        $this->assertDatabaseMissing('people', $project->people[0]->only('id'));
    }

    /** @test */
    public function a_person_requires_a_name()
    {
        $project = ProjectFactory::create();

        $attributes = factory('App\Person')->raw(['name' => '']);

        $this->actingAs($project->owner)
            ->post($project->path() . '/people', $attributes)
            ->assertSessionHasErrors('name');
    }

    /** @test */
    public function a_person_requires_a_firstname()
    {
        $project = ProjectFactory::withPeople(1)->create();

        $attributes = factory('App\Person')->raw(['firstname' => '']);

        $this->actingAs($project->owner)
            ->post($project->path() . '/people', $attributes)
            ->assertSessionHasErrors('firstname');
    }
}
