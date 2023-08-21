@extends('site.common.main')

@section('customHeader')
@endsection

@section('siteTitle', 'PeaceWorc | Blogs')

@section('main')
<section class="header">
    <p class="header_title">Blogs</p>
</section>

    <div class="container main mt-5">
        <div class="row">
            @forelse ($blogs as $item)
                <div class="col-sm-4 mb-4">
                    <div class="card shadow-sm">
                        <img src="{{asset($item->image)}}" alt="blog image" style="height:275px; width:100%; object-fit:cover;">
                        <div class="card-body">
                            <p class="card-text fs-5">{{Str::of($item->title)->limit(30)}}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-secondary shadow-none"
                                        onclick="location.href='{{ route('site.blog', ['id' => Crypt::encrypt($item->id)]) }}';">View</button>
                                </div>
                                <small class="text-muted">{{$item->created_at->diffForHumans()}}</small>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col">
                    <div class="text-center">
                        <h3 class="text-muted">No Blogs To Show.</h3>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection

@section('customJs')
@endsection
