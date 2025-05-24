<?php
session_start();
if (!isset($_SESSION['groomer'])) {
    header('Location: ServiceProvLogin.php');
    exit();
}

$full_name = $_SESSION['groomer']['full_name'];
$email = $_SESSION['groomer']['email'];
$phone = $_SESSION['groomer']['phone'];
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Groomer Registration - ThePetNest</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('https://images.unsplash.com/photo-1600585154347-6e2c3d0e8b0a');
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .form-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
        }
        .form-container h2 {
            text-align: center;
            font-size: 28px;
            margin-bottom: 10px;
        }
        .form-container p {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
        }
        .form-section {
            background-color: #1a2a44;
            color: white;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
            text-align: center;
        }
        .form-section:hover {
            background-color: #2a3a54;
        }
        .form-content {
            display: none;
            margin-top: 20px;
        }
        .form-content.active {
            display: block;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-size: 14px;
            margin-bottom: 5px;
        }
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }
        .form-group textarea {
            resize: vertical;
            height: 100px;
        }
        .submit-btn {
            background-color: #ff6200;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }
        .submit-btn:hover {
            background-color: #e55a00;
        }
        .options {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .options label {
            flex: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
            cursor: pointer;
            font-size: 14px;
            text-align: center;
        }
        .options input[type="radio"] {
            display: none;
        }
        .options input[type="radio"]:checked + label {
            background-color: #ff6200;
            color: white;
        }
        .error {
            color: red;
            font-size: 12px;
            display: none;
            margin-top: 5px;
        }
        .invalid {
            border-color: red;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Groomer Registration</h2>
        <p>Please fill out your profile details for verification and onboarding.</p>

        <div class="form-section" onclick="toggleSection('personal-details')">Personal Details</div>
        <div id="personal-details" class="form-content">
            <form id="personal-form" action="groomers.php" method="POST">
            <div class="form-group">
                <label for="full-name">Full Name *</label>
                <input 
                    type="text" 
                    id="full-name" 
                    name="full_name" 
                    placeholder="Enter your full name" 
                    required 
                    pattern="[A-Za-z\s]{4,}(?![A-Za-z])\b" 
                    title="Full name must be at least 4 characters long, contain only letters and spaces, and have no consecutive duplicate letters"
                    value="<?php echo htmlspecialchars($full_name); ?>"
                >
                <span id="full-name-error" class="error">Full name must be at least 4 characters long, contain only letters and spaces, and have no consecutive duplicate letters (e.g., 'aa' or 'll').</span>
                <span id="full-name-required" class="error">Required</span>
            </div>


                <div class="form-group">
                    <label for="email">Email *</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        placeholder="Enter your email" 
                        required
                        value="<?php echo htmlspecialchars($email); ?>"
                    >
                    <span id="email-error" class="error">Please enter a valid email (e.g., example@domain.com).</span>
                    <span id="email-required" class="error">Required</span>
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number *</label>
                    <input 
                        type="tel" 
                        id="phone" 
                        name="phone" 
                        placeholder="Enter your phone number" 
                        required 
                        pattern="\d{10}" 
                        maxlength="10" 
                        title="Phone number must be exactly 10 digits"
                        value="<?php echo htmlspecialchars($phone); ?>"
                    >
                    <span id="phone-error" class="error">Phone number must be exactly 10 digits (e.g., 1234567890).</span>
                    <span id="phone-required" class="error">Required</span>
                </div>

                <div class="form-group">
                    <label for="profile-photo">Profile Photo (Optional)</label>
                    <input type="file" id="profile-photo" name="profile_photo" accept="image/*">
                </div>

                <?php if (isset($profile_photo) && !empty($profile_photo)): ?>
                    <div class="form-group">
                        <label>Current Profile Photo</label>
                        <img src="uploads/<?php echo htmlspecialchars($profile_photo); ?>" alt="Profile Photo" style="width: 100px; height: 100px; object-fit: cover;">
                    </div>
                <?php endif; ?>

            </form>
        </div>

        <div class="form-section" onclick="toggleSection('address-details')">Address Details</div>
        <div id="address-details" class="form-content">
            <form id="address-form">
                <div class="form-group">
                    <label for="address-line1">Address Line 1 *</label>
                    <input type="text" id="address-line1" name="address_line1" placeholder="Enter your address" required>
                    <span id="address-line1-required" class="error">Required</span>
                </div>
                <div class="form-group">
                    <label for="address-line2">Address Line 2 (Optional)</label>
                    <input type="text" id="address-line2" name="address_line2" placeholder="Apartment, suite, etc.">
                </div>
                <div class="form-group">
                    <label for="city">City *</label>
                    <input type="text" id="city" name="city" placeholder="Enter your city" required>
                    <span id="city-required" class="error">Required</span>
                </div>
                <div class="form-group">
                    <label for="state">State/Province *</label>
                    <input type="text" id="state" name="state" placeholder="Enter your state" required>
                    <span id="state-required" class="error">Required</span>
                </div>
                <div class="form-group">
                    <label for="zip-code">Zip/Postal Code *</label>
                    <input type="text" id="zip-code" name="zip_code" placeholder="Enter your zip code" required>
                    <span id="zip-code-required" class="error">Required</span>
                </div>
            </form>
        </div>

        <div class="form-section" onclick="toggleSection('service-details')">Grooming Service Details</div>
        <div id="service-details" class="form-content">
            <form id="service-form">
                <div class="form-group">
                    <label for="grooming-price">Base Grooming Price (per session) *</label>
                    <input type="number" id="grooming-price" name="grooming_price" placeholder="Enter price" required min="0">
                    <span id="grooming-price-error" class="error">Price must be a positive number.</span>
                    <span id="grooming-price-required" class="error">Required</span>
                </div>
                <div class="form-group">
                    <label>Types of Services Offered *</label>
                    <div class="options">
                        <input type="radio" id="bathing" name="service_type" value="bathing" checked>
                        <label for="bathing">Bathing</label>
                        <input type="radio" id="hair-trimming" name="service_type" value="hair-trimming">
                        <label for="hair-trimming">Hair Trimming</label>
                        <input type="radio" id="nail-clipping" name="service_type" value="nail-clipping">
                        <label for="nail-clipping">Nail Clipping</label>
                        <input type="radio" id="ear-cleaning" name="service_type" value="ear-cleaning">
                        <label for="ear-cleaning">Ear Cleaning</label>
                    </div>
                </div>
                <div class="form-group">
                    <label>Pet Types Serviced *</label>
                    <div class="options">
                        <input type="radio" id="dogs" name="pet_type_serviced" value="dogs" checked>
                        <label for="dogs">Dogs</label>
                        <input type="radio" id="cats" name="pet_type_serviced" value="cats">
                        <label for="cats">Cats</label>
                        <input type="radio" id="both" name="pet_type_serviced" value="both">
                        <label for="both">Both</label>
                    </div>
                </div>
                <div class="form-group">
                    <label>Size of Pets Serviced *</label>
                    <div class="options">
                        <input type="radio" id="small" name="pet_size_serviced" value="small" checked>
                        <label for="small">Small</label>
                        <input type="radio" id="medium" name="pet_size_serviced" value="medium">
                        <label for="medium">Medium</label>
                        <input type="radio" id="large" name="pet_size_serviced" value="large">
                        <label for="large">Large</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="availability">Availability (Days/Times) *</label>
                    <input type="text" id="availability" name="availability" placeholder="e.g., Mon-Fri 9 AM - 5 PM" required>
                    <span id="availability-required" class="error">Required</span>
                </div>
            </form>
        </div>

        <div class="form-section" onclick="toggleSection('experience-qualifications')">Experience & Qualifications</div>
        <div id="experience-qualifications" class="form-content">
            <form id="experience-form">
                <div class="form-group">
                    <label for="years-experience">Years of Experience *</label>
                    <input type="number" id="years-experience" name="years_experience" placeholder="Enter years of experience" required min="0">
                    <span id="years-experience-error" class="error">Years of experience must be a positive number.</span>
                    <span id="years-experience-required" class="error">Required</span>
                </div>
                <div class="form-group">
                    <label for="awards">Awards & Certifications (Optional)</label>
                    <textarea id="awards" name="awards" placeholder="List any awards or certifications related to pet grooming"></textarea>
                </div>
                <div class="form-group">
                    <label for="special-skills">Special Skills (Optional)</label>
                    <textarea id="special-skills" name="special_skills" placeholder="e.g., Handling aggressive pets, grooming specific breeds"></textarea>
                </div>
            </form>
        </div>

        <button type="button" class="submit-btn" onclick="submitForm()">Submit</button>
    </div>

    <script>
        function toggleSection(sectionId) {
            const sections = document.querySelectorAll('.form-content');
            sections.forEach(section => {
                section.classList.remove('active');
            });
            document.getElementById(sectionId).classList.add('active');
        }

        function validateForm() {
            let isValid = true;

            // Helper function to show/hide errors
            function showError(elementId, errorId, show, isRequiredError = false) {
                const input = document.getElementById(elementId);
                const error = document.getElementById(errorId);
                const requiredError = document.getElementById(`${elementId}-required`);
                
                if (show) {
                    if (isRequiredError && requiredError) {
                        requiredError.style.display = 'block';
                    } else if (error) {
                        error.style.display = 'block';
                    }
                    input.classList.add('invalid');
                } else {
                    if (error) error.style.display = 'none';
                    if (requiredError) requiredError.style.display = 'none';
                    input.classList.remove('invalid');
                }
            }

            // Personal Details Validation
            // Full Name
            const fullName = document.getElementById('full-name').value;
            const nameRegex = /^(?!.*(.)\1)[A-Za-z\s]{4,}$/;
            if (!fullName) {
                showError('full-name', 'full-name-error', true, true);
                isValid = false;
            } else if (!nameRegex.test(fullName)) {
                showError('full-name', 'full-name-error', true);
                isValid = false;
            } else {
                showError('full-name', 'full-name-error', false);
            }

            // Email
            const email = document.getElementById('email').value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!email) {
                showError('email', 'email-error', true, true);
                isValid = false;
            } else if (!emailRegex.test(email)) {
                showError('email', 'email-error', true);
                isValid = false;
            } else {
                showError('email', 'email-error', false);
            }

            // Phone Number
            const phone = document.getElementById('phone').value;
            const phoneRegex = /^\d{10}$/;
            if (!phone) {
                showError('phone', 'phone-error', true, true);
                isValid = false;
            } else if (!phoneRegex.test(phone)) {
                showError('phone', 'phone-error', true);
                isValid = false;
            } else {
                showError('phone', 'phone-error', false);
            }

            // Address Details Validation
            // Address Line 1
            const addressLine1 = document.getElementById('address-line1').value;
            if (!addressLine1) {
                showError('address-line1', 'address-line1-required', true, true);
                isValid = false;
            } else {
                showError('address-line1', 'address-line1-required', false);
            }

            // City
            const city = document.getElementById('city').value;
            if (!city) {
                showError('city', 'city-required', true, true);
                isValid = false;
            } else {
                showError('city', 'city-required', false);
            }

            // State
            const state = document.getElementById('state').value;
            if (!state) {
                showError('state', 'state-required', true, true);
                isValid = false;
            } else {
                showError('state', 'state-required', false);
            }

            // Zip Code
            const zipCode = document.getElementById('zip-code').value;
            if (!zipCode) {
                showError('zip-code', 'zip-code-required', true, true);
                isValid = false;
            } else {
                showError('zip-code', 'zip-code-required', false);
            }

            // Grooming Service Details Validation
            // Grooming Price
            const groomingPrice = document.getElementById('grooming-price').value;
            if (!groomingPrice) {
                showError('grooming-price', 'grooming-price-required', true, true);
                isValid = false;
            } else if (groomingPrice < 0) {
                showError('grooming-price', 'grooming-price-error', true);
                isValid = false;
            } else {
                showError('grooming-price', 'grooming-price-error', false);
            }

            // Availability
            const availability = document.getElementById('availability').value;
            if (!availability) {
                showError('availability', 'availability-required', true, true);
                isValid = false;
            } else {
                showError('availability', 'availability-required', false);
            }

            // Experience & Qualifications Validation
            // Years of Experience
            const yearsExperience = document.getElementById('years-experience').value;
            if (!yearsExperience) {
                showError('years-experience', 'years-experience-required', true, true);
                isValid = false;
            } else if (yearsExperience < 0) {
                showError('years-experience', 'years-experience-error', true);
                isValid = false;
            } else {
                showError('years-experience', 'years-experience-error', false);
            }

            return isValid;
        }

        function submitForm() {
            if (validateForm()) {
                // Combine all form data
                const personalForm = document.getElementById('personal-form');
                const addressForm = document.getElementById('address-form');
                const serviceForm = document.getElementById('service-form');
                const experienceForm = document.getElementById('experience-form');

                const formData = new FormData();
                const personalData = new FormData(personalForm);
                const addressData = new FormData(addressForm);
                const serviceData = new FormData(serviceForm);
                const experienceData = new FormData(experienceForm);

                for (let [key, value] of personalData) formData.append(key, value);
                for (let [key, value] of addressData) formData.append(key, value);
                for (let [key, value] of serviceData) formData.append(key, value);
                for (let [key, value] of experienceData) formData.append(key, value);

                // Create a hidden form to submit the data
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'groomers.php';
                form.style.display = 'none';
                document.body.appendChild(form);

                for (let [key, value] of formData) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = key;
                    input.value = value;
                    form.appendChild(input);
                }

                form.submit();
            }
        }

        // Show the first section by default
        toggleSection('personal-details');
    </script>
</body>
</html>