<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Log - LogBook</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Arial', sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      padding: 20px;
      display: flex;
      justify-content: center;
      align-items: flex-start;
    }

    .container {
      background-color: white;
      padding: 2.5rem;
      border-radius: 12px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
      width: 100%;
      max-width: 800px;
      margin-top: 20px;
      animation: slideIn 0.5s ease-out;
    }

    @keyframes slideIn {
      from {
        opacity: 0;
        transform: translateY(-20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    h1 {
      text-align: center;
      color: #333;
      margin-bottom: 2rem;
      font-size: 2.2rem;
      font-weight: 600;
    }

    .form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1.5rem;
      margin-bottom: 1.5rem;
    }

    .form-group {
      display: flex;
      flex-direction: column;
    }

    .form-group.full-width {
      grid-column: 1 / -1;
    }

    label {
      display: block;
      margin-bottom: 0.5rem;
      color: #555;
      font-weight: 500;
      font-size: 0.95rem;
    }

    input[type="text"],
    input[type="date"],
    input[type="time"],
    textarea {
      width: 100%;
      padding: 0.875rem;
      border: 2px solid #e1e5e9;
      border-radius: 8px;
      font-size: 1rem;
      transition: all 0.3s ease;
      background-color: #f8f9fa;
      font-family: inherit;
    }

    input[type="text"]:focus,
    input[type="date"]:focus,
    input[type="time"]:focus,
    textarea:focus {
      outline: none;
      border-color: #667eea;
      background-color: white;
      box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    textarea {
      resize: vertical;
      min-height: 120px;
    }

    .file-upload-area {
      border: 2px dashed #e1e5e9;
      border-radius: 8px;
      padding: 2rem;
      text-align: center;
      background-color: #f8f9fa;
      transition: all 0.3s ease;
      cursor: pointer;
      position: relative;
      overflow: hidden;
    }

    .file-upload-area:hover {
      border-color: #667eea;
      background-color: #f0f4ff;
    }

    .file-upload-area.dragover {
      border-color: #667eea;
      background-color: #e8f2ff;
    }

    .upload-icon {
      font-size: 3rem;
      color: #667eea;
      margin-bottom: 1rem;
    }

    .upload-text {
      color: #666;
      font-size: 1.1rem;
      margin-bottom: 0.5rem;
    }

    .upload-subtext {
      color: #999;
      font-size: 0.9rem;
    }

    #picture {
      position: absolute;
      opacity: 0;
      width: 100%;
      height: 100%;
      cursor: pointer;
    }

    .preview {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
      gap: 1rem;
      margin-top: 1.5rem;
    }

    .preview-item {
      position: relative;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      transition: transform 0.2s ease;
    }

    .preview-item:hover {
      transform: scale(1.02);
    }

    .preview img {
      width: 100%;
      height: 150px;
      object-fit: cover;
      display: block;
    }

    .remove-btn {
      position: absolute;
      top: 8px;
      right: 8px;
      background: rgba(255, 255, 255, 0.9);
      border: none;
      border-radius: 50%;
      width: 30px;
      height: 30px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.2rem;
      color: #e53e3e;
      transition: all 0.2s ease;
    }

    .remove-btn:hover {
      background: white;
      color: #c53030;
      transform: scale(1.1);
    }

    .submit-btn {
      width: 100%;
      padding: 1rem;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-size: 1.1rem;
      font-weight: 600;
      transition: all 0.3s ease;
      margin-top: 2rem;
    }

    .submit-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }

    .submit-btn:active {
      transform: translateY(0);
    }

    .back-link {
      display: inline-block;
      margin-bottom: 1rem;
      color: #667eea;
      text-decoration: none;
      font-weight: 500;
      transition: color 0.2s ease;
    }

    .back-link:hover {
      color: #764ba2;
    }

    .back-link::before {
      content: "← ";
    }

    @media (max-width: 768px) {
      .container {
        padding: 2rem;
        margin: 10px;
      }

      .form-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
      }

      h1 {
        font-size: 1.8rem;
      }

      .preview {
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
      }

      .preview img {
        height: 120px;
      }
    }

    .file-info {
      background: #f8f9fa;
      padding: 1rem;
      border-radius: 6px;
      margin-top: 1rem;
      text-align: center;
      color: #666;
    }

    .file-count {
      font-weight: 500;
      color: #667eea;
    }
  </style>
</head>
<body>
  <div class="container">
    <a href="home.php" class="back-link">Back to Home</a>
    
    <h1>Add New Log Entry</h1>

    <form action="processLog.php" method="POST" enctype="multipart/form-data" onsubmit="prepareUpload()">
      
      <div class="form-grid">
        <div class="form-group">
          <label for="logTitle">Log Title</label>
          <input type="text" id="logTitle" name="logTitle" placeholder="Enter log title..." required>
        </div>

        <div class="form-group">
          <label for="logDate">Log Date</label>
          <input type="date" id="logDate" name="logDate" required>
        </div>

        <div class="form-group">
          <label for="Timein">Time In</label>
          <input type="time" id="Timein" name="Timein" required>
        </div>

        <div class="form-group">
          <label for="Timeout">Time Out</label>
          <input type="time" id="Timeout" name="Timeout" required>
        </div>

        <div class="form-group full-width">
          <label for="logDescription">Log Description</label>
          <textarea id="logDescription" name="logDescription" placeholder="Describe your log entry in detail..." required></textarea>
        </div>

        <div class="form-group full-width">
          <label>Pictures</label>
          <div class="file-upload-area" onclick="document.getElementById('picture').click()">
            <div class="upload-icon">📸</div>
            <div class="upload-text">Click to upload pictures</div>
            <div class="upload-subtext">or drag and drop images here</div>
            <input type="file" id="picture" name="picture[]" accept="image/*" multiple>
          </div>
          
          <div class="file-info" id="fileInfo" style="display: none;">
            <span class="file-count" id="fileCount">0</span> image(s) selected
          </div>

          <div class="preview" id="preview"></div>
        </div>
      </div>

      <button type="submit" class="submit-btn">Add Log Entry</button>
    </form>
  </div>

  <script>
    const input = document.getElementById('picture');
    const preview = document.getElementById('preview');
    const fileInfo = document.getElementById('fileInfo');
    const fileCount = document.getElementById('fileCount');
    const uploadArea = document.querySelector('.file-upload-area');
    let selectedFiles = [];

    // Set today's date as default
    document.getElementById('logDate').valueAsDate = new Date();

    // File input change event
    input.addEventListener('change', (e) => {
      const files = Array.from(e.target.files);
      addFiles(files);
    });

    // Drag and drop functionality
    uploadArea.addEventListener('dragover', (e) => {
      e.preventDefault();
      uploadArea.classList.add('dragover');
    });

    uploadArea.addEventListener('dragleave', () => {
      uploadArea.classList.remove('dragover');
    });

    uploadArea.addEventListener('drop', (e) => {
      e.preventDefault();
      uploadArea.classList.remove('dragover');
      const files = Array.from(e.dataTransfer.files).filter(file => file.type.startsWith('image/'));
      addFiles(files);
    });

    function addFiles(files) {
      files.forEach(file => {
        if (file.type.startsWith('image/') && !selectedFiles.some(f => f.name === file.name && f.size === file.size)) {
          selectedFiles.push(file);
        }
      });
      updatePreview();
      updateFileInfo();
    }

    function updatePreview() {
      preview.innerHTML = '';
      selectedFiles.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = (e) => {
          const previewItem = document.createElement('div');
          previewItem.className = 'preview-item';
          
          const img = document.createElement('img');
          img.src = e.target.result;
          img.alt = file.name;
          
          const removeBtn = document.createElement('button');
          removeBtn.className = 'remove-btn';
          removeBtn.innerHTML = '×';
          removeBtn.type = 'button';
          removeBtn.title = 'Remove image';
          removeBtn.onclick = (e) => {
            e.stopPropagation();
            removeFile(index);
          };
          
          previewItem.appendChild(img);
          previewItem.appendChild(removeBtn);
          preview.appendChild(previewItem);
        };
        reader.readAsDataURL(file);
      });
    }

    function removeFile(index) {
      selectedFiles.splice(index, 1);
      updatePreview();
      updateFileInfo();
      updateFileInput();
    }

    function updateFileInfo() {
      if (selectedFiles.length > 0) {
        fileInfo.style.display = 'block';
        fileCount.textContent = selectedFiles.length;
      } else {
        fileInfo.style.display = 'none';
      }
    }

    function updateFileInput() {
      // Create a new DataTransfer to update the file input
      const dt = new DataTransfer();
      selectedFiles.forEach(file => dt.items.add(file));
      input.files = dt.files;
    }

    function prepareUpload() {
      // Update file input before submission
      updateFileInput();
      return true;
    }

    // Form validation
    document.querySelector('form').addEventListener('submit', (e) => {
      const timeIn = document.getElementById('Timein').value;
      const timeOut = document.getElementById('Timeout').value;
      
      if (timeIn && timeOut && timeIn >= timeOut) {
        e.preventDefault();
        alert('Time Out must be later than Time In');
        return false;
      }
    });
  </script>

</body>
</html>
