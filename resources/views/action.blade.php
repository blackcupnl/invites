@extends('invites::layout')

@section('content')
<form method="POST"> @csrf

    <h1>@lang('invites::lang.invite_'.$action)</h1>

    <p>@lang('invites::lang.confirm_'.$action)</p>

    <button class="btn btn-success" type="submit">@lang('invites::lang.yes')</button>
    <a class="btn btn-danger" href="{{ route('invite.show', $invite) }}">@lang('invites::lang.no')</a>

</form>
@endsection
