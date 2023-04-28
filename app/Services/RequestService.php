<?php

namespace App\Services;

use App\Enums\RequestTypeContants;
use App\Models\Request;

class RequestService extends BaseService {

    public function getModel(){
        return Request::class;
    }

    public function getTable($input){
        $query = $this->model->with('userRequest')->with('userApprove');
        $requests = $this->orderNSearch($input, $query);
        foreach($requests as $request){
            $type = RequestTypeContants::getKey($request->type);
            $type = str_replace('_', ' ', $type);
            $type = strtolower($type);
            $type = ucwords($type);
            $request->type = $type;
        }

        return $requests;
    }
}