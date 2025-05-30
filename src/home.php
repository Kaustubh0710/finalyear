<?php
session_start();
$isLoggedIn = isset($_SESSION['full_name']);
$fullName = $isLoggedIn ? $_SESSION['full_name'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HappyTails</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="script.js" defer></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f8f9fa;
        }
        header {
            background: #ffb74d;
            padding: 20px;
            text-align: center;
            padding: 0;
        }
        header h1 {
            color: #fff;
        }
        header a{
            position: relative;
            color: #FFFFFF;
            text-decoration: none;
            left: 47%;
            top: 0px;
        }
        header a:hover {
            color: #ff6f00;
            text-decoration: underline;
        }
        nav {
            display: flex;
            justify-content: center;
            padding: 10px;
            background: #444;
        }
        nav a {
            color: #fff;
            text-decoration: none;
            margin: 0 15px;
            font-size: 18px;
        }
        .hero {
            position: relative;
            width: 100%;
            height: 50vh;
            overflow: hidden;
        }
        .hero img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            animation: fade 10s infinite;
        }
        @keyframes fade {
            0% { opacity: 1; }
            50% { opacity: 0.7; }
            100% { opacity: 1; }
        }
        .services {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            padding: 20px;
        }
        .service-card {
            background: #fff;
            border-radius: 8px;
            margin: 15px;
            padding: 20px;
            width: 300px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }
        .service-card:hover {
            transform: scale(1.05);
        }
        .service-card img {
            width: 100%;
            border-radius: 10px;
            height: 150px;
            object-fit: cover;
        }
        .service-card button {
            background: #ffb74d;
            border: none;
            color: white;
            padding: 10px;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 10px;
        }
        footer {
            background: #222;
            color: #fff;
            text-align: center;
            padding: 10px;
            margin-top: 20px;
        }
        
    </style>
</head>
<body>
<header style="position: relative;">
  <h1><i class="fas fa-paw">HappyTails</i></h1>

  <!-- Become a Host Button - Left Corner -->
  <div style="position: absolute; top: 20px; left: 1px;">
    <a href="ServiceProvSignup.php" style="color: red; background-color: transparent; border: 2px solid red; padding: 5px 10px; text-decoration: none; font-weight: bold; cursor: pointer;">
      Become a Host
    </a>
  </div>

  <!-- Login/Logout/User Info - Right Corner -->
  <div style="position: absolute; top: 20px; right: 30px;">
    <?php if ($isLoggedIn): ?>
      <a href="user_profile.php" style="text-decoration: none; color: #fff; font-weight: bold;">
        👤 <?php echo htmlspecialchars($fullName); ?>
      </a>
      <form action="logout.php" method="POST" style="display: inline;">
        <button type="submit" style="margin-left: 180px; color: red; background-color: transparent; border: 2px solid red; padding: 5px 10px; cursor: pointer;">
          Logout
        </button>
      </form>
    <?php else: ?>
      <a href="login.php" style="text-decoration: none; color: #fff;">
        <i class="fas fa-sign-in-alt"></i> Login
      </a>
    <?php endif; ?>
  </div>
</header>    
    <section class="hero">
        <img src="../image/home.jpg" alt="Pets">
    </section>
    
    <section class="services">
        <div class="service-card">
            <img src="../image/mating2.jpg" alt="Love">
            <h3>  Register for Pet Mating  <i class="fas fa-love"></i></h3>
            <p> Find Your Furry Soulmate</p>
         <a href="MatingRegister1.php"><button> Find Now</button></a>
        </div>
        <div class="service-card">
            <img src="../image/mater.png" alt="Love">
            <h3>  Pet Mating  <i class="fas fa-love"></i></h3>
            <p> Find Your Furry Soulmate</p>
         <a href="petmating.php"><button> Find Now</button></a>
        </div>
        <div class="service-card">
            <img src="../image/bord.jpg" alt="Boarding">
            <h3>Pet Boarding <i class="fas fa-board"></i></h3>
            <p>Book Cat and Dog Boarding Service</p>
            <a href="pet_boarding.php"><button>Book Now</button></a>
        </div>
        <div class="service-card">
            <img src="../image/gro.png" alt="Grooming">
            <h3>Pet Grooming <i class="fas fa-groom"></i></h3>
            <p>Book In-Home Cat and Dog Grooming Service</p>
         <a href="index.html" >  <button>Book Now</button></a>
        </div>
        <div class="service-card">
            <img src="../image/lost.jpg" alt="Lost">
            <h3>Lost Pet Recovery<i class="fas fa-lost"></i></h3>
            <p>Contact Rescue Shelter and find your lost pet.</p>
          <a href="lost-pet-recovery.html"><button>Contact Now</button></a>
        </div>
        <div class="service-card">
            <img src="../image/vet.jpg" alt="Vet">
            <h3>Vet on Call <i class="fas fa-vet"></i></h3>
            <p>Expert Veterinary Service At Your Home</p>
           <a href="Vet-apt.html"><button>Book Now!</button></a> 
        </div>
        <div class="service-card">
            <img src="../image/Emergency.jpg" alt="Emergency">
            <h3>Emergency Response <i class="fas fa-emergency"></i></h3>
            <p>Get help from Emergency Response Team for your pets.</p>
            <a href="emergency-response.html"><button>Book Now</button></a>
        </div>
        <div class="service-card">
            <img src="../image/adopt.jpg" alt="Adopt">
            <h3>Adopt a Pet <i class="fas fa-adopt"></i></h3>
            <p>Adopt, Don't Shop: Save a Life</p>
         <a href="pet-adoption.html"><button>Adopt Now</button></a>
        </div>
        
    </section>
   
  
    <footer>
        <p>© 2025 The HappyTails. All rights reserved. <i class="fas fa-paw"></i></p>
    </footer>
</body>
</html>