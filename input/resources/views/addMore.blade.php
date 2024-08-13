<!DOCTYPE html>
<html>
<head>
    <title>Laravel - Dynamically Add or Remove Input Fields Using jQuery</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

<div class="container">
    <h2 align="center">Laravel - Dynamically Add or Remove Input Fields Using jQuery</h2>
    <div class="form-group">
        <form action="addmore" method="post" name="add_name" id="add_name">
            @csrf
            <div class="alert alert-danger print-error-msg" style="display:none">
                <ul></ul>
            </div>

            <div class="alert alert-success print-success-msg" style="display:none">
                <ul></ul>
            </div>
            <div class="container">
                <div class="row" id="dynamic_field">
                    <div class="col-md-4">
                        <input type="text" name="certificate[]" placeholder="Certificate Name" class="form-control name_list" />
                    </div>
                    <div class="col-md-4">
                        
                        <input type="date" id="dateInput1" name="date[]" placeholder="Certificate Date" class="form-control name_list" />
                    </div>
                    <div class="col-md-4">
                        <input type="number" name="score[]" placeholder="Score" class="form-control name_list" />
                        <button type="button" name="add" id="add" class="btn btn-success" style="float: inline-end;margin: 10px 0px;">Add More</button>
                    </div>
                </div>
            </div>
            <input type="submit" name="submit" id="submit" class="btn btn-info" value="Submit" style="float: inline-end;margin-top: 10px;" />
        </form>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        var postURL = "{{ url('addmore') }}";
        var i = 1;

        // Function to get the date in YYYY-MM-DD format
        function getFormattedDate(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are zero-indexed
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

        // Get today's date
        const today = new Date();
        const minDate = getFormattedDate(today);

        // Get the date 20 years from now
        const futureDate = new Date();
        futureDate.setFullYear(today.getFullYear() + 20);
        const maxDate = getFormattedDate(futureDate);

        // Set min and max dates for all date inputs
        function setDateInputConstraints() {
            $('input[type="date"]').each(function() {
                $(this).attr('min', minDate);
                $(this).attr('max', maxDate);
            });
        }

        // Set constraints for the initial date input
        setDateInputConstraints();

        $('#add').click(function() {
            i++;
            $('#dynamic_field').append('<div class="row dynamic-added" id="row' + i + 
                '"><div class="col-md-4"><input type="text" name="certificate[]" placeholder="Certificate Name" class="form-control name_list" /></div>' +
                '<div class="col-md-4"><input type="date" name="date[]" placeholder="Certificate Date" class="form-control name_list" /></div>' +
                '<div class="col-md-4"><input type="number" name="score[]" placeholder="Score" class="form-control name_list"  min="0" max="10"  />' +
                '<button type="button" name="remove" id="' + i + 
                '" class="btn btn-danger btn_remove" style="float: inline-end;margin: 10px 0px;">X</button></div></div>');
            
            // Update date input constraints for newly added fields
            setDateInputConstraints();
        });

        $(document).on('click', '.btn_remove', function() {
            var button_id = $(this).attr("id");
            $('#row' + button_id).remove();
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#submit').click(function(event) {
    event.preventDefault(); // Prevent default form submission

    // Disable the button to prevent multiple clicks
    $('#submit').prop('disabled', true);

    $.ajax({
        url: postURL,
        method: "POST",
        data: $('#add_name').serialize(),
        dataType: 'json',
        success: function(data) {
            if (data.error) {
                printErrorMsg(data.error);
            } else {
                i = 1;
                $('.dynamic-added').remove();
                $('#add_name')[0].reset();
                $(".print-success-msg").find("ul").html('');
                $(".print-success-msg").css('display', 'block');
                $(".print-error-msg").css('display', 'none');
                $(".print-success-msg").find("ul").append('<li>Record Inserted Successfully.</li>');
            }
            // Re-enable the button after success or error
            $('#submit').prop('disabled', false);
        },
        error: function() {
            // Handle error
            $('#submit').prop('disabled', false);
        }
    });
});

        function printErrorMsg(msg) {
            $(".print-error-msg").find("ul").html('');
            $(".print-error-msg").css('display', 'block');
            $(".print-success-msg").css('display', 'none');
            $.each(msg, function(key, value) {
                $(".print-error-msg").find("ul").append('<li>' + value + '</li>');
            });
        }
    });
</script>


<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>
</html>
