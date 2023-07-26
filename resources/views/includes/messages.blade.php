@if(Session::has('success_message'))
<script>
     new Notify ({
        title: 'Success',
        text: "{{ Session::get('success_message') }}",
        status: 'success', // can be warning, success , error
        autoclose: true,
    });
</script>
@endif
@if(Session::has('error_message'))
<script>
     new Notify ({
        title: 'Error',
        text: "{{ Session::get('error_message') }}",
        status: 'error', // can be warning, success , error
        autoclose: true,
    });
</script>
@endif

@if(Session::has('warning_message'))
<script>
     new Notify ({
        title: 'Warning',
        text: "{{ Session::get('warning_message') }}",
        status: 'warning', // can be warning, success , error
        autoclose: true,
    });
</script>
@endif