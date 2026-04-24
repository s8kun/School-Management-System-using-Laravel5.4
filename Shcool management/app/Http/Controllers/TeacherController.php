<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Classroom;
use App\Models\Level;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function __construct()
    {

           $this->middleware('role:Admin')->except('create', 'store');              
        
    }

    public function index()
    {
        //

        $teachers = Teacher::all();

        //return $teachers;

        return view('teachers.index', compact('teachers'));



    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

        $classrooms = Classroom::all();

        $levels = Level::all();

        return view('teachers.create', compact(['classrooms', 'levels']));
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

        $validated = $request->validate($this->teacherValidationRules([
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]));

        $teacher = Teacher::create([
            'name' => $validated['name'],
            'gender' => $validated['gender'],
            'classroom_id' => $validated['classroom_id'],
            'level_id' => $validated['level_id'],
            'experience' => $validated['experience'],
            'phone' => $validated['phone'],
        ]);

        User::create([
            'name' => $teacher->name,
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'userable_id' => $teacher->id,
            'userable_type' => 'Teacher'
        ]);


        if (Auth::check() && Auth::user()->hasRole('Admin')) {
            return redirect('/teachers');
        }
        
        return redirect('login');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function show(Teacher $teacher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function edit(Teacher $teacher)
    {
        //

        $classrooms = Classroom::all();
        $levels = Level::all();

        return view('teachers.edit', compact('teacher', 'classrooms', 'levels'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Teacher $teacher)
    {
        //

        $input = $request->validate($this->teacherValidationRules());

        $teacher->fill($input)->save();

        return redirect('/teachers');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function destroy(Teacher $teacher)
    {
        //

        $teacher->delete();

        return redirect('/teachers');


    }

    protected function teacherValidationRules(array $extraRules = [])
    {
        return array_merge([
            'name' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female',
            'classroom_id' => 'required|integer|exists:classrooms,id',
            'level_id' => 'required|integer|exists:levels,id',
            'experience' => 'required|string|max:255',
            'phone' => 'required|digits_between:7,10',
        ], $extraRules);
    }
}
