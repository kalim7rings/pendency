@extends('layouts.app', ['title'=>'Document Email'])
@section('content')
    <dashboard-component :document-information="{{ json_encode($data) }}"></dashboard-component>
@endsection