<?php

namespace App\Repositories;

use App\Models\News;
use App\Events\Logging;
use App\Jobs\CreateComment;
use App\Traits\ResponseAPI;
use Illuminate\Support\Str;
use App\Http\Requests\NewsRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\NewsResource;
use App\Http\Resources\NewsDetailResource;

class NewsRepository implements NewsRepositoryInterface
{
    protected $model;
    // Use ResponseAPI Trait in this repository
    use ResponseAPI;

    /**
     * @var NewsImageRepository
     */
    private $newsImageRepo;

    public function __construct(News $model, NewsImageRepository $newsImageRepo)
    {
        $this->model = $model;
        $this->newsImageRepo = $newsImageRepo;

    }

    public function all()
    {
        try {
            NewsResource::withoutWrapping();
            return NewsResource::collection($this->model->paginate(10));
        } catch(\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function find($id)
    {
        $data = $this->model->with('comments')->findOrFail($id);
        return new NewsDetailResource($data);
    }
    
    public function create(NewsRequest $request)
    {
        DB::beginTransaction();
        try {
            $newsRequest = [
                'title' => $request->title,
                'slug' => Str::slug($request->title),
                'description' => $request->description
            ];
            $data = $this->model->create($newsRequest);
            // Save Image
            if ($request->has('image')) $this->newsImageRepo->save($request, $data);
            DB::commit();
            // Store Event Log
            event(new Logging(auth()->user(), 'Create'));

            return new NewsResource($data);
        } catch(\Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), 500);
        }
    }

    public function update($id, NewsRequest $request)
    {
        DB::beginTransaction();
        try {
            $newsRequest = [
                'title' => $request->title,
                'slug' => Str::slug($request->title),
                'description' => $request->description
            ];
            $news = $this->model->findOrFail($id);
            $news->update($newsRequest);
            // Save Image
            if ($request->has('image')) $this->newsImageRepo->update($request, $news);
            DB::commit();
            // Store Event Log
            event(new Logging(auth()->user(), 'Update'));

            return new NewsResource($news);
        } catch(\Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), 500);
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $news = $this->model->with('image')->findOrFail($id);
            
            // Delete Image
            if (!empty($news->image)) $this->newsImageRepo->delete($news);
            $news->delete();
            DB::commit();
            // Store Event Log
            event(new Logging(auth()->user(), 'Delete'));

            return new NewsResource($news);
        } catch(\Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), 500);
        }
    }

    public function comment($slug, $request)
    {
        $news = $this->model->where('slug', $slug)->firstOrFail();
        // Dispatch the job
        CreateComment::dispatch($news, $request, auth()->user()); 
    }
}