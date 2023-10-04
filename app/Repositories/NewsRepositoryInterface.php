<?php
namespace App\Repositories;

use App\Http\Requests\NewsRequest;

interface NewsRepositoryInterface
{
    public function all();
    public function find($id);
    public function create(NewsRequest $request);
    public function update($id, NewsRequest $request);
    public function delete($id);
    public function comment($slug, $request);
}