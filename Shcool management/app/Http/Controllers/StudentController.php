<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Classroom;
use App\Models\Level;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
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

        $students = Student::all();

        //$student = Student::find(1);

        //return $student->subjects;

        return view('students.index', compact('students'));



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

        return view('students.create', compact('classrooms', 'levels'));
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

        $validated = $request->validate($this->studentValidationRules([
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]));

        $student = Student::create([
            'name' => $validated['name'],
            'gender' => $validated['gender'],
            'age' => $validated['age'],
            'address' => $validated['address'],
            'classroom_id' => $validated['classroom_id'],
            'level_id' => $validated['level_id'],
        ]);

        User::create([
            'name' => $student->name,
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'userable_id' => $student->id,
            'userable_type' => 'Student'
        ]);

        if (Auth::check() && Auth::user()->hasRole('Admin')) {
            return redirect('/students');
        }
        
        return redirect('login');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        //
        $classrooms = Classroom::all();
        $levels = Level::all();

        return view('students.edit', compact('classrooms', 'levels', 'student'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Student $student)
    {
        //

        $input = $request->validate($this->studentValidationRules());

        $student->fill($input)->save();

        return redirect('/students');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        //

        $student->delete();

        return redirect('/students');


    }

    protected function studentValidationRules(array $extraRules = [])
    {
        return array_merge([
            'name' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female',
            'age' => 'required|integer|min:1',
            'address' => 'required|string|max:255',
            'classroom_id' => 'required|integer|exists:classrooms,id',
            'level_id' => 'required|integer|exists:levels,id',
        ], $extraRules);
    }
}
