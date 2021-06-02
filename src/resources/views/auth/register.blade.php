@extends('layouts.app')
@section('content')
<register :home="{{json_encode(url('/'))}}"></register>
@endsection
