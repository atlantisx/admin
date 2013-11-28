@if (is_array($item))
	<li class="dark-nav">
        <span class="glow"></span>
        <a href="#{{$key}}" data-toggle="collapse" class="accordion-toggle collapsed ">
            <i class="icon-beaker icon-2x"></i>
            <span>{{$key}}<i class="icon-caret-down"></i></span>
        </a>
		<ul class="collapse" id="{{$key}}">
			@foreach ($item as $key => $item)
                @include('admin::partials.sidebar_item')
			@endforeach
		</ul>
	</li>
@else
	<li>
        <a href="{{ URL::to($item) }}"><i class="icon-hand-up"></i> {{$key}}</a>
	</li>
@endif
