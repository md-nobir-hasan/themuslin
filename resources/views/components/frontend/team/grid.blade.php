<div class="team-single-item">
    <div class="thumb">
        {!! render_image_markup_by_attachment_id($image) !!}
        <div class="content style-{{$index}}">
            <h4 class="title">{{$name}} </h4>
            <div class="social-link">
                <ul>
                    @if(!empty($iconone) && !empty($icononeurl))
                        <li><a href="{{$icononeurl}}"><i class="{{$iconone}}"></i></a></li>
                    @endif
                    @if(!empty($icontwo) && !empty($icontwourl))
                        <li><a href="{{$icontwourl}}"><i class="{{$icontwo}}"></i></a></li>
                    @endif
                    @if(!empty($iconthree) && !empty($iconthreeurl))
                        <li><a href="{{$iconthreeurl}}"><i class="{{$iconthree}}"></i></a></li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>