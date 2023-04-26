<?php

namespace App\Services;

use App\Models\Request;

class RequestService extends BaseService {

    public function getModel(){
        return Request::class;
    }

    public function getTable($request){
        $query = $this->model->with('userRequest')->with('userApprove');
        $requests = $this->orderNSearch($request, $query);
        return $requests;
    }
}