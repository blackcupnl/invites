<?php

namespace BlackCup\Invites\Controllers;

use Illuminate\Routing\Controller;
use BlackCup\Invites\Models\Invite;
use BlackCup\Invites\Facades\Invites;
use Illuminate\Auth\Middleware\Authenticate;

class InviteController extends Controller
{
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
                return redirect()->route('invite.completed', $request->invite);
            }

            return $next($request);
        })->except('completed');
    }

    public function show(Invite $invite)
    {
        return view('invites::show')->with(['invite' => $invite]);
    }

    public function action(Invite $invite, string $action)
    {
        return view('invites::action')->with(['invite' => $invite, 'action' => $action]);
    }

    public function execute(Invite $invite, string $action)
    {
        Invites::{$action}($invite);

        return redirect()->route('invite.completed', $invite);
    }

    public function completed(Invite $invite)
    {
        if ($invite->status == Invite::OPEN) {
            return redirect()->route('invite.show', $invite);
        }

        return view('invites::completed')->with(['invite' => $invite]);
    }
}
