
<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<meta content="yes" name="apple-mobile-web-app-capable" />
<meta name="Robots" content="all" />
<title>{{ $title }}</title>
<link rel="shortcut icon" href="/img/icon.ico">
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
<script src="//www.youtube.com/player_api"></script>
<!-- Optional theme -->
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">

<!--[if lt IE 9]><script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
</head>
<body>
  @include('partials/nav')
  <!-- container -->
  <div class="container">
  <!-- Main component for a primary marketing message or call to action -->
  @yield('content')
  </div>
  <!-- container end -->

  <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-53250580-2', 'auto');
  ga('require', 'displayfeatures');
  ga('require', 'linkid', 'linkid.js');
  ga('send', 'pageview');

  </script>

  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
  @yield('js')

  <script>
  function ajax_data(data){

    if(data["type"] == "input_error"){
      $("#" + data["tag_id"]).parents("div.form-group").addClass("has-error has-feedback").append('<span class="glyphicon glyphicon-remove form-control-feedback"></span>');
    }

    if(data["type"] == "html"){
      $("#" + data["tag_id"]).html(data["html"]);
    }

    if(data["type"] == "ptt_table"){
      ptt_table(data);
    }

  }

  $(document).ready(function() {
    // ajax
    $("body").on("click" , ".ajax_post" , function(){
      var form = $(this).parents("form");
      if(form){
        $.post("" , form.serialize(), function(data) {
          ajax_data(data);
          //console.log(data);
        }, "json");
      }
    });

    @yield('js_ready')
  });
  </script>
</body>
</html>