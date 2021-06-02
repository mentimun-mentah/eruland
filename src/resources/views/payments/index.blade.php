@extends('layouts.app')
@section('content')
<payment :home="{{json_encode(url('/'))}}" :storage="{{json_encode(asset('storage/products/'))}}"></payment>
@endsection
