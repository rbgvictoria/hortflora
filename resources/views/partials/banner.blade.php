    <div class="container">
          <div class="row">
              <div class="col-lg-12 clearfix">
                <ul class="social-media">
                    <li><a href="https://twitter.com/RBG_Victoria" target="_blank"><span class="icon icon-twitter-solid"></span></a></li>
                    <li><a href="https://www.facebook.com/BotanicGardensVictoria" target="_blank"><span class="icon icon-facebook-solid"></span></a></li>
                    <li><a href="https://instagram.com/royalbotanicgardensvic/" target="_blank"><span class="icon icon-instagram-solid"></span></a></li>
                </ul>
              </div> <!-- /.col -->
              
            <nav class="navbar navbar-default">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <div class="navbar-brand">
                        <a class="brand-rbg" href="http://www.rbg.vic.gov.au"><img src="https://vicflora.rbg.vic.gov.au/images/rbg-logo-with-text.png" alt=""/></a>
                        <a class="brand-vicflora" href="https://hortflora.rbg.vic.gov.au/">HortFlora</a>
                    </div>
                </div>
              
                <div id="navbar" class="navbar-collapse collapse">
                  <ul class="nav navbar-nav">
                      <li class="home-link"><a href="{{ env('APP_URL') }}"><span class="glyphicon glyphicon-home"></span></a></li>
                    <li><a href="{{ secure_url('/search') }}">Search</a></li>
                    <li><a href="{{ secure_url('/browse') }}">Browse classification</a></li>
                    <li><a href="#">Glossary</a></li>
                  </ul>
                  <form action="{{ secure_url('search') }}" accept-charset="utf-8" method="get" class="navbar-form navbar-right">                    <div class="form-group">
                        <div class="input-group">
                      <input type="text" name="q" value="" class="form-control input-sm" placeholder="Enter taxon name..."  />                            <div class="submit input-group-addon"><i class="fa fa-search fa-lg"></i></div>
                        </div>
                    </div>
                    
                  </form>                </div><!--/.navbar-collapse -->
            </nav>

            <div class="col-lg-12">
                <div id="header">
                    <div class="login">
                        @if(auth()->check())
                        {{ Auth::user()->getName() }} | <a href="{{ secure_url('logout') }}"
                                onclick="event.preventDefault();
                                         document.getElementById('logout-form').submit();">Log out</a>
                        <form id="logout-form" action="{{ secure_url('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                            <input type="hidden" name="user_id" value="{{ Auth::user()->getId() }}"/>
                        </form>

                        @else
                        <a href="{{ secure_url('login') }}" id="hidden-login-link">Log in</a>
                        @endif
                    </div>
                    <div id="logo">
                        <a href='http://www.rbg.vic.gov.au'>
                            <img class="img-responsive" src="https://vicflora.rbg.vic.gov.au/images/rbg-logo-with-text" alt="" />
                        </a>
                    </div>
                    <div id="site-name">
                        <a href="{{ env('APP_URL')}}">{{ env('APP_NAME') }}</a>
                    </div>
                    <div id="subtitle">{{ env('APP_SUBTITLE') }}</div>
                </div>
            </div>
              
        </div><!--/.row -->
    </div><!--/.container -->
