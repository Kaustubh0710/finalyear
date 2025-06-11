<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "happytails";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if we're filtering for dogs
$is_dog_page = basename($_SERVER['PHP_SELF']) === 'dogmating.php';

// Query to retrieve pet data - filter for dogs if on dog page
$sql = "SELECT id, pet_name, pet_type, breed, gender, pet_image,email FROM matingregister";
if ($is_dog_page) {
    $sql .= " WHERE pet_type = 'dog'";
}

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Happy Tails - <?php echo $is_dog_page ? 'Dog' : 'Pet'; ?> Matches</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary: #6c5ce7;
            --secondary: #a29bfe;
            --light: #f8f9fa;
            --dark: #343a40;
            --success: #00b894;
            --info: #0984e3;
            --warning: #fdcb6e;
            --danger: #d63031;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f6fa;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        header {
            text-align: center;
            margin-bottom: 3rem;
            position: relative;
        }
        
        header h1 {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 1rem;
            position: relative;
            display: inline-block;
        }
        
        header h1::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: var(--secondary);
            border-radius: 2px;
        }
        
        .pet-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 2rem;
        }
        
        .pet-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0,0,0,0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .pet-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.12);
        }
        
        .pet-image-container {
            position: relative;
            height: 200px;
            overflow: hidden;
        }
        
        .pet-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .pet-card:hover .pet-image {
            transform: scale(1.05);
        }
        
        .pet-type {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(255,255,255,0.9);
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--primary);
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .pet-info {
            padding: 1.5rem;
        }
        
        .pet-name {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--dark);
        }
        
        .pet-details {
            margin-bottom: 1rem;
        }
        
        .pet-details div {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            color: #666;
        }
        
        .pet-details i {
            margin-right: 8px;
            color: var(--primary);
            width: 18px;
            text-align: center;
        }
        
        .pet-actions {
            display: flex;
            justify-content: flex-end;
            border-top: 1px solid #eee;
            padding-top: 1rem;
            margin-top: 1rem;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background-color: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #5649d1;
        }
        
        .empty-state {
            grid-column: 1 / -1;
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        
        .empty-state i {
            font-size: 3rem;
            color: var(--secondary);
            margin-bottom: 1rem;
        }
        
        .empty-state h3 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: var(--dark);
        }
        
        .filter-buttons {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
            gap: 1rem;
        }
        
        .filter-btn {
            padding: 0.5rem 1.5rem;
            border-radius: 20px;
            background: white;
            border: 2px solid var(--primary);
            color: var(--primary);
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .filter-btn.active {
            background: var(--primary);
            color: white;
        }
        
        .filter-btn:hover {
            background: var(--secondary);
            border-color: var(--secondary);
            color: white;
        }
        
        @media (max-width: 768px) {
            .pet-container {
                grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            }
            
            header h1 {
                font-size: 2rem;
            }
            
            .filter-buttons {
                flex-wrap: wrap;
            }
        }
        .btn-home {
            background-color: var(--info);
            color: white;
            padding: 10px 20px;
            border-radius: 30px;
            font-size: 1rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background 0.3s ease, transform 0.3s ease;
        }

        .btn-home:hover {
            background-color: #0666c7;
            transform: translateY(-2px);
        }

    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1><?php echo $is_dog_page ? 'Available Dogs' : 'Available Pets'; ?> for Mating</h1>
            <p>Find the perfect companion for your Dog</p>
        </header>
        
        <?php if (!$is_dog_page): ?>
        <div class="filter-buttons">
            <a href="dogmating.php" class="filter-btn">Show Dogs</a>
            <a href="catmating.php" class="filter-btn">Show Cats</a>
        </div>
        <?php endif; ?>
        
        <div class="pet-container">
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '<div class="pet-card">';
                    
                    // Image with type badge
                    echo '<div class="pet-image-container">';
                    if (!empty($row["pet_image"])) {
                        echo '<img src="uploads/' . htmlspecialchars($row["pet_image"]) . '" alt="' . htmlspecialchars($row["pet_name"]) . '" class="pet-image">';
                    } else {
                        echo '<img src="images/default-pet.jpg" alt="Default pet image" class="pet-image">';
                    }
                    echo '<span class="pet-type">' . htmlspecialchars(ucfirst($row["pet_type"])) . '</span>';
                    echo '</div>';
                    
                    // Pet info
                    echo '<div class="pet-info">';
                    echo '<h3 class="pet-name">' . htmlspecialchars($row["pet_name"]) . '</h3>';
                    echo '<div class="pet-details">';
                    echo '<div><i class="fas fa-paw"></i> Breed: ' . htmlspecialchars($row["breed"]) . '</div>';
                    echo '<div><i class="fas fa-venus-mars"></i> Gender: ' . htmlspecialchars($row["gender"]) . '</div>';
                    echo '</div>';
                    
                    // Action button (only Details now)
                    echo '<div class="pet-actions">';
                    echo '<a href="dogprofile.php?email=' . urlencode($row['email']) . '&breed=' . urlencode($row['breed']) . '&name=' . urlencode($row['pet_name']) . '" class="btn btn-primary"><i class="fas fa-info-circle"></i> Details</a>';
                    echo '</div>';
                    
                    echo '</div>'; // Close pet-info
                    echo '</div>'; // Close pet-card
                }
            } else {
                echo '<div class="empty-state">';
                echo '<i class="fas fa-paw"></i>';
                echo '<h3>No Dogs Available</h3>';
                echo '<p>There are currently no ' . ($is_dog_page ? 'dogs' : 'pets') . ' registered for mating. Check back later!</p>';
                echo '</div>';
            }
            ?>
        </div>
        <div style="text-align: center; margin-top: 3rem;">
            <a href="home.php" class="btn btn-home">
                <i class="fas fa-home"></i> Return to Home
            </a>
        </div>
    </div>
</body>
</html>
<?php
$conn->close();
?>