@extends('frontend.frontend-master')

@section('content')

    <section class="breadcrumb-area breadcrumb-bg "
       {!! render_background_image_markup_by_attachment_id(get_static_option('site_breadcrumb_bg')) !!}
    >
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-inner">
                        <h1 class="page-title">@yield('page-title')</h1>
                        <ul class="page-list">
                            <li><a href="{{url('/')}}">{{__('Home')}}</a></li>
                            <li>@yield('page-title')</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
