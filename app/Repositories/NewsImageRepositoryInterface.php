<?php
namespace App\Repositories;


interface NewsImageRepositoryInterface
{
    public function save($request, $news);
    public function update($request, $news);
    public function delete($news);
}