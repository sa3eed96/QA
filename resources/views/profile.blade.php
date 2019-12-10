@extends('layouts.app')

@section('content')
    <profile :questions="{{$questions}}" :user="{{$user}}" :answers="{{$answers}}"></profile>
@endsection