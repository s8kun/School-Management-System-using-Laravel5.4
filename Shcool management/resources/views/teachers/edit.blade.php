@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            @include('partials.errors')

            <form action="/teachers/{{ $teacher->id }}" method="POST" role="form">
            {{ csrf_field() }}
                <legend>Edit a Teacher</legend>

                <div class="form-group">
                    <label for="">Name</label>
                    <input name="name" type="text" class="form-control" id="" placeholder="Enter Name" value="{{ old('name', $teacher->name) }}">
                </div>
                <div class="form-group">
                    <label for="inputGender">Gender</label>
                    <select name="gender" id="inputGender" class="form-control" required="required">
                        <option {{ old('gender', $teacher->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                        <option {{ old('gender', $teacher->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>
                <div class="form-group">
                <label for="inputLevel_id">Level</label>
                <select name="level_id" id="inputLevel_id" class="form-control" required="required">
                    @foreach ($levels as $level)
                    <option value="{{ $level->id }}" {{ old('level_id', $teacher->level_id) == $level->id ? 'selected' : '' }}>{{ $level->name }}</option>
                    @endforeach
                </select>
                </div>
                <div class="form-group">
                    <label for="">Experience</label>
                    <input name="experience" type="text" class="form-control" id="" placeholder="Enter Experience" value="{{ old('experience', $teacher->experience) }}">
                </div>
                <div class="form-group">
                    <label for="">Phone</label>
                    <input name="phone" type="text" class="form-control" id="" placeholder="Enter Phone" value="{{ old('phone', $teacher->phone) }}">
                </div>
                <div class="form-group">
                <label for="">Classroom</label>
                <select name="classroom_id" id="inputClassroom_id" class="form-control" required="required">
                    @foreach ($classrooms as $classroom)
                    <option value="{{ $classroom->id }}" {{ old('classroom_id', $teacher->classroom_id) == $classroom->id ? 'selected' : '' }}>{{ $classroom->name }}</option>
                    @endforeach
                </select>
                </div>


                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>
@endsection
