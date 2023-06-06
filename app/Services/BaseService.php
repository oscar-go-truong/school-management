<?php

namespace App\Services;

use App\Enums\PaginationContants;
use App\Enums\StatusTypeContants;
use App\Helpers\Message;

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

    public function getAllActive()
    {
        return $this->model->where('status', StatusTypeContants::ACTIVE)->get();
    }

    public function changeStatus($id, $request)
    {
        $status = $request->status;
        $update = $this->model->where('id', $id)->update(['status' => $status]);
        if ($update) {
            return ['data' => ['id' => $id], 'message' => Message::updateStatusSuccessfully("")];
        }
        return  ['data' => null, 'message' => Message::error()];
    }

    protected function orderNSearch($request, $query)
    {
        $limit = isset($request->limit) ? $request->limit : PaginationContants::LIMIT;
        // order by
        $isOrder = isset($request->orderBy) && isset($request->orderDirect);
        if ($isOrder) {
            $orderBy =  $request->orderBy ;
            $orderDirect = $request->orderDirect;
                $query = $query->orderBy($orderBy, $orderDirect);
        }
        if (isset($request->search)) {
            $searchColumn = $request->search['column'];
            $searchType = $request->search['type'];
            $searchKey = $request->search['key'];
            $query = $query->where($searchColumn, $searchType, '%' . $searchKey . '%');
        }
        $models =  $query->paginate($limit);
        $result = [
            'data' => $models->items(),
            'from' => $models->firstItem(),
            'to' => $models->lastItem(),
            'last_page' => $models->lastPage(),
            'total' => $models->total()
        ];
        return $result;
    }
    public function getById($id)
    {
        return $this->model->where('id', $id)->first();
    }


    public function store($request)
    {
        $result =  $this->model->create($request->input());
        if ($result) {
            return ['data' => ['id' => $result->id], 'message' => Message::createSuccessfully("")];
        }
        return  ['data' => null, 'message' => Message::error()];
    }

    public function update($id, $arg)
    {
        $result = $this->model->where('id', $id)->update($arg);
        if ($result) {
            return ['data' => ['id' => $id], 'message' => Message::updateStatusSuccessfully("")];
        }
        return  ['data' => null, 'message' => Message::error()];
    }

    public function destroy($id)
    {
        $model = $this->model->find($id);
        if ($model) {
            $this->model->destroy($id);
            return ['data' => ['id' => $model->id], 'message' => Message::deleteSuccessfully("")];
        }
        return ['data' => null, 'message' => Message::error()];
    }
}
