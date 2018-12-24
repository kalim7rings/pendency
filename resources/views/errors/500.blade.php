@extends('layouts.app', ['title'=>'Something went wrong'])

@section('content')
    <div class="container-fluid main_content">
        <div class="row">
            <div class="col-12 col-lg-12 col-md-9 main_right_box" style="height: 75vh;">
                <div class="card_inner">
                    <h3 class="text-center mt-5 mb-5" style="padding-top: 100px;">
                        {!! $message ?? 'Something went wrong.' !!}
                    </h3>
                </div>
            </div>
        </div>
    </div>
@endsection