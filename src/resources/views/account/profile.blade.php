@extends('account.sidenav')
@section('sidenav')
<account-profile :home="{{json_encode(url('/'))}}" :storage="{{json_encode(asset('storage/'))}}"></account-profile>
@endsection
