@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            @include('partials.errors')

            <form action="/subjects" method="POST" role="form">
            {{ csrf_field() }}
                <legend>Create a new subject</legend>

                <div class="form-group">
                    <label for="inputSubjectName">Name</label>
                    <input name="name" type="text" class="form-control" id="inputSubjectName" placeholder="Enter subject name" value="{{ old('name') }}">
                </div>
                <div class="form-group">
                    <label for="inputSubjectScore">Final Degree</label>
                    <input name="score" type="number" step="0.01" min="0" class="form-control" id="inputSubjectScore" placeholder="Enter final degree" value="{{ old('score') }}">
                </div>
                <div class="form-group">
                <label for="inputlevel_id">Level</label>
                <select name="level_id" id="inputlevel_id" class="form-control" required="required">
                @foreach ($levels as $level)
                    <option value="{{ $level->id }}" {{ old('level_id') == $level->id ? 'selected' : '' }}>{{ $level->name }}</option>
                @endforeach
                </select>
                </div>
                <div class="form-group">
                <label for="inputteacher_id">Teacher</label>
                <select name="teacher_id" id="inputteacher_id" class="form-control" required="required">
                @foreach ($teachers as $teacher)
                    <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>{{ $teacher->name }}</option>
                @endforeach
                </select>
                </div>



                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>
@endsection
