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
<form action="/patients" method="post">
    @csrf
    <div class="mb-3">
      <label for="username" class="form-label">username</label>
      <input type="text" name = "username" value="{{ old('username') }}" class="form-control" id="username">
    </div>
    <div class="mb-3">
      <label for="password" class="form-label">password</label>
      <input type="password" name = "password" value="{{ old('password') }}" class="form-control" id="password">
    </div>

    <div class="mb-3">
      <label for="fname" class="form-label">Frist Name</label>
      <input type="text" name = "fname" value="{{ old('fname') }}" class="form-control" id="fname">
    </div>

    <div class="mb-3">
      <label for="lname" class="form-label">Last Name</label>
      <input type="text" name = "lname" value="{{ old('lname') }}" class="form-control" id="lname">
    </div>

    <div class="mb-3">
      <label for="gender" class="form-label">Gender</label>
      <input type="text" name = "gender" value="{{ old('gender') }}" class="form-control" id="gender">
    </div>

    <div class="mb-3">
      <label for="phone" class="form-label">Phone</label>
      <input type="text" name = "phone" value="{{ old('phone') }}" class="form-control" id="phone">
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>
  </form>
</body>
</html>