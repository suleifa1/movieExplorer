<div class="upload-container">
    <div id="previewContainer" class="preview-container">
        <img id="imagePreview" src="" class="hide"/>
        <label for="imageUpload" class="image-upload-label">
            <i style="font-size: 128px; "class="uil uil-cloud-upload"></i>
        </label>
    </div>
    <form id="uploadForm" action="upload.php" method="post" enctype="multipart/form-data">
        
        <input type="file" name="image" id="imageUpload" class="image-upload-input">
        <button type="submit" class="upload-button">Загрузить</button>
    </form>

    <div id="progressContainer" class="progress-container">
        <div id="progressBar" class="progress-bar">
            <span id="progressText" class="progress-text"></span>
        </div>
    </div>

    <script>
        document.getElementById('imageUpload').addEventListener('change', function(event) {
            var file = event.target.files[0];
            var previewContainer = document.getElementById('previewContainer');
            var imagePreview = document.getElementById('imagePreview');
            var span = document.querySelector('.image-upload-label');
            
            // Проверка типа файла перед отображением превью
            if (file && (file.type === "image/jpeg" || file.type === "image/png")) {
                var reader = new FileReader();
                
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.classList.toggle('hide');
                    span.classList.toggle('hide');
                };
                
                reader.readAsDataURL(file);
            } else {
                imagePreview.classList.add('hidden');
                alert('JPEG and PNG acceptable only');
            }
        });
        document.getElementById('uploadForm').addEventListener('submit', function(event) {
            event.preventDefault();

            var formData = new FormData(this);
            var xhr = new XMLHttpRequest();
            
            xhr.open('POST', '/api/upload', true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

            xhr.upload.onprogress = function(event) {
                if (event.lengthComputable) {
                    var percentComplete = Math.round((event.loaded / event.total) * 100);
                    var progressBar = document.getElementById('progressBar');
                    var progressText = document.getElementById('progressText');
                    progressBar.style.width = percentComplete + '%';
                    progressText.textContent = percentComplete + '%';
                }
            };

            xhr.onload = function() {
                if (xhr.status === 200) {
                    console.log('Upload complete:', xhr.responseText);
                    document.getElementById('progressText').textContent = 'Uploaded';
                } else {
                    console.log('Upload failed:', xhr.statusText);
                }
            };

            xhr.onerror = function() {
                console.log('Upload failed. An error occurred during the transfer.');
            };

            xhr.send(formData);
        });

    </script>
    <link rel="stylesheet" href="../css/upload.css">
</div>