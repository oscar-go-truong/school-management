<?php
namespace App\Helpers;

class Message 
{
    static function error() {
        return 'Error, please try again later!';
    }

    static function createSuccessfully($model){
        return 'Create '.$model.' successfully!';
    }

    static function updateSuccessfully($model)
    {
        return 'Update '.$model.' successfully!';
    }

    static function updateStatusSuccessfully($model)
    {
        return 'Update status '.$model.' successfully!';
    }

    static function deleteSuccessfully($model)
    {
        return 'Delete '.$model.' successfully!';
    }

    static function rejectRequestSuccessfully()
    {
        return 'Reject request successfully!';
    }

    static function approveRequestSuccessfully()
    {
        return 'Approve request successfully!';
    }

    static function fileUploadIsInvalid()
    {
        return "File upload is invalid!";
    }

    static function importFileSuccessfully()
    {
        return 'File import successful!';
    }

    static function conflictTimeWithCourse($course){
        return 'User conflict time with course <b>'.$course->course->subject->name.' - '.$course->course->name.'</b>!';
    }

    static function userWasJoinedSubjectInThisYear($user, $course, $newCourseId){
        return 'User has joined class <b>'.$course->course->name.'</b> of subject <b>'.$course->course->subject->name.'</b>. Do you want to change the class to <b>'.$course->name.'</b>?<div class="d-flex justify-content-center">
        <button class="btn btn-secondary mr-3">No</button> 
        <button class="btn btn-primary ml-3" data-id="'.$course->id.'" data-newCourseId="'.$newCourseId.'" data-oldCourseId="'.$course->course_id.'" data-userId="'.$user->id.'" id="changeCourse">Yes</button></div>
       </div>';
    }
}
