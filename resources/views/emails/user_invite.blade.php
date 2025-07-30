<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Invitation to Waltergates OMS</title>
  <style>
    /* Reset & base */
    body {
      margin: 0; padding: 0;
      background-color: #f0f8ff; /* very light sky blue */
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: #333333;
    }
    a {
      color: #e63946; /* red */
      text-decoration: none;
    }
    a:hover {
      text-decoration: underline;
    }
    .container {
      max-width: 600px;
      margin: 30px auto;
      background-color: #ffffff;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      padding: 30px 40px;
    }
    .header {
      text-align: center;
      margin-bottom: 30px;
    }
    .header img {
      max-width: 150px;
      height: auto;
    }
    h1 {
      color: #0077b6; /* sky blue */
      margin-bottom: 20px;
    }
    p {
      line-height: 1.6;
      font-size: 16px;
      margin-bottom: 20px;
    }
    .btn {
      display: inline-block;
      background-color: #e63946; /* red */
      color: #fff;
      padding: 14px 28px;
      border-radius: 5px;
      font-weight: 600;
      font-size: 16px;
      cursor: pointer;
      text-align: center;
      margin: 20px 0;
    }
    .btn:hover {
      background-color: #c5303f;
    }
    .footer {
      text-align: center;
      font-size: 14px;
      color: #666666;
      margin-top: 30px;
      border-top: 1px solid #e0e0e0;
      padding-top: 15px;
    }
    @media only screen and (max-width: 640px) {
      .container {
        padding: 20px;
        margin: 20px;
      }
      h1 {
        font-size: 24px;
      }
      p, .btn {
        font-size: 14px;
      }
      .btn {
        padding: 12px 24px;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <!-- Replace 'logo.png' with your actual logo path or URL -->
      <img src="{{ asset('build/assets/image/Office_logo.jpg') }}" alt="Waltergates Logo" />
    </div>
    <h1>You are Invited!</h1>
    <p>Hello,</p>

    <p>You have been invited to join the <strong>Waltergates Office Management System</strong>.</p>

    <p>Click the button below to complete your registration. <br><small>(Link expires in 7 days)</small></p>

    <p style="text-align:center;">
      <a class="btn" href="{{ url('user/invite-register', ['token' => $user->invite_token]) }}">
        Complete Your Registration
      </a>
    </p>

    <p>If you did not expect this email, please ignore it.</p>

    <div class="footer">
      Waltergates Office Management System &copy; {{ date('Y') }}. All rights reserved.
    </div>
  </div>
</body>
</html>
