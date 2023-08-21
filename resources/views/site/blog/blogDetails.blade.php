@extends('site.common.main')

@section('customHeader')
  
@endsection

@section('siteTitle', 'PeaceWorc | Read Blog')

@section('main')

    <section class="header">
        <p class="header_title mt-4 p-5">{{$blog_details->title}}</p>
    </section>

    <div class="container main mt-5" id="blogDetails">
        <img src="{{$blog_details->image}}"
            width="100%" alt="">

        <div class="details">
            <p class="my-4 fw-bold">Posted : {{$blog_details->created_at->format('M d, Y')}} {{$blog_details->created_at->diffForHumans()}}</p>
            <p>{{$blog_details->content}}</p>
        </div>
    </div>
@endsection

@section('customJs')
@endsection
