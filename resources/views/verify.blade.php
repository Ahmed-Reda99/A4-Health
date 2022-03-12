<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>
<body>

    <div class=" my-3 sideMargin w-75 mx-auto">
    <div class="aaaa bg-white pb-4">
        <h4 class="bg-primary rounded-top text-white text-center py-2 ">Sign Up</h4>
        <form action="/api/verify" method="post">
        @csrf
        <div class="text-primary px-4">
            <div class="form-floating my-3 text-center">
                <input  name="fname" type="text" class="form-control " id="floatingfName" 
                    placeholder="name@example.com">
                <label for="floatingfName">First Name</label>
            </div>
            
            <div class="form-floating my-3 text-center">
                <input  name="lname" type="text" class="form-control " id="floatinglName" 
                    placeholder="name@example.com">
                <label for="floatinglName">Last Name</label>
            </div>
            

            <div class="form-floating my-3 text-center">
                <input  name="username" type="text" class="form-control " id="floatingUsername" 
                    placeholder="name@example.com">
                <label for="floatingUsername">Username</label>
            </div>
            
            <div class="form-floating my-3 text-center">
                <input  name="password" type="password" class="form-control " id="floatingPassword" 
                    placeholder="name@example.com">
                <label for="floatingPassword">Password</label>
            </div>
            
            
            <div class="d-flex align-items-center">
                <div class="form-check-inline">
                    <label>Gender</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" id="inlineRadio1" 
                       value="Male" >
                    <label class="form-check-label" for="inlineRadio1">Male</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" id="inlineRadio2" 
                    value="Female" >
                    <label class="form-check-label" for="inlineRadio2">Female</label>
                </div>
            </div>
        
           
            <div class="form-floating my-3 d-flex justify-content-between ">
                <input class="form-control w-75" id="floatingphone" name="phone"
                    placeholder="name@example.com">
                <label for="floatingphone">Phone</label>
                <!-- <button  class="btn btn-danger align-self-center">Verify</button>            -->
            </div>
            
<!-- 
            <div class="form-floating my-3 d-flex justify-content-between" >
                <input  #mv  type="text" class="form-control w-75" id="floatingmassage"
                    placeholder="name@example.com">
                <label for="floatingmassage">Massage Verification</label>
                <button  type="submit" class="btn btn-danger align-self-center">Checked</button>           
            </div> -->
       
            
            <button type="submit" class="btn btn-danger">Save</button>
         
        </div>
    </form>


    </div>
</div>

</body>
</html>