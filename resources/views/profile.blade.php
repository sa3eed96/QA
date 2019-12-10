@extends('layouts.app')

@section('content')
    <profile :questions="{{$questions}}" :user="{{$user}}"></profile>
@endsection