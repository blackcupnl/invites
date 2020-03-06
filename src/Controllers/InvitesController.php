<?php

namespace BlackCup\Invites\Controllers;

use Illuminate\Routing\Controller;
use BlackCup\Invites\Models\Invite;
use BlackCup\Invites\Facades\Invites;
use Illuminate\Auth\Middleware\Authenticate;

class InvitesController extends Controller
{
    /**
     * Create a new InvitesController.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($request->invite->payload->requiresAuthentication($request->action)) {
                return app(Authenticate::class)->handle($request, $next);
            }

            return $next($request);
        })->only('action', 'execute');

        $this->middleware(function ($request, $next) {
            if ($request->invite->status != Invite::OPEN) {
                return redirect()->route('invites.completed', $request->invite);
            }

            return $next($request);
        })->except('completed');
    }

    /**
     * Displays the specified invite.
     *
     * @param  Invite $invite
     * @return \Illuminate\Http\Response
     */
    public function show(Invite $invite)
    {
        return view('invites::show')->with(['invite' => $invite]);
    }

    /**
     * Displays the action page for the specified invite.
     *
     * @param  Invite $invite
     * @return \Illuminate\Http\Response
     */
    public function action(Invite $invite, string $action)
    {
        return view('invites::action')->with(['invite' => $invite, 'action' => $action]);
    }

    /**
     * Executes the action.
     *
     * @param  Invite $invite
     * @param string $action (accept|reject)
     * @return \Illuminate\Http\Response
     */
    public function execute(Invite $invite, string $action)
    {
        Invites::{$action}($invite);

        return redirect()->route('invites.completed', $invite);
    }

    /**
     * Displays the completed page for the specified invite.
     *
     * @param  Invite $invite
     * @return \Illuminate\Http\Response
     */
    public function completed(Invite $invite)
    {
        if ($invite->status == Invite::OPEN) {
            return redirect()->route('invites.show', $invite);
        }

        return view('invites::completed')->with(['invite' => $invite]);
    }
}
