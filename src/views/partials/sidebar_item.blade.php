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
    @if( User::find($user->id)->can($key) )
	<li>
        <a href="{{ route($key,array('name'=>'advance')) }}"><i class="icon-hand-up"></i> {{$item}}</a>
	</li>
    @endif
@endif
