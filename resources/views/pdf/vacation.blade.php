<!DOCTYPE html>
<html>

<head>
    <title>Vacation Details</title>
</head>

<body>
    <h1>{{ $vacation->title }}</h1>
    <p><strong>Description:</strong> {{ $vacation->description }}</p>
    <p><strong>Date:</strong> {{ $vacation->date }}</p>
    <p><strong>Location:</strong> {{ $vacation->location }}</p>
    <p><strong>Participants:</strong> {{ implode(', ', $vacation->participants) }}</p>
</body>

</html>