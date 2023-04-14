<?php
namespace App\Services;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class BaseService {

    protected  $model;

    public function index() {
        return $this->model->orderBy('created_at',"DESC")->paginate(Constants::LIMIT);
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