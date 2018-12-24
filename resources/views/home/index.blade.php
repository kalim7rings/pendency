@extends('layouts.app', ['title'=>'Document Email'])
@section('content')
    <home-component :user="{{ json_encode($user) }}"></home-component>
@endsection