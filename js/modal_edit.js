document.addEventListener('DOMContentLoaded', ()=>{
    
    document.addEventListener('click', function(event) {
        var modal3 = document.getElementById('editMovieModal');
        var clickedElement = event.target;

        if (clickedElement.id === 'editMovieModalOpenBtn') {
            modal3.style.display = 'block';
        }
        else if (clickedElement.classList.contains('close') || clickedElement === modal3) {
            modal3.style.display = 'none';
        }
    });
    document.addEventListener('submit', function(e) {
        if (e.target.matches('#update-movie-form')) {
            e.preventDefault();
    
            var formData = new FormData(e.target);
            var formDataObj = {};
            formData.forEach(function(value, key){
                if (formDataObj.hasOwnProperty(key)) {
                    if (Array.isArray(formDataObj[key])) {
                        formDataObj[key].push(value);
                    } else {
                        
                        formDataObj[key] = [formDataObj[key], value];
                    }
                } else {
                    formDataObj[key] = value;
                }
            });
            formDataObj.movieId = document.querySelector('.movie-content').getAttribute('data-value');
            const jsonData = JSON.stringify(formDataObj);
    
            fetch('/movie/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: jsonData
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    window.location.reload();
                    alert('Фильм успешно обновлен в базе данных.');
                } else {
                    alert('Произошла ошибка при обновлении фильма.');
                }
            })
            .catch(error => {
                alert('Ошибка: ' + error);
            });

        }
    });
    document.addEventListener('click', function(e) {
        if (e.target.matches('#deleteMovie')) {
            var movieId = document.querySelector('.movie-content').getAttribute('data-value');
            deleteMovie(movieId);
        }
    });
    
    function deleteMovie(Id) {
        fetch('/api/delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({type: 'movie', movieId: Id })
        })
        .then(response => response.json())
        .then( data => {
            if (data.success) {
                alert(data.result);
                window.history.back();
                window.location.reload();
            } else {
                alert(data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Произошла ошибка при запросе.');
        });
    }
    
})