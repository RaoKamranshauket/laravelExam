@extends('layouts.app')
@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css" />
@endsection
@section('content')
<div class="pagetitle">
    <h1>All Users</h1>
    <nav class="d-flex justify-content-between   pb-3">
        <ol class="breadcrumb ">
            <li class="breadcrumb-item "><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
            <li class="breadcrumb-item">Exams</li>
        </ol>
        
        <!-- <a href="{{ route('admin.exams.create') }}" class="d-flex btn btn-primary float-right">Add New Exam</a> -->
    </nav>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body mt-2">
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
                                @foreach($exam as $detail)
                                @if(!in_array($detail->section_name,$sections))
                                 <tr>
                                    <th colspan="2">{{ $detail->section_name }}</th>
                                </tr>
                                @php array_push($sections,$detail->section_name) @endphp
                                @continue

                                @endif
                                   
                                   <tr>
                                      <td>{{ $detail->question }}</td>
                                      <td>{{ $detail->option }}</td>
                                   </tr>
                                  
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>
@include('includes.confirm_modal')
@endsection
@section('scripts')
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(function() {

        $(document).on('click', '.delete-record', showConfirm);
        $(document).on('click', '.delete', deleteRecord);

        var table = $('.data-table').DataTable({
            // processing: true,
            // serverSide: true,
            // ajax: "{{ route('admin.exams.index') }}",
            columns: [{
                    data: 'question',
                    name: 'question'
                },
                {
                    data: 'answer',
                    name: 'answer'
                },
                // {
                //     data: 'time_limit',
                //     name: 'time_limit'
                // },
                // {
                //     data: 'questions',
                //     name: 'questions'
                // },
                // {
                //     data: 'action',
                //     name: 'action',
                //     orderable: false,
                //     searchable: false
                // },
            ]
        });
    });
</script>
@endsection