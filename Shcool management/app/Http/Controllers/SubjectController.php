<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Level;
use App\Models\Teacher;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $subjects = Subject::all();

        //return $subjects;

        return view('subjects.index', compact('subjects'));



    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

        $levels = Level::all(); 
        $teachers = Teacher::all(); 


        return view('subjects.create', compact('levels', 'teachers'));
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

        Subject::create($request->validate($this->subjectValidationRules()));

        return redirect('/subjects');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function show(Subject $subject)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function edit(Subject $subject)
    {
        //
        $levels = Level::all();
        $teachers = Teacher::all();

        return view('subjects.edit', compact('subject', 'levels', 'teachers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subject $subject)
    {
        //

        $input = $request->validate($this->subjectValidationRules());

        $subject->fill($input)->save();

        return redirect('/subjects');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subject $subject)
    {
        //

        $subject->delete();

        return redirect('/subjects');


    }

    protected function subjectValidationRules()
    {
        return [
            'name' => 'required|string|max:255',
            'score' => 'required|numeric|min:0',
            'level_id' => 'required|integer|exists:levels,id',
            'teacher_id' => 'required|integer|exists:teachers,id',
        ];
    }
}
