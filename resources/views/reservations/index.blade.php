<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
        <!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</head>
<body>
<a href="/patients/reservations/create">store post</a>
        <table class="table">
            <tr>
                <th>
                    Patient Name
                </th>
                <th>
                    Doctor Name
                </th>
                <th>
                    Patient Time
                </th>
                <th>
                    Date
                </th>
            </tr>
            @foreach ($data as $reservation)
                <tr>
                <td>{{ $reservation->patient->user->fname }}</td>
                <td>{{ $reservation->appointment->doctor->user->fname }}</td>
                <td>{{ $reservation->patient_time }}</td>
                <td>{{ $reservation->appointment->date }}</td>
                <td><a href="/users/wait/edit">edit</a></td>
                <td>
                    <form action="/patients/{{$reservation->patient_id}}/reservations/{{$reservation->appointment_id}}/{{$reservation->patient_time}}" method="post">
                        @csrf
                        @method("DELETE")
                        <input type="submit" value="delete" name = "delete">
                    </form>
                </td>
                </tr>
            @endforeach 
        </table>
</body>
</html>