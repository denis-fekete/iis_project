
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GET Form Example</title>
</head>
<body>
    <h1>GET Form Example</h1>

    <!-- Form starts here -->
    <form action="{{ route('form.submit') }}" method="GET">
        @csrf <!-- Not needed for GET, but good practice for POST -->

        <label for="name">Name:</label>
        <input type="text" name="name" id="name" required>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>

        <button type="submit">Submit</button>
    </form>

    <!-- Display submitted data if available -->
    @if(isset($data))
        <h2>Submitted Data:</h2>
        <p>Name: {{ $data['name'] }}</p>
        <p>Email: {{ $data['email'] }}</p>
    @endif
</body>
</html>
