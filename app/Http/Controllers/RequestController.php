<?php

namespace App\Http\Controllers;

use App\Enums\RequestStatusContants;
use App\Enums\RequestTypeContants;
use App\Services\RequestService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    protected $requestService;

    public function __construct(RequestService $requestService){
        $this->requestService = $requestService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() : View
    {
        $status = RequestStatusContants::asArray();
        return view('request.index', compact('status'));
    }

    public function getTable(Request $request)
    {
        $input = $request->input();
        $requests = $this->requestService->getTable($input);
        return response()->json($requests);
    }

    public function changeStatus(Request $request, $id)
    {
        $status = $request->status;
        $resp = $this->requestService->changeStatus($id, $status);
        return response()->json($resp);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) 
    {
        $request = $this->requestService->getById($id);
        $content = $this->requestService->getContent($request);
        $status = RequestStatusContants::asArray();
        if($request->type === RequestTypeContants::BOOK_ROOM_OR_LAB)
            return view('request.bookingRoomRequestDetail', compact('request', 'content','status'));
        else if($request->type === RequestTypeContants::REVIEW_GRADES)
            return view('request.reviewScoreRequestDetail', compact('request', 'content','status'));
        else if($request->type === RequestTypeContants::SWITCH_COURSE)
            return view('request.switchCourseRequestDetail', compact('request', 'content','status'));
        else 
            return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
