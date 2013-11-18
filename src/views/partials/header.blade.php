<header>
	<h1>
		<a href="{{URL::route('admin_dashboard')}}">{{Config::get('admin::admin.title')}}</a>
	</h1>

	<a href="#" id="menu_button"><div></div></a>
	<a href="#" id="filter_button" class="{{$configType === 'model' ? '' : 'hidden'}}"><div></div></a>

	<div id="mobile_menu_wrapper">
		<ul id="mobile_menu">
			@foreach ($menu as $key => $item)
				@include('admin::partials.menu_item')
			@endforeach
		</ul>
	</div>

	<ul id="menu">
		@foreach ($menu as $key => $item)
			@include('admin::partials.menu_item')
		@endforeach
	</ul>

	<div id="right_nav">
		@if (count(Config::get('admin::admin.locales')) > 0)
			<ul id="lang_menu">
				<li class="menu">
				<span>{{Config::get('app.locale')}}</span>
					@if (count(Config::get('admin::admin.locales')) > 1)
						<ul>
							@foreach (Config::get('admin::admin.locales') as $lang)
								@if (Config::get('app.locale') != $lang)
									<li>
										<a href="{{URL::route('admin_switch_locale', array($lang))}}">{{$lang}}</a>
									</li>
								@endif
							@endforeach
						</ul>
					@endif
				</li>
			</ul>
		@endif
		<a href="{{URL::to('/')}}" id="back_to_site">{{trans('admin::admin.backtosite')}}</a>
		@if(Config::get('admin::admin.logout_path'))
			<a href="{{URL::to(Config::get('admin::admin.logout_path'))}}" id="logout">{{trans('admin::admin.logout')}}</a>
		@endif
	</div>
</header>