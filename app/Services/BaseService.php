<?php

namespace App\Services;

use App\Enums\PaginationContants;

abstract class BaseService
{
    protected $model;

    public function __construct()
    {
        $this->model = app()->make(
            $this->getModel()
        );
    }
    abstract public function getModel();
    public function index()
    {
        return $this->model->all();
    }

    public function changeStatus($id, $status)
    {
        return  $this->model->where('id', $id)->update(['status' => $status]);
    }

    public function orderNSearch($request, $query)
    {
        $input = $request->input();
        $limit = $request->query('limit', PaginationContants::LIMIT);
        // order by
        if (isset($input['orderBy'])) {
            foreach ($input['orderBy'] as $column => $sortType) {
                $query = $query->orderBy($column, $sortType);
            }
        }
        if (isset($input['search']) && $input['search']) {
            $searchColumn = $request->query('search')['column'];
            $searchType = $request->query('search')['type'];
            $searchKey = $request->query('search')['key'];
            $query = $query->where($searchColumn, $searchType, '%' . $searchKey . '%');
        }
        return $query->paginate($limit);
    }
    public function getById($id)
    {
        return $this->model->where('id', $id)->first();
    }


    public function store($model)
    {
        return $this->model->create($model);
    }

    public function update($id, $arg)
    {
        return  $this->model->where('id', $id)->update($arg);
    }

    public function destroy($id)
    {
        return $this->model->destroy($id);
    }
}
