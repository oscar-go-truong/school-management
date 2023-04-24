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
        $update = $this->model->where('id', $id)->update(['status' => $status]);
        if ($update) {
            return $this->model->find($id);
        } else {
            return  null;
        }
    }

    public function orderNSearch($request, $query)
    {
        $input = $request->input();
        $limit = $request->query('limit', PaginationContants::LIMIT);
        // order by
        $isOrder = isset($input['orderBy']) && isset($input['orderDirect']);
        if ($isOrder) {
            $orderBy = $input['orderBy'];
            $orderDirect = $input['orderDirect'];
                $query = $query->orderBy($orderBy, $orderDirect);
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
        $model = $this->model->find($id);
        if ($model) {
            $this->model->destroy($id);
            return $model;
        } else {
            return null;
        }
    }
}
