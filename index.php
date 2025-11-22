<?php
$students = json_decode(file_get_contents(__DIR__ . '/students.json'), true);

function getRowHeight($name) {
    $commaCount = substr_count($name, ',');
    if ($commaCount >= 10) {
        return 240; // Triple height for very long names (13+ people)
    } elseif ($commaCount >= 7) {
        return 180; // 2.25x height for long names (8-12 people)
    } elseif ($commaCount >= 3) {
        return 140; // Double height for medium names (4-7 people)
    }
    return 80; // Default height for short names (1-3 people)
}
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
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 20px;
  }

  h1 {
    color: #003366;
    font-size: 2rem;
    margin-bottom: 30px;
  }

  /* Column headers styling */
  .headers {
    width: 900px;
    display: grid;
    grid-template-columns: 1.2fr 1.2fr 1.5fr 1.5fr;
    gap: 15px;
    padding: 18px;
    background: #003366;
    border-radius: 10px 10px 0 0;
    border-bottom: 3px solid #007BFF;
    font-weight: bold;
    color: white;
    text-align: center;
    font-size: 16px;
    margin-bottom: 0;
  }

  /* Expanded list container size */
  .list-container {
    position: relative;
    width: 900px;
    height: auto;
    overflow: hidden;
    border-radius: 0 0 15px 15px;
    background: #fff;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    transition: height 0.35s ease;
  }

  /* Fixed highlight overlay at top with larger size */
  .highlight-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 80px;
    background: rgba(0, 123, 255, 0.1);
    border: 3px solid #007BFF;
    border-radius: 5px;
    pointer-events: none;
    z-index: 10;
    box-shadow: inset 0 0 10px rgba(0, 123, 255, 0.2);
  }

  ul {
    list-style: none;
    padding: 0;
    margin: 0;
    transition: transform 0.5s cubic-bezier(0.23, 1, 0.32, 1);
  }

  /* Larger list items with 4 columns */
  li {
    display: grid;
    grid-template-columns: 1.2fr 1.2fr 1.5fr 1.5fr;
    gap: 15px;
    align-items: center;
    padding: 18px;
    text-align: center;
    font-size: 16px;
    color: #333;
    border-bottom: 1px solid #eee;
  }

  /* Dynamic font sizing for long names */
  li span.name-cell {
    line-height: 1.4;
    word-wrap: break-word;
    overflow-wrap: break-word;
  }

  li.active {
    background-color: transparent;
    color: #333;
    font-weight: normal;
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

<!-- Column headers -->
<div class="headers">
  <div>Category</div>
  <div>Position</div>
  <div>Student or Group</div>
  <div>School</div>
</div>

<div class="list-container">
  <!-- Fixed highlight overlay at top -->
  <div class="highlight-overlay" id="highlightOverlay"></div>

  <!-- List with 4 columns per row -->
  <ul id="studentList">
    <?php 
    foreach ($students as $index => $student): 
      $rowHeight = getRowHeight($student['name']);
    ?>
      <!-- Added inline height and data attributes for dynamic sizing -->
      <li style="height: <?= $rowHeight ?>px;" data-height="<?= $rowHeight ?>" data-index="<?= $index ?>">
        <span><?= htmlspecialchars($student['category']) ?></span>
        <span><?= htmlspecialchars($student['position']) ?></span>
        <span class="name-cell"><?= htmlspecialchars($student['name']) ?></span>
        <span><?= htmlspecialchars($student['school']) ?></span>
      </li>
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
  const highlightOverlay = document.getElementById("highlightOverlay");
  const listContainer = document.querySelector('.list-container');

  let currentIndex = 0;

  const itemOffsets = [];
  let cumulativeOffset = 0;
  items.forEach((item, index) => {
    itemOffsets.push(cumulativeOffset);
    const height = parseInt(item.getAttribute('data-height'));
    cumulativeOffset += height;
  });

  function updateList() {
    // Keep the list static at the top; reveal items by increasing container height.
    list.style.transform = `translateY(0px)`;

    const currentHeight = parseInt(items[currentIndex].getAttribute('data-height'));
    const offset = itemOffsets[currentIndex];

    // Position the highlight overlay over the current item
    highlightOverlay.style.transform = `translateY(${offset}px)`;
    highlightOverlay.style.height = `${currentHeight}px`;

    // Expand container to show items from 0..currentIndex (inclusive)
    const containerHeight = offset + currentHeight;
    listContainer.style.height = `${containerHeight}px`;

    // Update button states
    prevBtn.disabled = currentIndex === 0;
    nextBtn.disabled = currentIndex === items.length - 1;

    // Toggle active class for accessibility / styling
    items.forEach((it, idx) => it.classList.toggle('active', idx === currentIndex));
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
