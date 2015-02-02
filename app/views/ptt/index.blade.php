@extends('layouts/master' , ['title' => 'Ptt推文計算機' , 'action' => 'ptt'])

@section('content')

	<!-- Main component for a primary marketing message or call to action -->
	<div class="jumbotron">
	<h1>Ptt推文計算</h1>
	{{ Form::open()}}
		<div id="ptt_form" class="form-group">
			<label for="exampleInputEmail1">Ptt網頁版網址</label>
			<input id="ptt_url" type="text" name="ptt_url" class="form-control" placeholder="範例:http://www.ptt.cc/bbs/Gossiping/M.1408188026.A.085.html">
		</div>
		<input type="hidden" name="post_type" value="ptt">
		<button id="ptt_go" type="button" class="ajax_post btn btn-default">Go</button>
	{{ Form::close()}}
</div>
<div id="ptt_result" style="display: none;">
	<h3 id="ptt_title"></h3>
	<!-- Nav tabs -->
	<ul id="ptt_nav" class="nav nav-tabs" role="tablist">
		<li class="active"><a href="#push_table" role="tab" data-toggle="tab">內容</a></li>
		<li><a href="#chart" role="tab" data-toggle="tab">分析</a></li>
		<!--<li><a href="#chart_table" role="tab" data-toggle="tab">分析table</a></li>-->
	</ul>

	<!-- Tab panes -->
	<div class="tab-content">
		<div class="tab-pane active" id="push_table">
			<p class="text-right">
				<label class="radio-inline"><input type="radio" name="push_type" id="inlineRadio0" value="" checked="">顯示全部</label>
				<label class="radio-inline"><input type="radio" name="push_type" id="inlineRadio1" value="推">只顯示推文</label>
				<label class="radio-inline"><input type="radio" name="push_type" id="inlineRadio2" value="→">只顯示箭頭</label>
				<label class="radio-inline"><input type="radio" name="push_type" id="inlineRadio3" value="噓">只顯示噓文</label>
			</p>
			<p class="text-right">
				顯示
				<label class="radio-inline"><input type="checkbox" name="show1" id="inlineRadio00" value="1" checked="">樓</label>
				<label class="radio-inline"><input type="checkbox" name="show2" id="inlineRadio10" value="2" checked="">類型</label>
				<label class="radio-inline"><input type="checkbox" name="show3" id="inlineRadio20" value="3" checked="">id</label>
				<label class="radio-inline"><input type="checkbox" name="show4" id="inlineRadio30" value="4" checked="">內容</label>
				<label class="radio-inline"><input type="checkbox" name="show5" id="inlineRadio30" value="5" checked="">日期 時間</label>
			</p>
			<div id="push_table_content"></div>
		</div>
		<div class="tab-pane" id="chart">
			<div class="google_chart" id="chart1"></div>
		</div>
		<!--<div class="tab-pane" id="chart_table">
			<div class="google_chart" id="chart2"></div>
		</div>-->
	</div>
</div>

<div class="row featurette">
	<div class="col-md-7">
		<h2 class="featurette-heading">使用說明.<span class="text-muted">貼上網址，按Go.</span></h2>
		<p class="lead">在欄位裡輸入Ptt網頁版網址，你可以在文章列表按Q(大寫)，或該文章的推文上方有網址。<br>範例:<a href="javascript:void(0)" onclick="check_hash('http://www.ptt.cc/bbs/Gossiping/M.1413390067.A.57A.html');">http://www.ptt.cc/bbs/Gossiping/M.1413390067.A.57A.html</a></p>
	</div>
	<div class="col-md-5">
		{{ HTML::image('assets/img/example.png' , 'example' , ['class' => 'img-rounded' , 'style' => 'width:500px']) }}
	</div>
</div>
<div class="row featurette">
	<div class="col-md-7">
		<h2 class="featurette-heading">例外說明.<span class="text-muted"></span></h2>
		<p class="lead">推文若被文章作者修改，該推文就無法被撈出。<br>如右圖:推文有被修改。</p>
	</div>
	<div class="col-md-5">
		{{ HTML::image('assets/img/example2.png' , 'example2' , ['class' => 'img-rounded' , 'style' => 'width:500px']) }}
	</div>
</div>

@stop

@section('js')

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
var clog;
var tmp_data;
google.load("visualization", "1", {packages:["corechart"]});
google.load("visualization", "1", {packages:["table"]});

function ptt_table(data){
	var d = {};
	d['t'] = {};
	d['i'] = {};
	var r = '<p class="bg-danger">找不到頁面或該文章無推文!</p>';
	if(!data["data"]["push"]){
		$("#ptt_result").html(r).show();
		return;
	}

	var push_type = $("input[name='push_type']:checked").val();
	var t = data["data"]["push"];
	var i = 1;

	r = ' ';
	for(var k in t){
		tm = t[k];
		d['t'][tm['type']] = (d['t'][tm['type']]) ? (d['t'][tm['type']] + 1) :1;
		d['i'][tm['id']] = d['i'][tm['id']] || {};
		//d['i'][tm['id']][tm['type']] = (d['i'][tm['id']][tm['type']]) ? (d['i'][t[k]['id']][tm['type']] + 1) :1;
		if(push_type){
			if(push_type != tm['type']){
				continue;
			}
			k = i;
		}

		r = r + '<tr> <td>' + k + '</td> <td>' + tm['type'] + '</td> <td>' + tm['id'] + '</td> <td>' + tm['content'] + '</td> <td>' + tm['month'] + '/' + tm['day'] + ' ' + tm['hour'] + ':' + tm['mins'] + '</td>  </tr>';
		i++;
	}

	var a = [['Task', 'Hours per Day']];
	for(var t in d['t']){
		a.push([t ,d['t'][t]]);
	}

	var b = [['Task', 'Hours per Day']];
	//for(var t in d['i']){
	//	b.push([t ,d['i'][t]]);
	//}
	//console.log(d,a,b);
	var ta = '';
	ta = ta + '<table class="table table-hover table-striped"> <thead><tr><th>樓</th><th>類型</th><th>id</th><th>內容</th><th>日期 時間</th></tr></thead><tbody>' + r + '</tbody>';


	$("#ptt_result #push_table_content").html(ta);
	$("#ptt_result #ptt_title").html('<a href="' + data["data"]["ptt_url"] + '">' + data["data"]["title"] + '</a>');
	// 留hash
	window.location.hash = $("#ptt_url").val();
	$("#ptt_form").removeClass("has-error has-feedback").find(".glyphicon-remove.form-control-feedback").hide();

	google.setOnLoadCallback(drawChart('chart1' , '推 噓 箭頭 分佈' ,a ));
	//google.setOnLoadCallback(drawTable('chart2' ,b ));
	//google.setOnLoadCallback(drawChart('chart3' , '推噓箭頭分佈' ,a ));

	$("#ptt_result").show();

	// 隱藏沒勾的
	$("#push_table input:checkbox:not(:checked)").each(function(){
		var c_val = $(this).val();
		$('#push_table_content td:nth-child(' + c_val + '),#push_table_content th:nth-child(' + c_val + ')').hide();
	});

	tmp_data = data;

}

function drawChart(pid , ptitle , pdata){
	var data = google.visualization.arrayToDataTable(pdata);

	var options = {
	  'title': ptitle,
	  'width':380,
      'height':285
	};

	var chart = new google.visualization.PieChart(document.getElementById(pid));

	chart.draw(data, options);
}

function drawTable(pid , pdata) {
	var data = new google.visualization.DataTable();
	data.addColumn('string', 'id');
	data.addColumn('number', 'Salary');
	data.addColumn('boolean', 'Full Time Employee');

	data.addRows([
	['Mike',  {v: 10000, f: '$10,000'}, true],
	['Jim',   {v:8000,   f: '$8,000'},  false],
	['Alice', {v: 12500, f: '$12,500'}, true],
	['Bob',   {v: 7000,  f: '$7,000'},  true]
	]);

	var table = new google.visualization.Table(document.getElementById(pid));

	table.draw(data, {showRowNumber: true});
}

function check_hash(url){
	if(url){
		window.location.hash = url;
	}
	var t_hash = window.location.hash.substring(1);
	if(t_hash.length){
		$("#ptt_url").val(t_hash);
		$("#ptt_go").trigger("click");
	}
}
</script>

@stop

@section('js_ready')

$("#ptt_result").hide();
check_hash();

$('#ptt_nav a').click(function (e) {
  e.preventDefault();
  $(this).tab('show');
})

$("#push_table input").change(function(){
	  ptt_table(tmp_data);
});

@stop