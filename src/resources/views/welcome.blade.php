@extends('layouts.app')
@section('content')
<home :query="{{$query}}" :storage="{{json_encode(asset('storage/'))}}" :home="{{json_encode(url('/'))}}"></home>
@endsection
