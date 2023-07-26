@extends('layouts.app')
@section('styles')
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Register Exam</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.exams.index') }}">Exams</a></li>
                <li class="breadcrumb-item active">Register Exam</li>
            </ol>
        </nav>
        <section class="section">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body" id="card-body">
                            <form class="row g-3 mt-3" name="register_exam_form" method="post"
                                action="{{ route('admin.exams.assign_student.store') }}" enctype="multipart/form-data">
                                @csrf

                                <div class="col-12">
                                    <label for="select-exam" class="form-label">Select Exam</label>
                                    <select class="form-control js-example-basic-single" name="exam_id" id="select-exam"
                                        autocomplete="off">
                                        <option value="">Select a exam...</option>

                                    </select>
                                </div>
                                <div class="col-12">
                                    <label for="select-teacher" class="form-label">Select Teacher</label>
                                    <select class="form-control" name="teacher_id" id="select-teacher" autocomplete="off">
                                        <option value="">Select a Teacher...</option>

                                    </select>
                                </div>
                                <div class="col-12">
                                    <label for="select-student" class="form-label">Select Student</label>
                                    <select class="form-control" name="student_id" id="select-student" autocomplete="off">
                                        <option value="">Select a Student...</option>

                                    </select>
                                </div>
                                <div class="text-center">
                                    <a href="{{ route('admin.exams.index') }}" class="btn btn-secondary">Cancel</a>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form><!-- Vertical Form -->

                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>
@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"
        integrity="sha512-rstIgDs0xPgmG6RX1Aba4KV5cWJbAMcvRCVmglpam9SoHZiUCyQVDdH2LPlxoHtrv17XWblE/V/PP+Tr04hbtA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/additional-methods.min.js"
        integrity="sha512-6S5LYNn3ZJCIm0f9L6BCerqFlQ4f5MwNKq+EthDXabtaJvg3TuFLhpno9pcm+5Ynm6jdA9xfpQoMz2fcjVMk9g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.js-example-basic-single').select2({
                ajax: {
                    // url: 'http://ceoe/api/list',
                    url: '{{ url('api/list') }}',
                    data: function(params) {
                        var query = {
                            search_text: params.term,
                            type: 'public'
                        }
                        return query;
                    },
                    processResults: function(data) {

                        console.log(data);
                        var data = $.map(data.data, function(exam) {
                            exam.id = exam.id
                            exam.text = exam.name
                            return exam
                        });

                        return {
                            results: data
                        };
                    }
                }
            });
        });

        var section_row = 1;
        $(function() {

            $(document).on('change', '#select-exam', loadStudents);

            $("form[name='register_exam_form']").validate({
                // Specify validation rules
                rules: {
                    exam_id: {
                        required: true,
                    },
                    tutor_id: {
                        required: true,
                    },
                    student_id: {
                        required: true,
                    },
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });
        });

        function loadStudents() {
            var exam_id = $(this).val();
            if (exam_id == "") {
                return;
            }

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: "POST",
                url: "{{ route('admin.exams.load_students') }}",
                data: {
                    exam_id
                },
                success: function(response) {
                    $("#select-student").find('option').not(':first').remove();
                    // add students
                    var mySelect = $('#select-student');
                    $.each(response.students, function(val, text) {
                        mySelect.append(
                            $('<option></option>').val(text.id).html(text.name)
                        );
                    });
                    $('#select-student').select2()
                    // add teachers
                    var mySelect = $('#select-teacher');
                    $.each(response.teachers, function(val, text) {
                        mySelect.append(
                            $('<option></option>').val(text.id).html(text.name)
                        );
                    });
                    $('#select-teacher').select2()
                },
            });
        }
    </script>
@endsection
