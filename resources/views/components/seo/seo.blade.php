<div class="container">
    <div class="seo">
        @if(!empty($seo_title))
            <h3 class="title">{{$seo_title}}</h3>
        @endif
        @if(!empty($seo_description))
            <p class="text">{{$seo_description}}</p>
        @endif
        @if(!empty($image))
            <img src="{{$image}}" alt="seo">
        @endif
    </div>
</div>
