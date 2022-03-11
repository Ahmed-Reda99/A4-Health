<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Embedded Payment</title>
    <style></style>
</head>

<body>
    <h1>Embedded Payment</h1>

    <script src="https://demo.myfatoorah.com/cardview/v1/session.js"></script>
    <div style="width:400px">
        <div id="card-element"></div>
    </div>
    <input type="text" value="{{ $data }}" id = "a">
    <input type="text" value="{{ $data2 }}" id = "b">
    
    <button onclick="submit()">Pay Now</button>


    <script>
        var a = document.getElementById("a");
        var b = document.getElementById("b");
        console.log(a.value);
        var config = {
            
          countryCode: a.value, // Here, add your Country Code.
          sessionId: b.value, // Here you add the "SessionId" you receive from InitiateSession Endpoint.
          cardViewId: "card-element",
          // The following style is optional.
          style: {
            direction: "ltr",
            cardHeight: 130,
            input: {
              color: "black",
              fontSize: "13px",
              fontFamily: "sans-serif",
              inputHeight: "32px",
              inputMargin: "0px",
              borderColor: "c7c7c7",
              borderWidth: "1px",
              borderRadius: "8px",
              boxShadow: "",
              placeHolder: {
                holderName: "Name On Card",
                cardNumber: "Number",
                expiryDate: "MM / YY",
                securityCode: "CVV",
              }
            },
            label: {
              display: false,
              color: "black",
              fontSize: "13px",
              fontWeight: "normal",
              fontFamily: "sans-serif",
              text: {
                holderName: "Card Holder Name",
                cardNumber: "Card Number",
                expiryDate: "Expiry Date",
                securityCode: "Security Code",
              },
            },
            error: {
              borderColor: "red",
              borderRadius: "8px",
              boxShadow: "0px",
            },
          },
      };
      myFatoorah.init(config);

      function submit() {
            myFatoorah.submit()
            // On success
            .then(function (response) {
              fetch(`http://127.0.0.1:8000/patients/1/reservations/1/pay/now/${sessionID}`)
            .then(response => response.json())
            .then(data => console.log(data));
            // Here you need to pass session id to you backend here
            var sessionId = response.SessionId;
            var cardBrand = response.CardBrand;
            
            })
            // In case of errors
            .catch(function (error) {
                console.log(error);
            });
        }
    </script>

</body>

</html>