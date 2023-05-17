<?php

namespace App\Http\Controllers;

use App\Enums\RequestStatusContants;
use App\Enums\RequestTypeContants;
use App\Http\Requests\CreateBookingRoomRequestRequest;
use App\Http\Requests\CreateEditExamsScoresRequesRequest;
use App\Http\Requests\CreateReviewScoreRequestRequest;
use App\Http\Requests\CreateSwitchCourseRequestRequest;
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
        $types = RequestTypeContants::asArray();
        return view('request.index', compact('status','types'));
    }

    public function getTable(Request $request)
    {
        $requests = $this->requestService->getTable($request);
        return response()->json($requests);
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
        $content = (object) $this->requestService->getContent($id);
        $status = RequestStatusContants::asArray();
        switch($request->type){
        case(RequestTypeContants::REVIEW_GRADES):
            $viewName ='request.reviewScoreRequestDetail';
            break;
        case(RequestTypeContants::SWITCH_COURSE):
            $viewName = 'request.switchCourseRequestDetail';
            break;
        case(RequestTypeContants::EDIT_EXAMS_SCORES):
            $viewName = 'request.editExamScoresRequestDetail';
        default: 
            return redirect()->back();
        }
        return view($viewName, compact('request', 'content','status'));
    }

    public function reject($id)
    {
        $resp = $this->requestService->reject($id);
        return response()->json($resp);
    }

    public function approve($id)
    {
        $resp = $this->requestService->approve($id);
        return response()->json($resp);
    }

    public function storeReviewScoreRequest(CreateReviewScoreRequestRequest $request)
    {
        $resp = $this->requestService->storeReviewScoreRequest($request);
        return response()->json($resp);
    }

    public function storeSwitchCourseRequest(CreateSwitchCourseRequestRequest $request)
    {
        $resp = $this->requestService->storeSwitchcCourseRequest($request);
        return response()->json($resp);
    }

    public function storeEditExamScoresRequest(CreateEditExamsScoresRequesRequest $request)
    {
        $resp = $this->requestService->storeEditExamScoresRequest($request);
        return response()->json($resp);
    }
}
