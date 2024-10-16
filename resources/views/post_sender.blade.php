<!-- resources/views/myform.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Form</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <form id="myForm">
        <input type="text" name="name" id="name" placeholder="Enter your name" required>
        <input type="email" name="email" id="email" placeholder="Enter your email" required>
        <button type="submit">Submit</button>
    </form>

    <script>

        $(document).ready(function() {
            $('#myForm').on('submit', function(e) {
                e.preventDefault();

                const formData = {
                    "_token": "{{ csrf_token() }}",
                    name: $('#name').val(),
                    email: $('#email').val(),
                };

                $.ajax({
                    url: 'http://127.0.0.1:8000/api/createuser',
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(formData),
                    success: function(response) {
                        console.log('Success:', response);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            });
        });
    </script>
</body>
</html>
