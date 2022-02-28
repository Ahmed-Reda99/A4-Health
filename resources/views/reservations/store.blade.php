<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <!-- Patient ID is Fixed for now -->
    <form action="/patients/{{$id}}/reservations" method="POST">
        @csrf
        <!-- Appointment ID sended by appointiment controller -->
        <input type="hidden" name = "appointment_id" value=1>
        <!-- patient_time is calculated by front end -->
        <label for="">Patient time</label>
        <input type="text" name = "patient_time" value="12:30:00">
        <button type="submit">Submit</button>
    </form>
</body>
</html>