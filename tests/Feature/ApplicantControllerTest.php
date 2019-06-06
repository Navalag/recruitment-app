<?php

namespace Tests\Feature;

use App\Applicant;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApplicantControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guests_may_not_create_applicant()
    {
        $this->get('/applicant/create')
            ->assertRedirect('/login');

        $this->post('/applicant')
            ->assertRedirect('/login');
    }

    /** @test */
    public function an_admin_can_create_new_applicant()
    {
        $this->signIn();

        $applicant = make('App\Applicant');

        $response = $this->post('/applicant', $applicant->toArray());

        $this->get($response->headers->get('Location'))
            ->assertSee($applicant->first_name)
            ->assertSee($applicant->last_name)
            ->assertSee($applicant->email);
    }

    /** @test */
    public function guests_may_not_edit_applicant()
    {
        $applicant = create('App\Applicant');

        $this->get($applicant->path() . '/edit')
             ->assertRedirect('/login');

        $this->patch($applicant->path(), $applicant->toArray())
             ->assertRedirect('/login');
    }

    /** @test */
    public function an_admin_can_edit_an_applicant()
    {
        $this->signIn();

        $applicant = create('App\Applicant');

        $applicant->first_name = 'Foo';
        $applicant->last_name = 'Bar';

        $this->patch($applicant->path(), $applicant->toArray());

        $this->get('/applicant')
            ->assertSee($applicant->first_name)
            ->assertSee($applicant->last_name)
            ->assertSee($applicant->email);
    }

    /** @test */
    public function unauthorized_users_may_not_delete_applicants()
    {
        $applicant = create('App\Applicant');

        $this->delete($applicant->path())->assertRedirect('/login');

        $this->signIn();
        $this->delete($applicant->path())->assertStatus(302); // TODO: check if it should be code 403
    }

    /** @test */
    public function authorized_users_can_delete_applicant()
    {
        $this->signIn();

        $applicant = create('App\Applicant');

        $response = $this->json('DELETE', $applicant->path());

        $response->assertStatus(204);

        $this->assertDatabaseMissing('applicants', ['id' => $applicant->id]);
    }

    /** @test */
    public function an_admin_can_send_email()
    {
        $this->signIn();

        $applicant = create('App\Applicant');

        $response = $this->json('GET', $applicant->path() . '/send-email');

        $response->assertStatus(200);

        $this->assertDatabaseHas('applicants', ['status' => 'email sent']);
    }

    /** @test */
    public function an_admin_may_not_send_email_twice()
    {
        $this->signIn();

        $applicant = create('App\Applicant');

        $this->json('GET', $applicant->path() . '/send-email');
        $response = $this->json('GET', $applicant->path() . '/send-email');

        $response->assertSessionHas('flash_message', 'Email has already been sent.')
                 ->assertStatus(302);
    }

    /** @test */
    public function unauthorized_users_may_not_send_email()
    {
        $applicant = create('App\Applicant');

        $this->get($applicant->path() . '/send-email')->assertRedirect('/login');
    }

    /** @test */
    public function an_applicant_requires_first_name()
    {
        $this->createApplicant(['first_name' => null])
             ->assertSessionHasErrors('first_name');
    }

    /** @test */
    public function an_applicant_requires_last_name()
    {
        $this->createApplicant(['last_name' => null])
             ->assertSessionHasErrors('last_name');
    }

    /** @test */
    public function an_applicant_requires_email()
    {
        $this->createApplicant(['email' => null])
             ->assertSessionHasErrors('email');
    }

    /** @test */
    public function an_applicant_requires_vacancy_id()
    {
        $this->createApplicant(['vacancy_id' => null])
             ->assertSessionHasErrors('vacancy_id');
    }

    public function createApplicant($overrides = [])
    {
        $this->signIn();

        $applicant = make('App\Applicant', $overrides);

        return $this->post('applicant', $applicant->toArray());
    }
}
