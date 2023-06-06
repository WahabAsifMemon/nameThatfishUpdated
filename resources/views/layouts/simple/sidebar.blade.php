<div class="sidebar-wrapper">
	<div>
		<div class="logo-wrapper">
			<a href="{{route('/')}}"><img class="img-fluid for-light" src="{{asset('assets/images/logo/logo.png')}}" alt=""><img class="img-fluid for-dark" src="{{asset('assets/images/logo/logo_dark.png')}}" alt=""></a>
			<div class="back-btn"><i class="fa fa-angle-left"></i></div>
			<div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="grid"> </i></div>
		</div>
		<div class="logo-icon-wrapper"><a href="{{route('/')}}"><img class="img-fluid" src="{{asset('assets/images/logo/logo-icon.png')}}" alt=""></a></div>
		<nav class="sidebar-main">
			<div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
			<div id="sidebar-menu">
				<ul class="sidebar-links" id="simple-bar">
					<li class="back-btn">
						<a href="{{route('/')}}"><img class="img-fluid" src="{{asset('assets/images/logo/logo-icon.png')}}" alt=""></a>
						<div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
					</li>
					<li class="sidebar-main-title">
						<div>
							<h6 class="lan-1">{{ trans('General') }} </h6>
                     		<p class="lan-2">{{ trans('Dashboards,widgets & layout.') }}</p>
						</div>
					</li>


					<li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav {{ Route::currentRouteName()=='index' }}" href="{{route('index')}}"><i data-feather="heart"> </i><span>{{ trans('Dashboard') }}</span></a></li>


					<li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav {{ Route::currentRouteName()=='privacy' }}" href="{{route('privacy')}}"><i data-feather="check-square"> </i><span>{{ trans('Privacy') }}</span></a></li>


					<li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav {{ Route::currentRouteName()=='setting' }}" href="{{route('setting')}}"><i data-feather="zap"> </i><span>{{ trans('Setting') }}</span></a></li>


					<li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav  {{ Route::currentRouteName()=='term' }}" href="{{route('term')}}"><i data-feather="server"> </i><span>{{ trans('Terms') }}</span></a></li>

					<li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav  {{ Route::currentRouteName()=='about' }}" href="{{route('about')}}"><i data-feather="star"> </i><span>{{ trans('About') }}</span></a></li>

					<li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav  {{ Route::currentRouteName()=='user_managment' }}" href="{{route('user_managment')}}"><i data-feather="users"> </i><span>{{ trans('User Management') }}</span></a></li>

					<li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav  {{ Route::currentRouteName()=='notification' }}" href="{{route('notification')}}"><i data-feather="bell"> </i><span>{{ trans('Notifications') }}</span></a></li>
					
			</div>
			<div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
		</nav>
	</div>
</div>


