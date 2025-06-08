<?php
include 'auth_check.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register Your Pet for Mating</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 40px;
      background-image: url('../image/petmat.jpg');
      background-size: cover;
      background-repeat: no-repeat;
      background-position: center;
    }
    h2 {
      text-align: center;
      margin-bottom: 30px;
      color: #fff;
      text-shadow: 1px 1px 2px #000;
    }
    .form-container {
      max-width: 700px;
      margin: auto;
      background-color: rgba(255, 255, 255, 0.95);
      border-top: 2px solid #ccc;
      padding: 20px;
      border-radius: 10px;
    }
    label {
      display: block;
      margin-top: 15px;
      font-weight: bold;
    }
    input[type="text"],
    input[list],
    input[type="file"],
    select {
      width: 100%;
      padding: 12px;
      margin-top: 5px;
      font-size: 14px;
      box-sizing: border-box;
    }
    .button-group {
      display: flex;
      gap: 10px;
      margin-top: 10px;
    }
    .button-group button {
      flex: 1;
      padding: 12px;
      background-color: #fff;
      border: 1px solid #ccc;
      cursor: pointer;
      font-size: 14px;
      transition: background-color 0.2s, border-color 0.2s;
    }
    .pet-type {
      display: flex;
      gap: 20px;
      margin-top: 10px;
    }
    .pet-type .option {
      border: 1px solid #ccc;
      padding: 10px;
      width: 100px;
      text-align: center;
      cursor: pointer;
      transition: background-color 0.2s, border-color 0.2s;
      background-color: #fff;
    }
    .pet-type .option img {
      height: 50px;
      margin-bottom: 5px;
      display: block;
      margin-left: auto;
      margin-right: auto;
    }
    .submit-button {
      text-align: right;
      margin-top: 30px;
    }
    .submit-button button {
      background-color: orange;
      color: white;
      border: none;
      padding: 12px 25px;
      font-size: 16px;
      cursor: pointer;
    }
    .selected {
      background-color: #ffe0b3 !important;
      border-color: orange !important;
    }
    .error {
      color: red;
      font-size: 12px;
      margin-top: 5px;
    }
    #image-preview {
      margin-top: 10px;
      max-height: 150px;
      display: none;
      border-radius: 10px;
    }
  </style>
</head>
<body>

  <h2>Register Your Pet for Mating</h2>

  <form class="form-container" id="petForm" enctype="multipart/form-data" method="POST" action="matingregister.php">

    <label for="pet-name">Pet's Name?</label>
    <input type="text" id="pet-name" name="petName" placeholder="Please enter your pet's name...">
    <div id="pet-name-error" class="error"></div>

    <label>Pet Type?</label>
    <div class="pet-type" id="petType">
      <div class="option" role="button" tabindex="0" data-type="dog">
        <img src="../image/dog.jpg" alt="Dog icon">
        <div>Dog</div>
      </div>
      <div class="option" role="button" tabindex="0" data-type="cat">
        <img src="../image/cat.jpg" alt="Cat icon">
        <div>Cat</div>
      </div>
    </div>
    <div id="pet-type-error" class="error"></div>

    <label for="breed">Pet Breed?</label>
    <input list="breeds" id="breed" name="breed" placeholder="Type or select a breed...">
    <datalist id="breeds">
      <!-- This will be populated by JavaScript -->
    </datalist>
    <div id="breed-error" class="error"></div>

    <label for="age">Pet's Age?</label>
    <input type="text" id="age" name="age" placeholder="Please enter your pet's age in years...">
    <div id="age-error" class="error"></div>

    <label for="weight">Pet's Weight?</label>
    <input type="text" id="weight" name="weight" placeholder="Please enter your pet's weight in KG...">
    <div id="weight-error" class="error"></div>

    <label>Pet's Gender?</label>
    <div class="button-group" id="gender">
      <button type="button" class="gender-btn">Male</button>
      <button type="button" class="gender-btn">Female</button>
    </div>
    <div id="gender-error" class="error"></div>

    <label>Pet's Vaccination?</label>
    <div class="button-group" id="vaccination">
      <button type="button">Yes</button>
      <button type="button">No</button>
    </div>
    <div id="vaccination-error" class="error"></div>

    <label>Pet Sociable?</label>
    <div class="button-group" id="sociable">
      <button type="button">Yes, Sociable</button>
      <button type="button">No, Not Sociable</button>
    </div>
    <div id="sociable-error" class="error"></div>

    <label>Pet Potty Trained?</label>
    <div class="button-group" id="potty-trained">
      <button type="button">Yes</button>
      <button type="button">No</button>
    </div>
    <div id="potty-trained-error" class="error"></div>

    <label>Pet Aggressive?</label>
    <div class="button-group" id="aggressive">
      <button type="button">Low</button>
      <button type="button">Medium</button>
      <button type="button">High</button>
    </div>
    <div id="aggressive-error" class="error"></div>

    <label for="pet-image">Upload Pet's Image</label>
    <input type="file" id="pet-image" name="petImage" accept="image/*">
    <div id="image-error" class="error"></div>
    <img id="image-preview" />

    <!-- Hidden inputs -->
    <input type="hidden" name="petType" id="petType_input">
    <input type="hidden" name="gender" id="gender_input">
    <input type="hidden" name="vaccination" id="vaccination_input">
    <input type="hidden" name="sociable" id="sociable_input">
    <input type="hidden" name="potty_trained" id="potty_trained_input">
    <input type="hidden" name="aggressive" id="aggressive_input">

    <div class="submit-button">
      <button type="submit">Continue</button>
    </div>
    <input type="hidden" id="isLoggedIn" value="<?php echo $isLoggedIn; ?>">
  </form>

  <script>
    // Breed data
    const breeds = {
      dog: [
        "Labrador Retriever",
        "German Shepherd",
        "Golden Retriever",
        "Bulldog",
        "Poodle",
        "Beagle",
        "Rottweiler",
        "Dachshund",
        "Siberian Husky",
        "Boxer",
        "Great Dane",
        "Shih Tzu",
        "Chihuahua"
      ],
      cat: [
        "Persian",
        "Maine Coon",
        "Siamese",
        "British Shorthair",
        "Bengal",
        "Ragdoll",
        "Sphynx",
        "Scottish Fold",
        "Russian Blue",
        "American Shorthair",
        "Abyssinian",
        "Burmese",
        "Birman"
      ]
    };

    // Function to update breed options
    function updateBreeds(type) {
      const datalist = document.getElementById('breeds');
      datalist.innerHTML = ''; // Clear existing options
      
      breeds[type].forEach(breed => {
        const option = document.createElement('option');
        option.value = breed;
        datalist.appendChild(option);
      });
    }

    // Highlight selected buttons
    document.querySelectorAll('.button-group').forEach(group => {
      const buttons = group.querySelectorAll('button');
      buttons.forEach(button => {
        button.addEventListener('click', () => {
          buttons.forEach(btn => btn.classList.remove('selected'));
          button.classList.add('selected');
        });
      });
    });

    // Highlight selected pet type and update breeds
    const petOptions = document.querySelectorAll('.pet-type .option');
    petOptions.forEach(option => {
      option.addEventListener('click', () => {
        petOptions.forEach(opt => opt.classList.remove('selected'));
        option.classList.add('selected');
        
        // Update breeds based on selected pet type
        const petType = option.getAttribute('data-type');
        updateBreeds(petType);
      });
    });

    // Image preview
    document.getElementById('pet-image').addEventListener('change', function () {
      const file = this.files[0];
      const preview = document.getElementById('image-preview');
      if (file) {
        preview.src = URL.createObjectURL(file);
        preview.style.display = 'block';
      } else {
        preview.src = '';
        preview.style.display = 'none';
      }
    });

    document.getElementById('petForm').addEventListener('submit', function (event) {
  event.preventDefault();

  // Check if user is logged in
  const isLoggedIn = document.getElementById('isLoggedIn').value === 'true';

  if (!isLoggedIn) {
    if (confirm("You need to login first.\n\nPress OK to go to login page.\nPress Cancel to stay here.")) {
      window.location.href = "login.php"; // replace login.php with your login page
    }
    return; // Stop form submission
  }

  // Otherwise, continue validating the form as you already do

  document.querySelectorAll('.error').forEach(div => div.textContent = '');
  let isValid = true;

  const getValue = (id) => document.getElementById(id).value.trim();

  if (getValue('pet-name') === '') {
    document.getElementById('pet-name-error').textContent = "Pet's name is required.";
    isValid = false;
  }

  if (!document.querySelector('.pet-type .selected')) {
    document.getElementById('pet-type-error').textContent = "Pet type is required.";
    isValid = false;
  }

  if (getValue('breed') === '') {
    document.getElementById('breed-error').textContent = "Pet breed is required.";
    isValid = false;
  }

  if (getValue('age') === '') {
    document.getElementById('age-error').textContent = "Pet's age is required.";
    isValid = false;
  }

  if (getValue('weight') === '') {
    document.getElementById('weight-error').textContent = "Pet's weight is required.";
    isValid = false;
  }

  if (!document.querySelector('.gender-btn.selected')) {
    document.getElementById('gender-error').textContent = "Pet's gender is required.";
    isValid = false;
  }

  if (!document.querySelector('#vaccination button.selected')) {
    document.getElementById('vaccination-error').textContent = "Vaccination info is required.";
    isValid = false;
  }

  if (!document.querySelector('#sociable button.selected')) {
    document.getElementById('sociable-error').textContent = "Sociable info is required.";
    isValid = false;
  }

  if (!document.querySelector('#potty-trained button.selected')) {
    document.getElementById('potty-trained-error').textContent = "Potty training info is required.";
    isValid = false;
  }

  if (!document.querySelector('#aggressive button.selected')) {
    document.getElementById('aggressive-error').textContent = "Aggression level is required.";
    isValid = false;
  }

  if (document.getElementById('pet-image').files.length === 0) {
    document.getElementById('image-error').textContent = "Please upload your pet's image.";
    isValid = false;
  }

  if (isValid) {
    // Fill hidden fields
    const selectedPetType = document.querySelector('.pet-type .selected');
    if (selectedPetType) {
      const petType = selectedPetType.getAttribute('data-type');
      document.getElementById('petType_input').value = petType;
    }

    const selectedGender = document.querySelector('.gender-btn.selected');
    if (selectedGender) {
      document.getElementById('gender_input').value = selectedGender.innerText.trim();
    }

    const selectedVaccination = document.querySelector('#vaccination button.selected');
    if (selectedVaccination) {
      document.getElementById('vaccination_input').value = selectedVaccination.innerText.trim();
    }

    const selectedSociable = document.querySelector('#sociable button.selected');
    if (selectedSociable) {
      document.getElementById('sociable_input').value = selectedSociable.innerText.trim();
    }

    const selectedPotty = document.querySelector('#potty-trained button.selected');
    if (selectedPotty) {
      document.getElementById('potty_trained_input').value = selectedPotty.innerText.trim();
    }

    const selectedAggressive = document.querySelector('#aggressive button.selected');
    if (selectedAggressive) {
      document.getElementById('aggressive_input').value = selectedAggressive.innerText.trim();
    }

    this.submit();
  }
});

  </script>

</body>
</html>
