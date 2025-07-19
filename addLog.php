<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Log</title>
  <style>
    .preview img {
      width: 100px;
      height: auto;
      margin: 5px;
      border: 2px solid #ccc;
      border-radius: 5px;
      cursor: pointer;
    }
    .preview {
      display: flex;
      flex-wrap: wrap;
    }
  </style>
</head>
<body>

  <form action="addLog.php" method="post" enctype="multipart/form-data" onsubmit="prepareUpload()">
    <label for="logTitle">Log Title:</label>
    <input type="text" id="logTitle" name="logTitle" required><br>

    <label for="logDate">Log Date:</label>
    <input type="date" id="logDate" name="logDate" required><br>

    <label for="Timein">Time in:</label>
    <input type="time" id="Timein" name="Timein" required><br>

    <label for="Timeout">Time out:</label>
    <input type="time" id="Timeout" name="Timeout" required><br>

    <label for="logDescription">Log Description:</label>
    <textarea id="logDescription" name="logDescription" rows="4" required></textarea><br>

    <label for="picture">Picture:</label>
    <input type="file" id="picture" accept="image/*" multiple><br>

    <div class="preview" id="preview"></div>

    <!-- This hidden input will contain the actual files to submit -->
    <input type="hidden" name="imageData" id="imageData">

    <button type="submit">Add Log</button>
  </form>

  <script>
    const input = document.getElementById('picture');
    const preview = document.getElementById('preview');
    let selectedFiles = [];

    input.addEventListener('change', () => {
      // Convert FileList to Array
      const files = Array.from(input.files);

      // Append new files
      files.forEach(file => {
        if (!file.type.startsWith('image/')) return;
        selectedFiles.push(file);
      });

      updatePreview();
    });

    function updatePreview() {
      preview.innerHTML = '';
      selectedFiles.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = (e) => {
          const img = document.createElement('img');
          img.src = e.target.result;
          img.title = 'Click to remove';
          img.onclick = () => {
            selectedFiles.splice(index, 1);
            updatePreview();
          };
          preview.appendChild(img);
        };
        reader.readAsDataURL(file);
      });
    }

    function prepareUpload() {
      // Manually append files using FormData in addLog.php instead
      // This script just prevents accidental duplicate images
    }

    // Optional: if using advanced JavaScript upload via AJAX, let me know.
  </script>

</body>
</html>
