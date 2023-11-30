<div id="roleModal" class="modal">
  <div class="modal-content">
    <span class="closeRole">&times;</span>
    <form id="roleForm">
      
      <?php if($role == 'admin'):?>
        <input type="radio" id="roleUser" name="role" value="3">
        <label for="roleUser">User</label><br>

        <input type="radio" id="roleModerator" name="role" value="2">
        <label for="roleModerator">Moderator</label><br>

        <input type="radio" id="roleAdmin" name="role" value="1">
        <label for="roleAdmin">Admin</label><br>

      <?php elseif($role = 'superadmin' ):?>
        <input type="radio" id="roleUser" name="role" value="3">
        <label for="roleUser">User</label><br>

        <input type="radio" id="roleModerator" name="role" value="2">
        <label for="roleModerator">Moderator</label><br>

        <input type="radio" id="roleAdmin" name="role" value="1">
        <label for="roleAdmin">Admin</label><br>

        <input type="radio" id="roleSuperAdmin" name="role" value="4">
        <label for="roleSuperAdmin">SuperAdmin</label><br>

      <?php endif;?>

      <input type="hidden" id="userIdForRoleChange" name="userId">
      <input type="submit" value="Change Role" id="changeRole">
      <style>
        #changeRole {
          background-color: black;
          color:#888;
          padding: 10px 20px;
          border: none;
          border-radius: 5px;
          cursor: pointer;
          margin-top: 20px;
      }

      #changeRole:hover {
          color:whitesmoke
      }
      </style>
    </form>
  </div>
</div>

