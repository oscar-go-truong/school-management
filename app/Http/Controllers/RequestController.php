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
        $input = $request->input();
        $resp = $this->requestService->storeReviewScoreRequest($input);
        return response()->json($resp);
    }

    public function storeSwitchCourseRequest(CreateSwitchCourseRequestRequest $request)
    {
        $input = $request->input();
        $resp = $this->requestService->storeSwitchcCourseRequest($input);
        return response()->json($resp);
    }

    public function storeEditExamScoresRequest(CreateEditExamsScoresRequesRequest $request)
    {
        $input = $request->input();
        $resp = $this->requestService->storeEditExamScoresRequest($input);
        return response()->json($resp);
    }
}
