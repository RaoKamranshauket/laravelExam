@extends('layouts.app')
@section('styles')
@endsection
@section('content')
<div class="pagetitle">
    <h1>Reporting | Basic Summary</h1>
    <nav class="d-flex justify-content-between pb-3">
        <ol class="breadcrumb ">
            <li class="breadcrumb-item "><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
            <li class="breadcrumb-item">Reporting</li>
        </ol>
    </nav>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <form action="{{ route('admin.reporting.index') }}" method="get">
                        <div class="card-body mt-2">
                            <div class="row justify-content-center">
                                <div class="col-6">
                                    <label for="select-exam" class="form-label">Select Exam</label>
                                    <select class="form-control" id="select-exam" name="exam_id">
                                        <option select="">Select Exam</option>
                                        @foreach($exams as $ex)
                                        <option value="{{ encode($ex->id) }}">{{ $ex->name }}</option>
                                        @endforeach
                                    </select>

                                </div>
                                <div class="col-6 m-2">
                                    <label for="select-student" class="form-label">Select Student</label>
                                    <select class="form-control" id="select-student" name="student_id">
                                        <option value="">Select Student</option>
                                    </select>

                                </div>
                                <div class="col-6 m-2 text-center">
                                    <button class="btn btn-primary" type="submit">Submit</button>
                                </div>

                            </div>
                        </div>
                        </from>
                </div>

            </div>
        </div>
        @if(isset($result))
        <div class="row">
            <div class="card">
                <div class="card-body mt-2">
                    @if(isset($exam) && isset($student))
                    <div class="col-6 text-center">
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">Exam Name <span class="">{{ $exam->name }}</span></li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">Student Name <span class="">{{ $student->first_name." ".$student->last_name }}</span></li>
                        </ul>
                    </div>
                    @endif
                    <div class="col-lg-12">



                        <table class="table table-striped data-table" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Question</th>
                                    <th>Answer</th>

                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $sections = [];
                                @endphp
                                @foreach($result as $detail)
                                @if(!in_array($detail['section_name'],$sections))
                                <tr>
                                    <th colspan="2">{{ $detail['section_name'] }}</th>
                                </tr>
                                @php array_push($sections,$detail['section_name']) @endphp


                                @endif

                                <tr>
                                    <td>{{ $detail['question'] }}</td>
                                    <td>{{  $detail['user_answer'] }}</td>
                                </tr>

                                @endforeach
                            </tbody>
                        </table>



                    </div>
                </div>
            </div>
        </div>
        @endif
    </section>
</div>
@include('includes.confirm_modal')
@endsection
@section('scripts')

@include('reporting.js_func')
@endsection
