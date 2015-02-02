<!-- 上方選單 -->
  <nav class="navbar navbar-default" role="navigation">
    <div class="container-fluid">
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="/">首頁</a>
      </div>

      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
            <li class="{{ ($action == 'ptt')?'active':'' }}">{{ HTML::link('/ptt', '推文') }}</li>
            <li class="{{ ($action == 'youtube')?'active':'' }}">{{ HTML::link('/youtube', 'youtube播放') }}</li>
                    <!--<li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <span class="caret"></span></a>
            <ul class="dropdown-menu" role="menu">
              <li><a href="#">Action</a></li>
              <li><a href="#">Another action</a></li>
              <li><a href="#">Something else here</a></li>
              <li class="divider"></li>
              <li class="dropdown-header">Nav header</li>
              <li><a href="#">Separated link</a></li>
              <li><a href="#">One more separated link</a></li>
            </ul>
          </li>-->
        </ul>
        <!--<ul class="login_area nav navbar-nav navbar-right">
          <li class="login_hide"><a href="#" onclick="auth.login('facebook');">以facebook登入</a></li>
          <li class="logout_hide user_pic"><img src="" class="img-rounded"></li>
          <li class="logout_hide user_name"><a href="#"></a></li>
          <li class="logout_hide"><a href="#" onclick="auth.logout();">登出</a></li>
        </ul>-->
      </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
  </nav>
  <!-- 上方選單 end -->