@extends('invites::layout')

@section('content')

<h1>@lang('invites::lang.invite_received')</h1>

<p>@lang('invites::lang.invite_received_long', ['name' => $invite->from_name])</p>

<p><i>{{ $invite->message }}</i></p>

@if ($invite->payload->description)
    <p>{{ $invite->payload->description }}</p>
@endif

<p>
    <a class="btn btn-success" href="{{ route('invites.action', [$invite, 'accept']) }}">@lang('invites::lang.accept')</a>
    <a class="btn btn-danger" href="{{ route('invites.action', [$invite, 'reject']) }}">@lang('invites::lang.reject')</a>
</p>

@endsection
