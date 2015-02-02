<?php

class PttController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /ptt
	 *
	 * @return Response
	 */
	public function index()
	{
		//
		return View::make('ptt.index');
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /ptt/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /ptt
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /ptt/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show()
	{
		//
		$json = [];
		$url = Input::get('ptt_url');
		$data = Ptt::whereRaw('pttUrl = ? and updated_at > ?' , [$url , time()-60] )->first();

		if(!$data)
		{
			$rs = curl_init();
			curl_setopt($rs, CURLOPT_URL, $url);
			curl_setopt($rs, CURLOPT_COOKIE, 'over18=1');
			curl_setopt($rs, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; rv:12.0) Gecko/20120403211507 Firefox/12.0');
			curl_setopt($rs, CURLOPT_TIMEOUT, 5);
			curl_setopt($rs, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($rs, CURLOPT_FOLLOWLOCATION, 1);
			$return_html = curl_exec($rs);
			curl_close($rs);
dd($return_html);
			preg_match_all('#<div class="push"><span class="(.*?)">(.*?)</span><span class="(.*?)">(.*?)</span><span class="(.*?)">(.*?)</span><span class="(.*?)">(.*?)</span></div>#sm', $return_html, $match); 

			$floor = 1;
			preg_match("/<title>(.+)<\/title>/siU", $return_html, $matches);
			$json['title'] = $matches[1];
			$json['ptt_url'] = $ptt_url;

			// 2 推噓箭頭 4 id 6 內容 8 時間(沒年分)和ip
			foreach($match[4] as $ptt_id){
				$array_no = $floor - 1;

				$json['push'][$floor]['id'] = safe_word($ptt_id);
				$json['push'][$floor]['type'] = str_replace(' ', '', $match[2][$array_no]);

				// 刪掉冒號空白
				if(substr($match[6][$array_no] , 0 , 2) == ': '){
					$json['push'][$floor]['content'] = substr($match[6][$array_no] , 2);
				}
				else if(substr($match[6][$array_no] , 0 , 1) == ':'){
					$json['push'][$floor]['content'] = substr($match[6][$array_no] , 1);
				}
				else{
					$json['push'][$floor]['content'] = $match[6][$array_no];
				}

				//  如果有ip的話
				if(strrpos($match[8][$array_no], '.') !== false){
					$m = sscanf($match[8][$array_no], "%s %d/%d %d:%d");
				}
				else{
					$m = sscanf($match[8][$array_no], "%d/%d %d:%d");
					// 補空白ip
					array_unshift($m, '');
				}
				$json['push'][$floor]['ip'] = $m[0];
				$json['push'][$floor]['month'] = str_pad($m[1],2,'0',STR_PAD_LEFT);
				$json['push'][$floor]['day'] = str_pad($m[2],2,'0',STR_PAD_LEFT);
				$json['push'][$floor]['hour'] = str_pad($m[3],2,'0',STR_PAD_LEFT);
				$json['push'][$floor]['mins'] = str_pad($m[4],2,'0',STR_PAD_LEFT);

				$floor++;

			}

			// 塞回db
			$ptt = new Ptt;
			$ptt->pttUrl = $url;
			$ptt->content = serialize($json);
			$ptt->save();

		}

	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /ptt/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /ptt/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /ptt/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}