document.addEventListener('DOMContentLoaded', ()=>{
      document.addEventListener('click', function(event) {
        var modal = document.getElementById('userInfoModal');
        if (!modal) return;
      
        var clickedElement = event.target;
        var userInfoSection = document.querySelector('.user-info');
        var updateInfoSection = document.querySelector('.update-info');
      
        function resetModalSections() {
          userInfoSection.style.display = 'block';
          updateInfoSection.style.display = 'none';
        }
      
        if (clickedElement.id === 'openProfileBtn') {
          modal.style.display = 'block';
        }
        else if (clickedElement.matches('.close-profile')) {
          modal.style.display = 'none';
          resetModalSections();
        }
        else if (clickedElement.id === 'updateInfoBtn') {
          userInfoSection.style.display = 'none';
          updateInfoSection.style.display = 'grid';
        }
        else if (clickedElement.id === 'backToProfileBtn') {
          resetModalSections();
        }
        else if (clickedElement === modal) {
          modal.style.display = 'none';
          resetModalSections();
        }
        else if (clickedElement.id === 'saveNewPasswordBtn') {
            var newPassword = document.getElementById('newPassword').value;
            var confirmPassword = document.getElementById('confirmPassword').value;
          
            if (newPassword === confirmPassword) {
              fetch('/api/update-password', {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json',
                  'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ password: newPassword })
              })
              .then(response => {
                if (response.ok) {
                  return response.json();
                } else {
                  throw new Error('Something went wrong');
                }
              })
              .then(response => {
                console.log(response);
                document.getElementById('newPassword').value = '';
                document.getElementById('confirmPassword').value = '';
                alert('Password changed successfully!');
              })
              .catch(error => {
                console.error(error);
              });
            } else {
              alert('Passwords do not match. Please try again.');
            }
        }
      });
})