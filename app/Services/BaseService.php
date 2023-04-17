<?php
namespace App\Services;

use App\Enums\PaginationContants;
use Illuminate\Database\Eloquent\Model;

abstract class BaseService {

    protected  $model;

    public function __construct(){
        $this->model = app()->make(
            $this->getModel()
        );
    }
    abstract public function getModel();
    public function index() {
        return $this->model->paginate(PaginationContants::LIMIT);
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