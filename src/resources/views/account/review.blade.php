@extends('account.sidenav')
@section('sidenav')
<account-review :storage="{{json_encode(asset('storage/products/'))}}"></account-review>
@endsection
