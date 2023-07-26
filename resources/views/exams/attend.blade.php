<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{asset('assets/css/exam.css')}}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <link href="https://www.jqueryscript.net/demo/Simple-Step-By-Step-Site-Tour-Plugin-Intro-js/example/css/demo.css" rel="stylesheet">
    <link href="https://www.jqueryscript.net/demo/Simple-Step-By-Step-Site-Tour-Plugin-Intro-js/introjs.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <style type="text/css">
        .introjs-helperNumberLayer{
            width: 25px !important;
            height: 25px !important;
        }
        .introjs-skipbutton{
            margin-right: 5px;
             color: #333;
        }

        .btn-outline-light{
                color: var(--bs-btn-hover-color);
    background-color: var(--bs-btn-hover-bg);
    border-color: var(--bs-btn-hover-border-color);
}

        }
    </style>
</head>

<body>
    <div id="hi">
<div class="">

    <nav class="navbar navbar-expand-lg navbar-color">
        <div class="container-fluid test">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                </ul>

                <button data-step="3" data-intro="Instructions (¾):Countdown for your exam time" data-position='left' class="btn btn-outline-light" type="submit">Time remaining <span id="counter" class=".btn-outline-light">{{ $section_remaining_time }}</span></button>
            </div>
        </div>
    </nav>
    @if(count($exam->sections) > 1)
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 text-end">

                </div>
                <div class="col-md-6">

                </div>
            </div>
        </div>

    @endif
    <div class="container-fuild">
        <div class="row col-12">
            <div class="col-lg-3 scroll-col" data-step="1" data-intro="Instructions (¼):Please click the correct answer" data-position='right'>

                 <button type="button" class="btn btn-primary m-4 justify-content-center m-2" data-step="4" data-intro="Instructions (4/4):If you have a question or experiencing a technical difficult, please contact Ken Cheng at 267.438.6982 or email him at kcheng@competitiveedgetutoring.com for IT support" data-position='bottom' @if($next_section !=null) style="display:none;" @endif id="submit-exam">SUBMIT</button>
                @php
                    $collection = isset($exam_result->exam_result_details) ? $exam_result->exam_result_details:[];
                    $section_id = isset($exam_result) ? $exam_result->section_id : null;
                @endphp
                @foreach($questions as $q)
                    <div class="d-flex justify-content-around pt-3">
                        <span>{{ $q->question }}</span>
                        @if($q->question_type == config('constants.EXAM_QUESTION_TYPE.RADIO'))
                            @foreach($q->active_options as $op)
                                @php

                                    $checked = false;
                                    if(isset($collection) && !empty($collection)){
                                    $checked = $collection->contains(function ($item, $key) use($q,$op,$section,$section_id){
                                    return $item->question_id == $q->id && $item->option_id == $op->id && $section->id == $section_id;
                                    });
                                    }



                                @endphp

                                <div>
                                    <input type="radio" class="answers" name="answer_{{ encode($q->id) }}" value="{{ encode($op->id) }}" {{ $checked ? 'checked':'' }}>
                                    <label for="a">{{ $op->option }}</label>
                                </div>
                            @endforeach
                        @elseif($q->question_type == config('constants.EXAM_QUESTION_TYPE.TEXT'))
                            @php
                                $input_value = '';
                                if(isset($collection) && !empty($collection)){
                                $exam_details = $collection->filter(function ($item, $key) use($q,$exam_result){
                                return $item->question_id == $q->id && $item->exam_result_id == $exam_result->id;
                                })->first();
                                $input_value = isset($exam_details) ? $exam_details->text_answer : '';

                                }
                            @endphp

                            <div>
                                <input type="text" class="answer-text form-control" name="answer_{{ encode($q->id) }}" value="{{ $input_value }}">

                            </div>
                        @endif
                    </div>
                @endforeach

                <button type="button" class="btn btn-primary m-2" id="next-btn" @if($next_section==null) @endif>SUBMIT</button>
            </div>
            <div class="col-lg-9 mt-3">
                <div class="accordion" id="accordionExample">

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button" type="button"  data-bs-target="#collapse-{{ encode($section->id) }}" aria-expanded="true" aria-controls="collapseOne">
                                {{ $section->section_name }}
                            </button>
                        </h2>
                        <div id="collapse-{{ encode($section->id) }}" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                            <span style="top: 85px !important" data-step="2" data-intro="Instructions (2/4):Double click the selected answer to undo" data-position='right'></span>
                            <div class="accordion-body">
                                {{-- <iframe src="{{ route('file.show') }}" data-file="file:///C:/xampp/htdocs/laravel/html/public/uploads/blcLUPNxgC1681684084.pdf" width="900" height="700"></iframe> --}}
                                {{-- <iframe src="{{ route('file.show') }}" data-file="{{ asset('public/uploads/'.$section->file) }}" width="900" height="700"></iframe> --}}
                                <iframe src="{{ route('file.show', ['file' => $pdfData ]) }}" width="900" height="700"></iframe>

                                {{-- <iframe src="{{ $pdfData }}" width="900" height="700"></iframe> --}}
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>


</div>
<form id="submit-exam-form" method="POST" action="{{ route('exam.submit') }}">
    @csrf
    <input type="hidden" name="exam_id" value="{{ encode($exam->id) }}" />
    <input type="hidden" name="section_id" value="{{ encode($section->id) }}" />
    <input type="hidden" name="student_id" value="{{ $user_id }}" />
</form>
<form id="submit-section-form" method="POST" action="{{ route('exam.section.submit') }}">
    @csrf
    <input type="hidden" name="exam_id" value="{{ encode($exam->id) }}" />
    <input type="hidden" name="section_id" value="{{ encode($section->id) }}" />
    <input type="hidden" name="next_section" value="{{ $next_section }}" />
    <input type="hidden" name="student_id" value="{{ $user_id }}" />
</form>
<div class="modal modal-dialog-centered" tabindex="-1" id="confirm-modal" style="display:none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure want to submit this exam?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                <button type="button" class="btn btn-primary" onclick="submitExam();">Yes</button>
            </div>
        </div>
    </div>
</div>

<div class="modal modal-dialog-centered" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" id="welcome-modal"
     style="display:none; z-index: 999999">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Welcome</h5>
            </div>
            <div class="modal-body">
                <p>Please refrain from using your browser's navigation buttons during the test as it will invalidate your answers.</p>
                <p>You are taking the {{ $exam->name }}.</p>
                <p>During the test, you'll find the answer choices on the left. You can submit your answers with the submit button, or they will be automatically submitted at the end of the allowed time.</p>
                <p>The next section lasts {{ $section->time }} minutes and has {{ $section->questions }} questions.</p>
                <p>You have <span id="start-timer">10:00 </span>until the next section starts automatically.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" id="continue">Continue</button>
            </div>
        </div>
    </div>
</div>

</div>
<script type="text/javascript" src="{{ URL::asset('public/assets/js/intro.js') }}"></script>
<script type="text/javascript">
    (function(global) {
        if (typeof(global) === "undefined") {
            throw new Error("window is undefined");
        }
        var _hash = "!";
        var noBackPlease = function() {
            global.location.href += "#";

            // Making sure we have the fruit available for juice (^__^)
            global.setTimeout(function() {
                global.location.href += "!";
            }, 50);
        };
        global.onhashchange = function() {
            if (global.location.hash !== _hash) {
                global.location.hash = _hash;
            }
        };
// global.onbeforeunload = function() {
//   return "Are you sure you want to refresh the page?";
// };

        global.onload = function() {
            noBackPlease();

            // Disables backspace on page except on input fields and textarea..
            document.body.onkeydown = function(e) {
                var elm = e.target.nodeName.toLowerCase();
                if (e.which === 8 && (elm !== 'input' && elm !== 'textarea')) {
                    e.preventDefault();
                }
                // Stopping the event bubbling up the DOM tree...
                e.stopPropagation();
            };
        }
    })(window);
</script>
<script>
    var TimerStart = false;
    var startTimer = "{{$break_duration}}";
    const SHOW_POPUP = "{{ $show_popup }}";
    var complete_steps = "{{ $complete_steps }}";
    $(function() {
        if (SHOW_POPUP==0) {
            $("#welcome-modal").modal('show');
        } else {

            startExamTimer();
            TimerStart = true;
        }

        // disable radios
        // $(":radio").click(function() {
        //     var radioName = $(this).attr("name"); //Get radio name
        //     $(":radio[name='" + radioName + "']").attr("disabled", true); //Disable all with the same name
        // });
        // disable input text
        // $(".answers-text").blur(function() {
        //     //$(this).attr("disabled", true);
        // });
        // check selected
        // $("input[type=radio]:checked").each(function() {
        //     const radioName = $(this).attr('name');
        //     $(":radio[name='" + radioName + "']").attr("disabled", true);

        // });

    });
    var EXAM_ID = "{{ $exam_id }}";
    var USER_ID = "{{ $user_id }}";
    var timer2 = "{{ $section_remaining_time }}";
    var SECTION_ID = "{{ encode($section->id) }}";
var sectionCount=0;
    var intervalId = window.setInterval(function() {
        var timer = startTimer.split(':');
        //by parsing integer, I avoid all extra string processing
        var minutes = parseInt(timer[0], 10);
        var seconds = parseInt(timer[1], 10);
        --seconds;
        minutes = (seconds < 0) ? --minutes : minutes;
        seconds = (seconds < 0) ? 59 : seconds;
        seconds = (seconds < 10) ? '0' + seconds : seconds;
        //minutes = (minutes < 10) ?  minutes : minutes;
        $('#start-timer').html(minutes + ':' + seconds + " ");
        if (minutes < 0) {
            clearInterval(intervalId);
        }
        //check if both minutes and seconds are 0
        if ((seconds <= 0) && (minutes <= 0)) {
            clearInterval(intervalId);
            // Check the next section existance
            startSection();
            $("#welcome-modal").modal('hide');
        };
        startTimer = minutes + ':' + seconds;

    }, 1000);


    //
    $(function() {
        $(document).on('click', '#continue', function() {
            console.log(complete_steps);
            if (complete_steps == 0) {
    introJs().start();
  }else{
startSection();
  }
        });
        $(document).on('change', '.answers', handleChange);
        $(document).on('keyup', '.answer-text', handleChange);
        $(document).on('click', '#submit-exam', showConfirmModel);
        $(document).on('click', '#next-btn', function() {
            submitSection();
        });
        $(document).on('dblclick', '.answers', function() {
            const element = $(this);
            const name = $(this).attr('name');
            $(":radio[name='" + name + "']").prop("checked", false);
            var fields = element.attr('name').split('_');
            var optionId = element.val();
            var questionId = fields[1];

            var data = {
                option_id: optionId,
                question_id: questionId,
                exam_id: EXAM_ID,
                user_id: USER_ID,
                section_id: SECTION_ID,
                action: 'remove',
            };
            ajaxCall(data);

        });
    });
    // alert(sessionStorage.getItem("minutes")+'='+sessionStorage.getItem("seconds"));
    function startExamTimer() {

  var timer = timer2.split(':');
  var minutes = parseInt(timer[0], 10);
  var seconds = parseInt(timer[1], 10);

  var interval = setInterval(function() {
    seconds--;

    if (seconds < 0) {
      minutes--;
      seconds = 59;
    }

    seconds = (seconds < 10) ? '0' + seconds : seconds;
    minutes = (minutes < 10) ? ' ' + minutes : minutes;

    $('#counter').html(minutes + ':' + seconds);

    if (minutes <= 0 && seconds <= 0) {

      clearInterval(interval);
      submitSection();

      // Check the next section existence
      if ($('#next-btn').css('display') != 'none') {

        document.getElementById("next-btn").click();
      } else {

        submitExam();
      }
    }

    timer2 = minutes + ':' + seconds;
  }, 1000); // Change interval duration to 2000 milliseconds (2 seconds)
}


    function showConfirmModel() {
        $("#confirm-modal").modal('show');
    }

    function submitExam() {
        document.getElementById('hi').innerHTML='submitexam function call';
        $("#submit-exam-form").submit();
    }

    function submitSection() {

        $("#submit-section-form").submit();
    }

    function handleChange() {
        var element = $(this);
        // Check element type
        var element_type = element.attr('type');
        var name = $(this).attr('name');

        var fields = element.attr('name').split('_');
        var optionId = element.val();
        var questionId = fields[1];

        var data = {
            option_id: optionId,
            question_id: questionId,
            exam_id: EXAM_ID,
            user_id: USER_ID,
            section_id: SECTION_ID,
            action: 'save',
        };

        ajaxCall(data);
    }

    function ajaxCall(data) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: "POST",
            url: "{{ route('exam.result.save') }}",
            data: data
        });
    }

    function startSection()
    {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: "POST",
            url: "{{ route('exam.continue.section') }}",
            data: {
                exam_id: EXAM_ID,
                user_id: USER_ID,
                section_id: SECTION_ID
            },
            success: function(response) {
                if (response.success) {

                    startExamTimer();
                    TimerStart = true;
                } else {
                    alert('Sorry,Something went wrong Please try again later');
                }

            }
        });
    }
    /*
    tutorial session complete
     */
    $(function () {
        $(".introjs-skipbutton").hide();
        $(".introjs-prevbutton").hide();
    })
     $(document).on('click', '.introjs-skipbutton', function() {
    startSection();
        });
    $(document).on("click",".introjs-nextbutton",function () {
        var NbrLyer=$(this).parents(".introjs-helperLayer").find(".introjs-helperNumberLayer").text();
        if(NbrLyer==3){
            $(".introjs-nextbutton").text('Finish');
            $(".introjs-tooltip").css("min-width","500px");
            $(".introjs-tooltip").css("min-width","500px");
            $(".introjs-tooltip").css("text-align","center");
        }
        if(NbrLyer==4){
//            submit-examhideementById("submit-examhide");

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: "POST",
                url: "{{ route('exam.complete_steps') }}",
                data: {
                    exam_id: EXAM_ID,
                    user_id: USER_ID,
                    section_id: SECTION_ID,
                    step_complete: true
                },
                success: function(response) {
                    step_complete: true
                    startSection();
                }
            });
        }
    });
</script>

</body>
<script>

</script>
</html>
