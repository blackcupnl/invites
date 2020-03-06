<?php

namespace BlackCup\Invites\Tests\Unit;

use BlackCup\Invites\Models\Invite;
use BlackCup\Invites\Tests\TestCase;
use BlackCup\Invites\Tests\DummyAuthInvite;
use Illuminate\Auth\AuthenticationException;

class ControllerTest extends TestCase
{
    private $invite;

    protected function setUp(): void
    {
        parent::setUp();

        $this->invite = factory(Invite::class)->create();
    }

    public function test_routes_show_page()
    {
        $this->get(route('invites.show', $this->invite))->assertStatus(200);
        $this->get(route('invites.action', [$this->invite, 'accept']))->assertStatus(200);

        $this->invite->accept()->save();
        $this->get(route('invites.completed', $this->invite))->assertStatus(200);
    }

    public function test_routes_show_error_if_invite_is_not_found()
    {
        $this->invite->delete();
        $this->get(route('invites.show', $this->invite))->assertStatus(404);
        $this->get(route('invites.action', [$this->invite, 'accept']))->assertStatus(404);
        $this->get(route('invites.completed', $this->invite))->assertStatus(404);
    }

    public function test_routes_redirect_if_invite_is_completed()
    {
        $this->invite->accept()->save();
        $this->get(route('invites.show', $this->invite))->assertRedirect(route('invites.completed', $this->invite));
        $this->get(route('invites.action', [$this->invite, 'accept']))->assertRedirect(route('invites.completed', $this->invite));
        $this->post(route('invites.execute', [$this->invite, 'accept']))->assertRedirect(route('invites.completed', $this->invite));
    }

    public function test_completed_redirects_if_invite_is_not_completed()
    {
        $this->get(route('invites.completed', $this->invite))->assertRedirect(route('invites.show', $this->invite));
    }

    public function test_execute_redirects_to_complete()
    {
        $this->post(route('invites.execute', [$this->invite, 'accept']))->assertRedirect(route('invites.completed', $this->invite));
        $this->invite->accept()->save();
        $this->post(route('invites.execute', [$this->invite, 'accept']))->assertRedirect(route('invites.completed', $this->invite));
    }

    public function test_action_with_authentication_throws_authentication_exception()
    {
        $invite = factory(Invite::class)->create(['payload' => new DummyAuthInvite()]);

        $this->withoutExceptionHandling()->expectException(AuthenticationException::class);
        $this->get(route('invites.action', [$invite, 'accept']));
    }

    public function test_execute_with_authentication_throws_authentication_exception()
    {
        $invite = factory(Invite::class)->create(['payload' => new DummyAuthInvite()]);

        $this->withoutExceptionHandling()->expectException(AuthenticationException::class);
        $this->post(route('invites.execute', [$invite, 'accept']));
    }
}
