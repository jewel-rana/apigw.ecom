<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet"
    />

    <title>Forgot Password OTP</title>

</head>
<body
    style="
      margin: 0;
      padding-top:20px
    "
>

<div   style="
  padding: 50px 40px 46px;
  width: 380px;
  background: #fff;
  box-sizing: border-box;
  margin: 20px auto;
">
    <a href="https://prokash.io/"><img src="https://prokash.io/logo.png" height="40" alt="prokash"></a>
    <h2
        style="
        font-family: 'Roboto', sans-serif;
        color: #000;
        font-size: 20px;
        font-style: normal;
        font-weight: 500;
        line-height: 32px;
        margin: 0px 0px 32px;
      "
    >
        Forgot Password OTP
    </h2>


    <h2
        style="
        color: #000;
        font-family: 'Roboto', sans-serif;
        font-size: 18px;
        font-style: normal;
        font-weight: 500;
        line-height: 32px;
        margin: 0px 0px 10px;
      "

    >
        Dear {{ $name }},
    </h2>
    <p
        style="
        color: #333;
        font-family: 'Roboto', sans-serif;
        font-size: 14px;
        font-style: normal;
        font-weight: 500;
        line-height: 22px;
        margin: 0px;
      "
    >
        Here is your One Time Password (OTP).
    </p>
    <p
        style="
        color: #333;
        font-family: 'Roboto', sans-serif;
        font-size: 12px;
        font-style: normal;
        font-weight: 400;
        line-height: 22px;
        margin: 0px 0px 32px;
      "
    >
        Please enter this code to verify your email address for Prokash.
    </p>
    <table style="margin-bottom: 32px;">
        <tbody>
        <tr>
            @for($i =  0; $i < 5; $i++)
            <td>
                <div style="
              border-radius: 5px;
              background: rgba(137, 44, 220, 0.05);
              font-family: 'Roboto', sans-serif;
              font-size: 18px;
              font-weight: 600;
              color: #10241b;
              padding:8px 15px;
              margin-right: 4px;
            ">{{ $otp[$i] }}</div>
            </td>
            @endfor
        </tr>
        </tbody>
    </table>

    <p
        style="
        color: #333;
        font-family: 'Roboto', sans-serif;
        font-size: 16px;
        font-style: normal;
        font-weight: 600;
        line-height: 22px;
        margin: 0px 0px 32px;
      "
    >
        OTP will expire in <b>5 minutes.</b>
    </p>
    <p
        style="
        color: #333;
        font-family: 'Roboto', sans-serif;
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: 22px;
        margin: 0px 0px 32px;
      "
    >
        Thank you for choosing Prokash.
    </p>
    <p
        style="
        color: #333;
        font-family: 'Roboto', sans-serif;
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: 22px;
        margin: 0px;
      "
    >
        Best Regards,
    </p>
    <p
        style="
        color: #834BFF;
        font-family: 'Roboto', sans-serif;
        font-size: 16px;
        font-style: normal;
        font-weight: 500;
        line-height: 22px;
        margin: 0px 0px 32px;
      "
    >
        <a href="https://prokash.io/" style="text-decoration:none; color: #834BFF"> prokash.io</a>
    </p>




</body>
</html>
