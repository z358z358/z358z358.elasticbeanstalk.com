@extends('layouts/master' , ['title' => 'youtube 播放器' , 'action' => 'youtube'])

@section('content')

	<div class="jumbotron">
	<h1>youtube 播放器</h1>
	<form role="form">
		<div id="ptt_form" class="form-group">
			<label for="exampleInputEmail1">網址</label>
			<input id="youtube_url" value="{{ Input::get('youtube_url') }}" type="text" name="youtube_url" class="form-control" placeholder="">
		</div>
		<input id="youtube_go" type="submit" class="btn btn-default" value="Go"><a href="#" onclick="$('#div_result').show();">或直接輸入含有youtube網址的文字</a>
	</form>
	<div id="div_result" class="hid">
		<textarea id="result" class="form-control" rows="3"><?=$html_youtube?></textarea>
		<a href="#" onclick="get_video_id()" class="btn btn-default">Go</a>
	</div>
</div>
<div class="youtube_jum jumbotron hid">
	<div class="youtube resizable">
		<div id="player_youtube">

		</div>
	</div>
</div>
<div id="list">

</div>

<div class="row featurette">
	<div class="col-md-10">
		<h2 class="featurette-heading">使用說明.<span class="text-muted">貼上網址，按Go.</span></h2>
		<p class="lead">在欄位裡輸入網址，抓取該網頁的內容，然後列出youtube。<br>範例:
			{{ HTML::link('youtube?youtube_url=http%3A%2F%2Fwww.ptt.cc%2Fbbs%2FELSWORD%2FM.1409792311.A.883.html', 'http://www.ptt.cc/bbs/ELSWORD/M.1409792311.A.883.html')}}
	</div>
</div>

@stop

@section('js')

{{ HTML::style('assets/css/my.css') }}
{{ HTML::style('//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css') }}
{{ HTML::script('//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js') }}
{{ HTML::script('//cdn.jsdelivr.net/jquery.cookie/1.4.1/jquery.cookie.min.js') }}

<script type="text/javascript" id="youtube_api" src=""></script>
<script type="text/javascript">
	String.prototype.replaceArray = function(find, replace) {
	  var replaceString = this;
	  for (var i = 0; i < replaceString.length; i++) {
		replaceString = replaceString.replace(find, replace);
	  }
	  return replaceString.split(",");
	};

	Array.prototype.my_unique = function() {
		var a = this.concat();//使用concat()再複製一份陣列，避免影響原陣列
		for(var i=0; i<a.length; ++i) {
			for(var j=i+1; j<a.length; ++j) {
				if(a[i] === a[j])
					a.splice(j, 1);
			}
		}

		return a;
	};

    var video_id , video_id2;
	var played = [];
    // create youtube player
    var player2;
	var count = 0;

	function play_video() {
		$("#player_youtube").replaceWith($('<div id="player_youtube"></div>'));
		var player = {
		  playVideo: function(container, videoId) {
		    if (typeof(YT) == 'undefined' || typeof(YT.Player) == 'undefined') {
		      window.onYouTubeIframeAPIReady = function() {
		        player.loadPlayer(container, videoId);
		      };

		      $.getScript('//www.youtube.com/iframe_api');
		    } else {
		      player.loadPlayer(container, videoId);
		    }
		  },

		  loadPlayer: function(container, videoId) {
		    new YT.Player(container, {
		      videoId: videoId,
		      events: {
	            'onReady': onPlayerReady,
	            'onStateChange': onPlayerStateChange,
				'onError': onPlayerError
	          }
		    });
		  }
		};

		player.playVideo('player_youtube' , video_id[count]);

		if($("#" + video_id[count] + " .video_name").html() == ""){
			var url = "http://gdata.youtube.com/feeds/api/videos/" + video_id[count] + "?v=2&alt=jsonc&&callback=showMyVideos";
			// 创建script标签，设置其属性
			var script = document.createElement('script');
			script.setAttribute('src', url);
			// 把script标签加入head，此时调用开始
			document.getElementsByTagName('head')[0].appendChild(script);
		}
    };

	function get_video_id(){
		video_id = [];
		var tmp = $("#result").val().match(/watch\?v\=(...........)/g);
		if(tmp){
			video_id = $("#result").val().match(/watch\?v\=(...........)/g).toString().replaceArray("watch?v=" , "");
		}
		tmp = $("#result").val().match(/youtu.be\/(...........)/g);
		if(tmp){
			video_id2 = $("#result").val().match(/youtu.be\/(...........)/g).toString().replaceArray("youtu.be/" , "");
			video_id = video_id.concat(video_id2).my_unique();
		}
		var unique_video = [];
		$.each(video_id, function(i, el){
			if($.inArray(el, unique_video) === -1) unique_video.push(el);
		});
		video_id = unique_video;
		var textt = "";
		for (var i = 0; i < video_id.length; i++) {
			textt += '<tr><td>' + (i+1) + '<td><td><a class="youtube_link" id="'+video_id[i]+'" href="http://youtu.be/'+video_id[i]+'" target="_blank"><span class="video_name"></span> http://youtu.be/'+video_id[i]+'</a></td><td class="jump" data-vid="'+video_id[i]+'" data-count="'+i+'"><a href="#">播這首</a></td></tr>';
		}
		$("#list").html('<h4>找到' + video_id.length + '首</h4><table class="table table-hover table-striped"><thead><tr><th></th><th></th><th></th></tr></thead><tbody>' +  textt + '</tbody></table>' );
		count = 0;
		play_video();
		$(".youtube_jum").show();
		$.cookie('youtube_url', $("#youtube_url").val(), { expires: 365 });
	};

    // autoplay video
    function onPlayerReady(event) {
		var last_id = $.cookie('last_id');
		$(".youtube_link").parents("tr").removeClass("warning success");
		if(last_id){
			$("#" + last_id + ".youtube_link").parents("tr").addClass("warning");
		}
		$("#" + video_id[count] + ".youtube_link").parents("tr").addClass("success");



        event.target.playVideo();
    };

    // when video ends
    function onPlayerStateChange(event) {
        if(event.data === 0) {
			$.cookie('last_id', video_id[count], { expires: 365 });
            count++;
			if(!(count >= video_id.length)){
				play_video();
			}
			else{
				count = 0;
				play_video();
			}
        }
		//console.log(event);
    };

    // 有錯誤就撥下一首
    function onPlayerError(){
        count++;
        if(!(count >= video_id.length)){
            play_video();
        }
        else{
            count = 0;
            play_video();
        }
    }

	function showMyVideos(data){
		$("#" + video_id[count] + " .video_name").html(data['data']['title']);
	}
</script>

@stop

@section('js_ready')

$("#ptt_result").hide();

$( ".resizable" ).resizable();

if($("#result").val()){
	get_video_id();
}

$("#list").on("click" , ".jump" , function(){
	var this_count = parseInt($(this).data("count"));
	if(this_count >= 0){
		count = this_count;
		play_video();
	}
});

var cookie_url = $.cookie('youtube_url');
if(cookie_url && $("#youtube_url").val() == ""){
	$("#youtube_url").val(cookie_url);
}

@stop