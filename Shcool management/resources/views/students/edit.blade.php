@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            @include('partials.errors')

            <form action="/students/{{ $student->id }}" method="POST" role="form">
            {{ csrf_field() }}
                <legend>Edit a Student</legend>

                <div class="form-group">
                    <label for="">Name</label>
                    <input name="name" type="text" class="form-control" id="" placeholder="Enter Name" value="{{ old('name', $student->name) }}">
                </div>
                <div class="form-group">
                <label for="">SEX</label>
                <select name="gender" id="input" class="form-control" required="required">
                    <option {{ old('gender', $student->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                    <option {{ old('gender', $student->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                </select>
                </div>
                <div class="form-group">
                    <label for="">Age</label>
                    <input name="age" type="text" class="form-control" id="" placeholder="Enter Age" value="{{ old('age', $student->age) }}">
                </div>
                <div class="form-group">
                    <label for="">Address</label>
                    <input name="address" type="text" class="form-control" id="" placeholder="Enter Address" value="{{ old('address', $student->address) }}" >
                </div>

                <div class="form-group">
                <label for="">Classroom</label>
                <select name="classroom_id" id="inputClassroom_id" class="form-control" required="required">
                    @foreach ($classrooms as $classroom)
                    <option value="{{ $classroom->id }}" {{ old('classroom_id', $student->classroom_id) == $classroom->id ? 'selected' : '' }}>{{ $classroom->name }}</option>
                    @endforeach
                </select>
                </div>

                <div class="form-group">
                <label for="">Level</label>
                <select name="level_id" id="inputLevel_id" class="form-control" required="required">
                    @foreach ($levels as $level)
                    <option value="{{ $level->id }}" {{ old('level_id', $student->level_id) == $level->id ? 'selected' : '' }}>{{ $level->name }}</option>
                    @endforeach
                </select>
                </div>


                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>
@endsection
