<div class="single-event-slider-wrap">
    <div class="events-single-item bg-image margin-bottom-30"
         style="background-image: url({{asset('assets/frontend/img/bg/event-item-bg.png')}})"
    >
        <div class="thumb">
            <div class="bg-image"  {!! render_background_image_markup_by_attachment_id($image) !!}>
                <div class="post-time">
                    <h6 class="title">{{date('d',strtotime($date))}}</h6>
                    <span>{{date('M',strtotime($date))}}</span>
                </div>
            </div>
        </div>
        <div class="content-wrapper">
            <div class="content">
                <h2 class="title"><a href="{{route('frontend.events.single',$slug)}}">{{$title}}</a></h2>
                <ul class="info-items-03">
                    <li><i class="far fa-clock"></i> {{$time}}</li>
                    <li><i class="fas fa-map-marker-alt"></i> {{$venuelocation}}</li>
                </ul>
                <div class="events-btn-wrapper">
                    <a href="{{route('frontend.events.single',$slug)}}" class="book-btn">{{$buttontext}}</a>
                    <span class="free-btn">{{$cost ? amount_with_currency_symbol($cost) : __('Free')}}</span>
                </div>
            </div>
        </div>
    </div>
</div>