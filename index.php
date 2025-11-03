<?php
// Load JSON file
$jsonFile = 'indonesia_student_convention_2025.json';
$data = json_decode(file_get_contents($jsonFile), true);

// Extract only Student_or_Group values
$students = array_map(fn($item) => $item['Student_or_Group'], $data);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Indonesia Student Convention 2025</title>
<style>
  * {
    box-sizing: border-box;
  }

  body {
    margin: 0;
    padding: 0;
    font-family: "Segoe UI", Arial, sans-serif;
    background: linear-gradient(135deg, #e0f2ff, #f8faff);
    height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
  }

  h1 {
    color: #003366;
    font-size: 2rem;
    margin-bottom: 30px;
  }

  .list-container {
    position: relative;
    width: 500px;
    height: 60vh; /* occupy 60% of the viewport height */
    overflow: hidden;
    border-radius: 15px;
    background: #fff;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
  }

  ul {
    list-style: none;
    padding: 0;
    margin: 0;
    transition: transform 0.5s cubic-bezier(0.23, 1, 0.32, 1);
  }

  li {
    height: 60px;
    line-height: 60px;
    text-align: center;
    font-size: 20px;
    color: #333;
    border-bottom: 1px solid #eee;
  }

  li.active {
    background-color: #007BFF;
    color: white;
    font-weight: bold;
    transform: scale(1.08);
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 123, 255, 0.4);
    transition: all 0.25s ease-in-out;
  }

  .buttons {
    display: flex;
    justify-content: center;
    gap: 40px;
    margin-top: 25px;
  }

  button {
    background: #007BFF;
    color: white;
    border: none;
    border-radius: 8px;
    padding: 12px 30px;
    font-size: 18px;
    cursor: pointer;
    transition: background 0.3s, transform 0.2s;
  }

  button:hover:not(:disabled) {
    background: #0056b3;
    transform: scale(1.05);
  }

  button:disabled {
    background: #ccc;
    cursor: not-allowed;
  }
</style>
</head>
<body>

<h1>Indonesia Student Convention 2025</h1>

<div class="list-container">
  <ul id="studentList">
    <?php foreach ($students as $student): ?>
      <li><?= htmlspecialchars($student) ?></li>
    <?php endforeach; ?>
  </ul>
</div>

<div class="buttons">
  <button id="prevBtn">Previous</button>
  <button id="nextBtn">Next</button>
</div>

<script>
  const list = document.getElementById("studentList");
  const items = Array.from(list.getElementsByTagName("li"));
  const prevBtn = document.getElementById("prevBtn");
  const nextBtn = document.getElementById("nextBtn");

  let currentIndex = 0;
  const itemHeight = 60; // each li height in px
  const containerHeight = document.querySelector(".list-container").clientHeight;
  const visibleCount = Math.floor(containerHeight / itemHeight);

  function updateList() {
    items.forEach((item, i) => item.classList.toggle("active", i === currentIndex));

    // Keep active item centered vertically
    const translateY = (containerHeight / 2) - ((currentIndex + 0.5) * itemHeight);
    list.style.transform = `translateY(${translateY}px)`;

    prevBtn.disabled = currentIndex === 0;
    nextBtn.disabled = currentIndex === items.length - 1;
  }

  prevBtn.addEventListener("click", () => {
    if (currentIndex > 0) currentIndex--;
    updateList();
  });

  nextBtn.addEventListener("click", () => {
    if (currentIndex < items.length - 1) currentIndex++;
    updateList();
  });

  updateList();
</script>

</body>
</html>
