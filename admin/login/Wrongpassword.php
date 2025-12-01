<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Wrong Password</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, Helvetica, sans-serif;
      background: linear-gradient(135deg, #0ea371, #1391cf);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #2a2a2a;
    }
    .card {
      background: #fff;
      padding: 28px 32px;
      border-radius: 12px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.12);
      max-width: 360px;
      width: 100%;
      text-align: center;
    }
    .error {
      color: #d9534f;
      font-weight: bold;
      margin-bottom: 14px;
    }
    a {
      color: #0b7ec6;
      text-decoration: none;
      font-weight: bold;
    }
    a:hover { text-decoration: underline; }
  </style>
</head>
<body>
  <div class="card">
    <div class="error">Sorry, wrong password.</div>
    <div>Please <a href="Login.html">return to login</a> and try again.</div>
  </div>
</body>
</html>
