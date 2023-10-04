<?php

namespace App\Repositories;

use App\Models\NewsImage;
use App\Traits\ResponseAPI;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class NewsImageRepository implements NewsImageRepositoryInterface
{
    use ResponseAPI; // Use ResponseAPI Trait in this repository
    protected $model;

    public function __construct(NewsImage $model)
    {
        $this->model = $model;
        $this->directory = 'storage/upload/news/'; 
    }

    public function save($request, $news)
    {
        $imageUpload = $this->uploadImage($request);

        $data = [
            'news_id' => $news->id,
            'name' => $imageUpload['name'],
            'url' => $imageUpload['url'],
            'extension' => $imageUpload['ext']
        ];
        $result = $this->model->create($data);
        if ($result) {
            return $this->success("success", $data ,200);
        }else{
            return $this->error('error', 500);

        }
    }

    public function update($request, $news)
    {
        $imageUpload = $this->uploadImage($request);
        $imageNews = $this->model->where('news_id', $news->id)->first();
        
        if (!empty($imageNews)){
            if (File::exists(public_path($this->directory.$imageNews->name))){
                File::delete(public_path($this->directory.$imageNews->name));
            }   
        }   

        $data = [
            'news_id' => $news->id,
            'name' => $imageUpload['name'],
            'url' => $imageUpload['url'],
            'extension' => $imageUpload['ext']
        ];
        $result = empty($imageNews) ? $this->model->create($data) : $imageNews->update($data); 

        if ($result) {
            return $this->success("success", $data ,200);
        }else{
            return $this->error('error', 500);

        }

    }

    public function delete($news)
    { 
        $imageNews = $this->model->where('news_id', $news->id)->first();
        
        if (!empty($imageNews)){
            if (File::exists(public_path($this->directory.$imageNews->name))){
                File::delete(public_path($this->directory.$imageNews->name));
            }   
        }   
        $result = $imageNews->delete();

        if ($result) {
            return $this->success("success", null ,200);
        }else{
            return $this->error('error', 500);

        }
    }

    public function uploadImage($request)
    {
        $fileExt     = $request->image->getClientOriginalExtension();
        $fileRequest = $request->image;
        $extension   = $fileExt;
        $imageName   = strtotime(now()).rand(11111,99999).'.'.$extension;
        $path        = Storage::putFileAs('public/upload/news',$fileRequest, $imageName);
        $urlImage    = url('storage/upload/news/' . $imageName);
        
        return [
            'name' => $imageName,
            'url' => $urlImage,
            'ext' => $extension
        ];
    }
 
}