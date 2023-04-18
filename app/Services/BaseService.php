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

    public function orderNSearch($request, $query){
        $limit = $request->query('limit',PaginationContants::LIMIT);
        // order by
        if(array_key_exists('orderBy',$request->query()))
            foreach($request->query('orderBy') as $column => $sortType) {
                $query = $query->orderBy($column, $sortType);
            }
        $isSearch = array_key_exists('search',$request->query()) && $request->query('search');
        // search
        if($isSearch)
        {   
            $searchColumn = $request->query('search')['column'];
            $searchType = $request->query('search')['type'];
            $searchKey = $request->query('search')['key'];
            $query = $query->where($searchColumn,$searchType,'%'.$searchKey.'%');
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