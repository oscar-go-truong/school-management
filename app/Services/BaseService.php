<?php
namespace App\Services;

use App\Enums\PaginationContants;

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

    public function getTable($request){
        $limit = $request->query('limit',PaginationContants::LIMIT);
        $query = $this->model;
        if(array_key_exists('orderBy',$request->query()))
            foreach($request->query('orderBy') as $column => $sortType) {
                $query = $query->orderBy($column, $sortType);
            }
            if(array_key_exists('searchLike',$request->query()))
            foreach($request->query('searchLike') as $column => $searchKey) {
                $query = $query->where($column,'like','%'.$searchKey.'%');
            }
        
        return $query->paginate($limit);
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