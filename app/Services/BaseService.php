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
    public function getAll()
    {
        return $this->model->all();
    }

    public function changeStatus($id, $status)
    {
        $update = $this->model->where('id', $id)->update(['status' => $status]);
        if ($update) 
            return ['data'=>$this->model->find($id), 'message'=>"Update status successful!"];
        return  ['data'=> null, 'message'=>"Error, please try again later!"];
    }

    protected function orderNSearch($input, $query)
    {
        $limit =isset($input['limit']) ? $input['limit'] : PaginationContants::LIMIT;
        // order by
        $isOrder = isset($input['orderBy']) && isset($input['orderDirect']);
        if ($isOrder) {
            $orderBy = $input['orderBy'];
            $orderDirect = $input['orderDirect'];
                $query = $query->orderBy($orderBy, $orderDirect);
        }
        if (isset($input['search']) && $input['search']) {
            $searchColumn = $input['search']['column'];
            $searchType = $input['search']['type'];
            $searchKey = $input['search']['key'];
            $query = $query->where($searchColumn, $searchType, '%' . $searchKey . '%');
        }
        return $query->paginate($limit);
    }
    public function getById($id)
    {
        return $this->model->where('id', $id)->first();
    }


    public function store($arg)
    {
        $result =  $this->model->create($arg);
        if ($result) 
            return ['data'=> $result, 'message'=>"Create successful!"];
        return  ['data'=> null, 'message'=>"Error, please try again later!"];
    }

    public function update($id, $arg)
    {
        $result = $this->model->where('id', $id)->update($arg);
        if ($result) 
            return ['data'=>$this->model->find($id), 'message'=>"Update successful!"];
        return  ['data'=> null, 'message'=>"Error, please try again later!"];
    }

    public function destroy($id)
    {
        $item = $this->model->find($id);
        if ($item) {
            $this->model->destroy($id);
            return ['data'=>$item, 'message'=>"Delete successful!"];
        }
        return ['data'=>null, 'message'=>"Error, please try again later!"];
    }
}