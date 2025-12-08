<?php
$students = json_decode(file_get_contents(__DIR__ . '/student_sorted.json'), true);

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
    background-image: url('./image.png');
    background-size: cover;
    background-attachment: fixed;
    background-position: center;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 20px;
  }

  :root {
    --row-gap: 10mm; /* gap between rows */
  }

  h1 {
    color: #ac8505ff;
    font-size: 3rem;
    margin-bottom: 30px;
    margin-left: auto;
    margin-right: auto;
    width: 100%;
    max-width: 1100px;
    text-align: center;
    text-shadow: 
      2px 2px 0px #333,
      4px 4px 0px #000,
      6px 6px 0px rgba(0, 0, 0, 0.5),
      0px 0px 20px rgba(255, 215, 0, 0.6),
      inset -2px -2px 5px rgba(0, 0, 0, 0.3);
    font-weight: bold;
    letter-spacing: 1px;
    transform: perspective(500px) rotateX(2deg);
  }

  /* Column headers styling */
  .headers {
    width: 100%;
    display: grid;
    grid-template-columns: 1.2fr 1.2fr 1.5fr 1.5fr;
    gap: 15px;
    padding: 18px;
    padding-left: calc(18px + 10mm);
    padding-right: calc(18px + 10mm);
    background: #003366;
    border-radius: 10px 10px 0 0;
    border-bottom: 3px solid #007BFF;
    font-weight: bold;
    color: white;
    text-align: center;
    font-size: 20px;
    margin-bottom: 0;
  }

  /* Expanded list container size */
  .list-container {
    position: relative;
    width: 100%;
    height: auto; /* will be replaced by JS */
    overflow: hidden;
    border-radius: 0 0 15px 15px;
    background: transparent;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
  }

  /* Layout for side controls + list */
  .content {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 20px;
    justify-content: center;
    width: 100%;
    margin-top: 10px;
  }

  .list-wrap {
    width: calc(100% - 80px);
    max-width: none;
    margin-left: 40px;
    margin-right: 40px;
    flex: 0 0 auto;
  }

  .bottom-buttons {
    display: flex;
    flex-direction: row;
    gap: 40px;
    align-items: center;
    justify-content: center;
    margin-top: 40px;
    margin-bottom: 20px;
  }

  .bottom-buttons button {
    padding: 12px 30px;
    font-size: 18px;
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
    transition: transform 0.45s cubic-bezier(0.23, 1, 0.32, 1);
  }

  /* Larger list items with 4 columns */
  li {
    display: grid;
    grid-template-columns: 1.2fr 1.2fr 1.5fr 1.5fr;
    gap: 10px;
    align-items: center;
    padding: 24px;
    padding-left: calc(24px + 10mm);
    padding-right: calc(24px + 10mm);
    text-align: center;
    font-size: 22px;
    color: #333;
    border-bottom: 1px solid #eee;
    margin-bottom: var(--row-gap);
    background: #fff;
  }

  /* Dynamic font sizing for long names */
  li span.name-cell {
    display: block;
    line-height: 1.4;
    white-space: normal;
    overflow-wrap: anywhere;
    word-break: break-word;
  }

  li.active {
    color: #333;
    font-weight: normal;
  }

  /* Medal backgrounds for positions */
  .pos-1 {
    background: linear-gradient(90deg, #FFD700 0%, #FFE57F 100%);
  }
  .pos-2 {
    background: linear-gradient(90deg, #C0C0C0 0%, #E0E0E0 100%);
  }
  .pos-3 {
    background: linear-gradient(90deg, #CD7F32 0%, #D99B6B 100%);
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
<!-- Column headers (moved into .list-wrap to keep alignment with list) -->

<div class="content">
  

  <div class="list-wrap">
    <!-- Column headers moved here so their left edge lines up with the list -->
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
      <?php $posClass = 'pos-' . (int) $student['position']; ?>
      <li class="<?= $posClass ?>" style="height: <?= $rowHeight ?>px;" data-height="<?= $rowHeight ?>" data-index="<?= $index ?>">
        <span><?= htmlspecialchars($student['category']) ?></span>
        <span><?= htmlspecialchars($student['position']) ?></span>
        <span class="name-cell"><?= htmlspecialchars($student['name']) ?></span>
        <span><?= htmlspecialchars($student['school']) ?></span>
      </li>
    <?php endforeach; ?>
  </ul>

    </div>
  </div>
</div>

<div class="bottom-buttons">
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
  let visibleCount = 1; // Start with 1 item visible
  const maxVisible = 7; // Maximum items to show
  const maxViewportHeight = 500; // Maximum viewport height in px

  const itemOffsets = [];
  let cumulativeOffset = 0;
  
  // Convert CSS `--row-gap` to pixels
  function mmToPx(value) {
    const el = document.createElement('div');
    el.style.width = value;
    el.style.position = 'absolute';
    el.style.visibility = 'hidden';
    document.body.appendChild(el);
    const px = el.getBoundingClientRect().width;
    document.body.removeChild(el);
    return px;
  }

  const gapCss = getComputedStyle(document.documentElement).getPropertyValue('--row-gap') || '10mm';
  const gapPx = mmToPx(gapCss.trim());

  items.forEach((item, index) => {
    itemOffsets.push(cumulativeOffset);
    const height = parseInt(item.getAttribute('data-height'));
    cumulativeOffset += height + gapPx;
  });

  // Calculate container height based on visible items (max 7 items or 500px)
  function calculateContainerHeight() {
    let totalHeight = 0;
    let itemCount = 0;
    
    // Add heights for all visible items up to visibleCount or height limit
    for (let i = 0; i < visibleCount && i < items.length; i++) {
      const rowHeight = parseInt(items[i].getAttribute('data-height'));
      const gapHeight = i > 0 ? gapPx : 0;
      const newHeight = totalHeight + rowHeight + gapHeight;
      
      // Stop if we hit the max height or max items
      if (newHeight > maxViewportHeight || itemCount >= maxVisible) {
        break;
      }
      
      totalHeight = newHeight;
      itemCount++;
    }
    
    // Return actual height of visible items (shrinks to fit)
    return totalHeight;
  }

  // topVisibleIndex controls which item is at the top of the viewport
  let topVisibleIndex = 0;

  function updateList() {
    // Keep current item visible within viewport
    if (currentIndex >= visibleCount) {
      topVisibleIndex = currentIndex - visibleCount + 1;
    } else {
      topVisibleIndex = 0;
    }

    const scrollOffset = itemOffsets[topVisibleIndex] || 0;
    // Translate the list upward to make topVisibleIndex the first visible
    list.style.transform = `translateY(-${scrollOffset}px)`;

    const currentHeight = parseInt(items[currentIndex].getAttribute('data-height'));
    // Position overlay relative to the viewport (account for scrollOffset)
    const overlayY = itemOffsets[currentIndex] - scrollOffset;
    highlightOverlay.style.transform = `translateY(${overlayY}px)`;
    highlightOverlay.style.height = `${currentHeight}px`;

    // Container height grows dynamically based on visible items
    listContainer.style.height = `${calculateContainerHeight()}px`;

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
    if (currentIndex < items.length - 1) {
      currentIndex++;
      // Grow viewport up to maximum of 7 items OR 500px height
      if (visibleCount < maxVisible) {
        // Check if adding one more item would exceed height limit
        let testHeight = 0;
        for (let i = 0; i < visibleCount + 1 && i < items.length; i++) {
          const rowHeight = parseInt(items[i].getAttribute('data-height'));
          testHeight += rowHeight + (i > 0 ? gapPx : 0);
        }
        if (testHeight <= maxViewportHeight) {
          visibleCount++;
        }
      }
    }
    updateList();
  });

  updateList();
</script>

</body>
</html>
