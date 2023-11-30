document.addEventListener('DOMContentLoaded', function() {
    
    document.onclick = function(event) {
        if (event.target.matches('.editButton')) {
            var userId = event.target.dataset.userId;
            document.getElementById("userIdForRoleChange").value = userId;
            document.getElementById("roleModal").style.display = "block";
        }
        else if (event.target.matches('.closeRole')) {
            document.getElementById("roleModal").style.display = "none";
        }
        else if (event.target == document.getElementById("roleModal")) {
            document.getElementById("roleModal").style.display = "none";
        }
    };

    document.addEventListener('submit', function(e) {
        if (e.target.matches('#roleForm')) {
            e.preventDefault();
            var Id = document.getElementById("userIdForRoleChange").value; 
            var roleId = document.querySelector('input[name="role"]:checked').value;

            fetch('/api/update-role', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ userId: Id, role: roleId })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    document.getElementById("roleModal").style.display = "none";
                    window.location.reload();
                } else {
                    alert(data.error);
                }
            })
            .catch(error => {
                console.error('Error: ', error);
            });
        }
    });
});
