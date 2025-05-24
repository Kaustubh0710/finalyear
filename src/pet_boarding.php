<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>HappyTails - Pet Boarding</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: linear-gradient(to right, #1f3c88, #2e9cca);
      margin: 0;
      padding: 0;
      color: #333;
    }

    .container {
      background: white;
      max-width: 800px;
      margin: 50px auto;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
    }

    .tabs {
      display: flex;
      border-bottom: 2px solid #ccc;
      margin-bottom: 20px;
    }

    .tab {
      padding: 10px 20px;
      cursor: pointer;
      font-weight: bold;
    }

    .tab.active {
      background: orangered;
      color: white;
      border-top-left-radius: 10px;
      border-top-right-radius: 10px;
    }

    .filters {
      display: flex;
      gap: 20px;
      margin-bottom: 20px;
    }

    .filters label {
      display: flex;
      align-items: center;
      gap: 5px;
    }

    .form-group {
      margin-bottom: 15px;
    }

    .form-group label {
      font-weight: bold;
      display: block;
      margin-bottom: 5px;
    }

    input[type="text"],
    input[type="date"],
    input[type="time"] {
      width: 100%;
      padding: 8px;
      border: 1px solid #aaa;
      border-radius: 5px;
    }

    .counter {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 20px;
    }

    .counter label {
      width: 60px;
    }

    .counter button {
      width: 25px;
      height: 25px;
      font-size: 18px;
      cursor: pointer;
      border: none;
      background: #2e9cca;
      color: white;
      border-radius: 5px;
    }

    .search-btn {
      width: 100%;
      padding: 12px;
      font-size: 16px;
      background: orange;
      color: white;
      font-weight: bold;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    .search-btn:hover {
      background: orangered;
    }

    .daycare-btn {
      flex: 1;
      padding: 10px;
      border: 2px solid #ccc;
      background: white;
      font-weight: bold;
      cursor: pointer;
      border-radius: 5px;
      transition: background 0.3s, border 0.3s;
    }

    .daycare-btn.active {
      background: orangered;
      color: white;
      border-color: orangered;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="tabs">
      <div class="tab active" onclick="selectTab(this)">
        <div class="tab-title">Overnight</div>
        <div class="tab-desc">While You're Away</div>
      </div>
      <div class="tab" onclick="selectTab(this)">Daycare<br><small>While You're at Work</small></div>
    </div>

    <div class="filters">
      <label><input type="checkbox" checked> Pet Boarding</label>
      <label><input type="checkbox" checked> Pet Hosting</label>
      <label><input type="checkbox" checked> Pet Sitting</label>
    </div>

    <div id="daycare-options" style="display: none; margin-bottom: 20px; display: flex; gap: 10px;">
      <button type="button" class="daycare-btn" onclick="selectDaycare('single')">Single Day</button>
      <button type="button" class="daycare-btn" onclick="selectDaycare('multiple')">More than one day</button>
    </div>

    <!-- ✅ Entire form starts here -->
    <form action="BoarderListing.php" method="GET" id="bookingForm">
      <div class="form-group">
        <label>Your Location</label>
        <input type="text" name="location" placeholder="Eg: Electronic City, Bengaluru" required>
      </div>

      <div class="form-group">
        <label>Check-In Date</label>
        <input type="date" name="checkin" id="checkin-date" required>
      </div>

      <div class="form-group" id="checkout-date-group">
        <label>Check-Out Date</label>
        <input type="date" name="checkout" id="checkout-date" required>
      </div>

      <div id="time-options" style="display: none; margin-bottom: 15px;">
        <div style="display: flex; gap: 20px;">
          <div class="form-group" style="flex: 1;">
            <label for="checkin-time">Check-In Time</label>
            <input type="time" id="checkin-time" name="checkin_time">
          </div>
          <div class="form-group" style="flex: 1;">
            <label for="checkout-time">Check-Out Time</label>
            <input type="time" id="checkout-time" name="checkout_time">
          </div>
        </div>
      </div>

      <div class="counter">
        <label>Dogs</label>
        <button type="button" onclick="updateCount('dog', -1)">−</button>
        <span id="dogCount">0</span>
        <input type="hidden" name="dogs" id="dogInput" value="0">
        <button type="button" onclick="updateCount('dog', 1)">+</button>
      </div>

      <div class="counter">
        <label>Cats</label>
        <button type="button" onclick="updateCount('cat', -1)">−</button>
        <span id="catCount">0</span>
        <input type="hidden" name="cats" id="catInput" value="0">
        <button type="button" onclick="updateCount('cat', 1)">+</button>
      </div>

      <button type="submit" class="search-btn">Search</button>
    </form>
    <!-- ✅ Entire form ends here -->
  </div>

  <script>
  window.onload = function () {
    const defaultTab = document.querySelector('.tab.active');
    selectTab(defaultTab);
  };

  function selectTab(tab) {
    document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
    tab.classList.add('active');

    const isDaycare = tab.textContent.includes("Daycare");
    document.getElementById('daycare-options').style.display = isDaycare ? 'flex' : 'none';

    document.querySelectorAll('.daycare-btn').forEach(btn => btn.classList.remove('active'));

    document.getElementById('checkout-date-group').style.display = 'block';
    document.getElementById('time-options').style.display = 'none';

    checkoutInput.required = true;
  }

  function selectDaycare(type) {
    document.querySelectorAll('.daycare-btn').forEach(btn => btn.classList.remove('active'));

    if (type === 'single') {
      document.querySelectorAll('.daycare-btn')[0].classList.add('active');
      document.getElementById('checkout-date-group').style.display = 'none';
      document.getElementById('time-options').style.display = 'block';

      checkoutInput.required = false;
      checkoutInput.value = checkinInput.value; // auto-fill hidden checkout value same as checkin

    } else {
      document.querySelectorAll('.daycare-btn')[1].classList.add('active');
      document.getElementById('checkout-date-group').style.display = 'block';
      document.getElementById('time-options').style.display = 'none';

      checkoutInput.required = true;
    }
  }

  let dogCount = 0, catCount = 0;
  function updateCount(type, delta) {
    if (type === 'dog') {
      dogCount = Math.max(0, dogCount + delta);
      document.getElementById('dogCount').textContent = dogCount;
      document.getElementById('dogInput').value = dogCount;
    } else {
      catCount = Math.max(0, catCount + delta);
      document.getElementById('catCount').textContent = catCount;
      document.getElementById('catInput').value = catCount;
    }
  }

  const checkinInput = document.getElementById('checkin-date');
  const checkoutInput = document.getElementById('checkout-date');
  const today = new Date().toISOString().split("T")[0];
  checkinInput.min = today;

  checkinInput.addEventListener('change', function () {
    const selectedCheckinDate = checkinInput.value;
    checkoutInput.value = '';
    checkoutInput.min = getNextDate(selectedCheckinDate);

    // If single day selected, auto-fill checkout
    if (document.querySelector('.daycare-btn.active') && document.querySelector('.daycare-btn.active').textContent.includes('Single')) {
      checkoutInput.value = selectedCheckinDate;
    }
  });

  function getNextDate(dateStr) {
    const date = new Date(dateStr);
    date.setDate(date.getDate() + 1);
    return date.toISOString().split("T")[0];
  }

  document.querySelector('form').addEventListener('submit', function (e) {
    const locationInput = this.querySelector('input[name="location"]');
    locationInput.value = locationInput.value.trim().replace(/\s+/g, ' ');

    // 🛑 Prevent submit if no Dog or Cat selected
    if (dogCount === 0 && catCount === 0) {
      alert("Please select at least 1 Dog or 1 Cat before proceeding.");
      e.preventDefault();
      return false;
    }

    // 🛑 Extra safety for single day - auto-fill checkout if hidden
    if (document.querySelector('.daycare-btn.active') && document.querySelector('.daycare-btn.active').textContent.includes('Single')) {
      if (!checkoutInput.value) {
        checkoutInput.value = checkinInput.value;
      }
    }
  });
</script>


</body>
</html>
