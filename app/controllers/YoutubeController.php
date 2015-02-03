<?php

class YoutubeController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /youtube
	 *
	 * @return Response
	 */
	public function index()
	{
		//
		$html_youtube = '';
		$youtube_url = Input::get('youtube_url');
		$youtube_url = filter_var($youtube_url, FILTER_VALIDATE_URL);
		if($youtube_url)
		{
			$opts = array(
			  'http'=>array(
			    'method'=>"GET",
			    'header'=>"Accept-language: en\r\n" .
			              "Cookie: over18=1"
			  )
			);

			$context = stream_context_create($opts);
			$html_youtube = file_get_contents($youtube_url, false, $context);
		}

		// Open the file using the HTTP headers set above
		return View::make('youtube.index')->with('html_youtube' , $html_youtube)->with('youtube_url' , $youtube_url);
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /youtube/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /youtube
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /youtube/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /youtube/{id}/edit
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
	 * PUT /youtube/{id}
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
	 * DELETE /youtube/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}