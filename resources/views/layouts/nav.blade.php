<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                <span class="sr-only"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name') }}
            </a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav">
                <li class="@yield('active_blog')"><a href="{{ route('blog.index') }}">博客</a></li>
                <li class="@yield('active_order_create')"><a href="{{ route('order.create') }}">购买</a></li>
                <li class="@yield('active_order_index')"><a href="{{ route('order.index') }}">我的订单</a></li>
                <li class="@yield('active_account_coupons')"><a href="{{ route('account.coupons') }}">我的优惠券</a></li>
                <li class="@yield('active_question')"><a href="{{ route('question.index') }}">常见问题</a></li>
                <li class="@yield('active_about')"><a href="{{ route('about.index') }}">关于我们</a></li>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <!-- Authentication Links -->
                @if (Auth::guest())
                    <li><a href="{{ route('login.wechat') }}">登录</a></li>
                @else
                    <li class="dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            @if (!empty(Auth::user()->avatar))
                                <img src="{{ Auth::user()->avatar }}" alt="" width="30" class="img-responsive img-circle img-avatar">
                            @endif
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            @if (Auth::user()->isAdmin())
                                <li><a href="{{ route('admin.index') }}">管理中心</a></li>
                            @endif
                            <li><a href="{{ route('account.edit') }}">会员中心</a></li>
                            <li><a href="{{ route('account.referer') }}">邀请朋友</a></li>
                            <li>
                                <a href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    登出
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>