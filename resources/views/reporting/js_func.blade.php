<script>
    $(function() {
        $(document).on('change', '#select-exam', loadStudents);
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
            url: "{{ route('admin.reporting.get_students') }}",
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

            },
        });
    }
</script>
