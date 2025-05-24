<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Pet Mating Portal</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f9f9f9;
    }

    .hero {
      position: relative;
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      background: url('../image/mating.jpg') no-repeat center center/cover;
    }

    .overlay {
      position: absolute;
      bottom: 40px;
      background-color: white;
      padding: 30px 40px;
      border-radius: 10px;
      box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.1);
      text-align: center;
      max-width: 700px;
    }

    .overlay h1 {
      font-size: 24px;
      font-weight: bold;
      margin-bottom: 10px;
      color: #333;
    }

    .overlay p {
      font-size: 16px;
      color: #555;
      margin-bottom: 20px;
    }

    .btn-group {
      display: flex;
      justify-content: center;
      gap: 20px;
    }

    .btn {
      background-color: orange;
      color: white;
      border: none;
      padding: 12px 24px;
      font-size: 16px;
      font-weight: bold;
      border-radius: 6px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .btn:hover {
      background-color: darkorange;
    }
  </style>
</head>
<body>

  <div class="hero">
    <div class="overlay">
      <h1>Searching for a Dating Partner for your Pet?</h1>
      <p>Find a Perfect Pet Mating Match for Your Dog and Cat Nearby</p>
      <div class="btn-group">
        <button class="btn" onclick="location.href='dogmating.php'">Dog Mating</button>
        <button class="btn" onclick="location.href='catmating.php'">Cat Mating</button>
      </div>
    </div>
  </div>

</body>
</html>
