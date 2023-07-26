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
            <li class="breadcrumb-item ">Users</li>
        </ol>
        
        <a href="{{ route('admin.users.create') }}" class="d-flex btn btn-primary float-right">Add New User</a>
    </nav>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body mt-2">
                        <table class="table table-striped data-table" style="width:100%">
                            <thead>
                                <tr>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th width="100px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
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
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.users.index') }}",
            columns: [{
                    data: 'first_name',
                    name: 'first_name'
                },
                {
                    data: 'last_name',
                    name: 'last_name'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'type',
                    name: 'type'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });
    });

    function showConfirm() {
        const id = $(this).attr('data-id');
        const modal = $("#confirm-modal");
        modal.find("#record-id").val(id);
        modal.modal('show');
    }

    function deleteRecord() {
        const id = $("#confirm-modal").find("#record-id").val();
        $.ajax({
            headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{ route('admin.users.destroy') }}",
            type: 'POST',
            data: {
                "id": id,
            },
            success: function(response) {
                $("#confirm-modal").modal('hide');
                if(response.success){
                    $(".data-table").DataTable().ajax.reload();
                    new Notify ({
                                title: 'Success',
                                text: response.message,
                                status: 'success', // can be warning, success , error
                                autoclose: true,
                    });
                }else if(response.success == false){
                    new Notify ({
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