@extends('invites::layout')

@section('content')
<h1>@lang('invites::lang.invite_'.$invite->status)</h1>
@endsection
