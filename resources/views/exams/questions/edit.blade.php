@extends('layouts.app')
@section('styles')
@endsection
@section('content')
<div class="pagetitle">
    <h1>Edit Question Types</h1>
    <nav class="d-flex justify-content-between   pb-3">
        <ol class="breadcrumb ">
            <li class="breadcrumb-item "><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{route('admin.exams.index')}}">Exams</a></li>
            <li class="breadcrumb-item"><a href="{{route('admin.exams.edit',['id' => encode($section->exam->id)])}}">Edit Exam</a></li>
            <li class="breadcrumb-item">Edit {{ $section->section_name }} Questions</li>

        </ol>
    </nav>
    <section class="section">
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <form class="row g-3 mt-3" id="edit-options-form" name="edit-options-form" method="post" action="{{ route('admin.exam_question.update') }}">
                            @csrf
                            <input type="hidden" name="exam_id" value="{{ encode($section->exam->id) }}" />
                            @foreach($questions as $q)

                            <div class="col-12 question-container">
                                <div class="d-flex justify-content-around pt-3">
                                   <i class="bi bi-trash3 text-primary cursor-pointer m-1 delete-record" onClick="deleteRecord('<?php echo $q->id; ?>,<?php echo $section->id; ?>')"><strong>Q: {{ $q->question }}</strong></i>
                                    <div>
                                        <input type="radio" class="answers cursor-pointer" name="question_types[{{ encode($q->id) }}]" value="{{ config('constants.EXAM_QUESTION_TYPE.RADIO') }}" {{ config('constants.EXAM_QUESTION_TYPE.RADIO') == $q->question_type ? 'checked':''  }}>
                                        <label for="a">Options</label>
                                    </div>
                                    <div>
                                        <input type="radio" class="answers cursor-pointer" name="question_types[{{ encode($q->id) }}]" value="{{ config('constants.EXAM_QUESTION_TYPE.TEXT') }}" {{ config('constants.EXAM_QUESTION_TYPE.TEXT') == $q->question_type ? 'checked':''  }}>
                                        <label for="a">Text Box</label>
                                    </div>

                                </div>

                            </div>
                            <div class="col-12 text-center justify-content-center">
                                <select name="option_types[{{ encode($q->id) }}]" class="form-control">
                                    <option value="">Select Option</option>
                                    <option value="ABCD" {{ $q->option_type == 'ABCD' ? 'selected':'' }}>ABCD</option>
                                    <option value="EFGH" {{ $q->option_type == 'EFGH' ? 'selected':'' }}>EFGH</option>
								    <option value="ABCDE" {{ $q->option_type == 'ABCDE' ? 'selected':'' }}>ABCDE</option>
								    <option value="FGHJ" {{ $q->option_type == 'FGHJ' ? 'selected':'' }}>FGHJ </option>
                                    <option value="FGHJK" {{ $q->option_type == 'FGHJK' ? 'selected':'' }}>FGHJK</option>
                                </select>
                            </div>
                            <hr>
                            @endforeach
                            <div class="text-center">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form><!-- Vertical Form -->

                    </div>
                </div>

            </div>
        </div>
    </section>
</div>
@include('includes.confirm_modal')
@endsection
@section('scripts')

<script>
    $(function() {
        hideOptions();
        $(document).on('change','input[type=radio]',handleChange);
    });
    function handleChange()
    {
          var value = $(this).val();
          if(value == 2){
            $(this).closest('.question-container').next().hide();
          }else{
            $(this).closest('.question-container').next().show();
          }
    }

    function hideOptions() {
        $("#edit-options-form input[type=radio]:checked").each(function() {
            if ($(this).val() == 2) {
                 // Hide Element
                 $(this).closest('.question-container').next().hide();
            }
        });
    }

    function deleteRecord(id,section_id) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{ route('admin.exam_question.delete') }}",
            type: 'POST',
            data: {
                "Qid": id,"Sid": section_id,
            },
            success: function(response) {
            location.reload();
                if (response.success) {
                
                    new Notify({
                        title: 'Success',
                        text: response.message,
                        status: 'success', // can be warning, success , error
                        autoclose: true,
                    });
                } else if (response.success == false) {
                    new Notify({
                        title: 'Error',
                        text: response.message,
                        status: 'error', // can be warning, success , error
                        autoclose: true,
                    });
                }
            }
        });
    }
</script>
@endsection
