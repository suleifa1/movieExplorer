
<div id="userInfoModal" class="modal">
  <div class="modal-content">

    <span class="close-profile">&times;</span>

    <div class="user-info">
      <h2>Profile</h2>
      <img id="userImage" src="<?= htmlspecialchars($userData['image_path']) ?>" alt="Фото пользователя" class="user-photo">
        <div class="grid-p">
            <p><b>Username:</b> <span id="username"><?= htmlspecialchars($userData['username']) ?></span></p>
            <p><b>Email:</b> <span id="email"><?= htmlspecialchars($userData['email']) ?></span></p>
            <p><b>Role:</b> <span id="role"><?= htmlspecialchars($userData['role']) ?></span></p>
            <p><b>Password:</b> <span id="password">••••••••</span></p>
        </div>
      <button id="updateInfoBtn">Update</button>
    </div>

    <div class="update-info hide">
        <i id="backToProfileBtn" class="uil uil-arrow-left"></i>

        <div class="flex-all" style="display: flex; justify-content: space-around">
          <div class="upload-container">
            <div id="previewContainer" class="preview-container">
                <img id="imagePreview" src="" class="hide"/>
                <label for="imageUpload" class="image-upload-label">
                    <i style="font-size: 100px; "class="uil uil-cloud-upload"></i>
                </label>
            </div>
            <form id="uploadForm" method="post" enctype="multipart/form-data">
                
                <input type="file" name="image" id="imageUpload" class="image-upload-input">
                
            </form>

            <div id="progressContainer" class="progress-container">
                <div id="progressBar" class="progress-bar">
                    <span id="progressText" class="progress-text"></span>
                </div>
            </div>


          </div>

          <div class="input-group-container">
            <div class="input-group">
              <label for="newPassword">New Password:</label>
              <input type="password" id="newPassword" placeholder="Enter the password" required>
            </div>
            <div class="input-group">
              <label for="confirmPassword">Confirm Password:</label>
              <input type="password" id="confirmPassword" placeholder="Repeat the password" required>
            </div>
          </div>
        </div>

        <div class="buttons-change-modal" style="display: flex; justify-content: space-around">
          <button  type="button" class="upload-button">Change image</button>
          <button id="saveNewPasswordBtn">Save New Password</button>
        </div>      
    </div>

  </div>
</div>



<link rel="stylesheet" href="../css/upload.css">


