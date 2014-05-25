@if (is_array($item))
	<li class="dark-nav">
        <span class="glow"></span>
        <a href="#{{ $key }}" data-toggle="collapse" class="accordion-toggle collapsed ">
            <i class="fa fa-{{ $item['icon'] or 'laptop' }} fa-2x"></i>
            <span>{{ $item['title'] }}<i class="fa fa-caret-down"></i></span>
        </a>
		<ul class="collapse" id="{{$key}}">
			@foreach ($item['items'] as $key => $child)
                @include('admin::partials.sidebar_item',array('item' => $child, 'parameter' => $item['parameter'] ))
			@endforeach
		</ul>
	</li>
@else
    @if( User::find($user->id)->can($key) )
	<li>
        <a href="{{ route($key,$parameter) }}"><i class="fa fa-hand-up"></i> {{$item}}</a>
	</li>
    @endif
@endif
