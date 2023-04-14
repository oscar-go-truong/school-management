<?php
namespace App\Services;

use App\Enums\Contants;

class BaseService {

    protected  $model;

    public function index() {
        return $this->model->orderBy('created_at',"DESC")->paginate(Contants::LIMIT);
    }

    public function getById($id) {
        return $this->model->where('id', $id)->first();
    }


    public function store($model){
        return $this->model->create($model);
    }

    public function update($id, $arg) {
        return  $this->model->where('id',$id)->update($arg);
    }

    public function destroy($id) {
        return $this->model->destroy($id);
    }
}