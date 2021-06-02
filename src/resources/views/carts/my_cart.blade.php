@extends('layouts.app')
@section('content')
<my-cart :home="{{json_encode(url('/'))}}" :storage="{{json_encode(asset('storage/'))}}"></my-cart>
@endsection
