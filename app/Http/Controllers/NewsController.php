<?php

namespace App\Http\Controllers;

use App\Jobs\CreateComment;
use Illuminate\Http\Request;
use App\Http\Requests\NewsRequest;
use App\Repositories\NewsRepository;

class NewsController extends Controller
{
    protected $newsRepository;

    public function __construct(NewsRepository $newsRepository)
    {
        $this->newsRepository = $newsRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->newsRepository->all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NewsRequest $request)
    {    
        return $this->newsRepository->create($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\News  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->newsRepository->find($id);
    }

    public function update($id, NewsRequest $request)
    {
        return $this->newsRepository->update($id, $request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->newsRepository->delete($id);
    }

    public function comment($slug, Request $request)
    {
        return $this->newsRepository->comment($slug, $request);        
    }
}
