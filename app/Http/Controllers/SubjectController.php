<?php

namespace App\Http\Controllers;

use App\Enums\StatusTypeContants;
use App\Http\Requests\CreateUpdateSubjectRequest;
use App\Services\CourseService;
use App\Services\SubjectService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    protected $subjectService;
    protected $courseService;

    public function __construct(SubjectService $subjectService, CourseService $courseService)
    {
        $this->subjectService = $subjectService;
        $this->courseService = $courseService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): View
    {
        return view('subject.index');
    }

    public function getTable(Request $request)
    {
        $subjects = $this->subjectService->getTable($request);
        return response()->json($subjects);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() : View
    {
       return view('subject.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUpdateSubjectRequest $request)
    {
        $input = $request->input();
        $resp = $this->subjectService->store($input);
        if($resp['data'] != null)
            return redirect('/subjects')->with('success',$resp['message']);
        else 
            return redirect()->back()->with('error',$resp['message']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): View
    {
        $subject = $this->subjectService->getById($id);
        return view('subject.detail', compact('subject'));
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) : View
    {
        $subject = $this->subjectService->getById($id);
        return view('subject.update', compact('subject'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateUpdateSubjectRequest $request, $id)
    {
        $input = $request->input();
        $subject = ['name' => $input['name'], 'descriptions'=>$input['descriptions']];
        $resp = $this->subjectService->update($id,$subject);
        if($resp['data'] != null)
            return redirect('/subjects')->with('success',$resp['message']);
        else 
            return redirect()->back()->with('error',$resp['message']);
    }

    public function changeStatus(Request $request, $id)
    {
        $status = $request->input('status', StatusTypeContants::ACTIVE);
        $resp = $this->subjectService->changeStatus($id, $status);
        return response()->json($resp);  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) : JsonResponse
    {
        $resp = $this->subjectService->destroy($id);
        return response()->json($resp);
    }
}
