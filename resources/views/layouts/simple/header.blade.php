@php
    $user = auth()->user();
    $role = $user ? \App\Models\Role::where('id', $user->role_id)->first() : null;
@endphp

<div class="page-header">
  <div class="header-wrapper row m-0">
    <form class="form-inline search-full col" action="#" method="get">
      <div class="mb-3 w-100">
        <div class="Typeahead Typeahead--twitterUsers">
          <div class="u-posRelative">
            <input class="demo-input Typeahead-input form-control-plaintext w-100" type="text" placeholder="Search Cuba .." name="q" title="" autofocus>
            <div class="spinner-border Typeahead-spinner" role="status"><span class="sr-only">Loading...</span></div>
            <i class="close-search" data-feather="x"></i>
          </div>
          <div class="Typeahead-menu"></div>
        </div>
      </div>
    </form>
    <div class="header-logo-wrapper col-auto p-0">
      <div class="logo-wrapper"><a href="{{route('/')}}"><img class="img-fluid" src="{{asset('assets/images/logo/logo.png')}}" alt=""></a></div>
      <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="align-center"></i></div>
    </div>
    <div class="left-header col horizontal-wrapper ps-0">
    </div>
    <div class="nav-right col-8 pull-right right-header p-0">
      <ul class="nav-menus">
        <li class="language-nav">
          <div class="translate_wrapper">
            <div class="current_lang">
              <div class="lang"><i class="flag-icon flag-icon-{{ (App::getLocale() == 'en') ? 'us' : App::getLocale() }}"></i><span class="lang-txt">{{ App::getLocale() }} </span></div>
            </div>
           
          </div>
        </li>
        <li>
          <span class="header-search"><i data-feather="search"></i></span>
        </li>
        <li class="onhover-dropdown">
          <div class="notification-box"><i data-feather="bell"> </i><span class="badge rounded-pill badge-secondary">4</span></div>
        </li>
        <li class="onhover-dropdown">
          <div class="notification-box"><i data-feather="star"></i></div>
          <div class="onhover-show-div bookmark-flip">
            <div class="flip-card">
              <div class="flip-card-inner"> 
                <div class="back">
                  <ul>
                    <li>
                      <div class="droplet-dropdown bookmark-dropdown flip-back-content">
                        <input type="text" placeholder="search...">
                      </div>
                    </li>
                    <li>
                      <button class="d-block flip-back" id="flip-back">Back</button>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </li>
        <li>
          <div class="mode"><i class="fa fa-moon-o"></i></div>
        </li>
        <li class="cart-nav onhover-dropdown">
          <div class="cart-box"><i data-feather="shopping-cart"></i><span class="badge rounded-pill badge-primary">2</span></div>
        </li>
        <li class="onhover-dropdown">
          <i data-feather="message-square"></i>
        </li>
        <li class="maximize"><a class="text-dark" href="#!" onclick="javascript:toggleFullScreen()"><i data-feather="maximize"></i></a></li>
        <li class="profile-nav onhover-dropdown p-0 me-0">
          <div class="media profile-media">
            <img class="b-r-10" src="{{asset('assets/images/dashboard/profile.jpg')}}" alt="">
            <div class="media-body">
              <span>{{ $user ? $user->name : ''  }}</span>
              <p class="mb-0 font-roboto">{{ $role ? $role->name : '' }}<i class="middle fa fa-angle-down"></i></p>
            </div>
          </div>
          <ul class="profile-dropdown onhover-show-div">
            <li>
                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                             document.getElementById('logout-form').submit();">
                    <i data-feather="log-in"></i>
                    <span>Log out</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
        
        </li>
      </ul>
    </div>
    <script class="result-template" type="text/x-handlebars-template">
      <div class="ProfileCard u-cf">                        
      <div class="ProfileCard-avatar"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-airplay m-0"><path d="M5 17H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-1"></path><polygon points="12 15 17 21 7 21 12 15"></polygon></svg></div>
      <div class="ProfileCard-details">
      <div class="ProfileCard-realName">@{{name}}</div>
      </div>
      </div>
    </script>
    <script class="empty-template" type="text/x-handlebars-template"><div class="EmptyMessage">Your search turned up 0 results. This most likely means the backend is down, yikes!</div></script>
  </div>
</div>
