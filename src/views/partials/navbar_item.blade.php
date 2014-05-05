@if (is_array($item))
	<li class="dropdown-submenu">
        <a href="#">{{$key}}</a>
		<ul class="dropdown-menu">
			@foreach ($item as $key => $item)
                @include('admin::partials.navbar_item')
			@endforeach
		</ul>
	</li>
@else
	<li>
		@if (strpos($key, $settingsPrefix) === 0)
			<a href="{{ URL::route('admin_settings', array(substr($key, strlen($settingsPrefix)))) }}">{{$item}}</a>
		@elseif (strpos($key, $pagePrefix) === 0)
			<a href="{{ URL::route('admin_page', array(substr($key, strlen($pagePrefix)))) }}">{{$item}}</a>
		@else
			<a href="{{ URL::route('admin_index', array($key)) }}">{{$item}}</a>
		@endif
	</li>
@endif
