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
    <a href="/patients/create">store post</a>
    <table class="table">
        <tr>
            <th>
                ID
            </th>
            <th>
                username
            </th>
            <th>
                fname
            </th>
            <th>
                lname
            </th>
            <th>
                gender
            </th>
            <th>
                phone
            </th>
        </tr>
        @foreach ($users as $user)
        <tr>
            <td>{{ $user->id}}</td>
            <td>{{ $user->user->username }}</td>
            <td>{{ $user->user->fname }}</td>
            <td>{{ $user->user->lname }}</td>
            <td>{{ $user->user->gender }}</td>
            <td>{{ $user->user->phones[0]->phone }}</td>
            <td><a href="/patients/{{$user['id']}}">Show</a></td>
            <td><a href="/patients/{{$user['id']}}/edit">edit</a></td>
            <td>
                <form action="/patients/{{$user['id']}}" method="post">
                    @csrf
                    @method("DELETE")
                    <input type="submit" value="delete" name="delete">
                </form>
            </td>
        </tr>
        @endforeach
    </table>
</body>

</html>