@extends('admin.app')
@section('content')
<transaction :storage="{{json_encode(asset('storage/'))}}"></transaction>
@endsection
