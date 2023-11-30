var moviesData = [];
var comments = [];
var movie;
var data = 'all'; 
var currentSortDirection = 'asc';
var detailsButtons = document.querySelectorAll('.details-button');

document.addEventListener("DOMContentLoaded", ()=>{
  
    loadMovies();
    initilizeButtons();
    var sortDropdown = document.getElementById("sortDropdown");
    if(sortDropdown){
        sortDropdown.addEventListener("click", function (event) {
        if (event.target.tagName === "A") {
            var sortValue = event.target.dataset.value;
            if (sortValue === "asc" || sortValue === "desc" || sortValue === "rating") {
                sortMovies(sortValue);
            }
        }
    });
    }
    
    var logout = document.querySelector('.logout_a');
    if(logout){
        logout.addEventListener("click", async()=>{
            await fetch('/logout', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = data.redirect; 
                } else {
                    alert(data.error); 
                }
            });
            
            
        });
    }
 
    if(detailsButtons){
        detailsButtons.forEach((button) => {
            button.addEventListener('click', async (event)=>{
                event.preventDefault();
                data = button.dataset.value;
        
                updateURL(data);
                var movieId = data.split('/')[1]; 
                document.getElementById("moviesContainer").innerHTML = await getMovieForm(movieId);
            })
        })
    }
    var imageUploadPreview = document.getElementById('imageUpload');
    if(imageUploadPreview){
        imageUploadPreview.addEventListener('change', function(event) {
            var file = event.target.files[0];
            var previewContainer = document.getElementById('previewContainer');
            var imagePreview = document.getElementById('imagePreview');
            var span = document.querySelector('.image-upload-label');

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
    }
    var uploadButton = document.querySelector('.upload-button');
    if(uploadButton){
        uploadButton.addEventListener('click', function() {
            var form = document.getElementById('uploadForm');
            var formData = new FormData(form);
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
    
            xhr.onload = async function() {
                if (xhr.status === 200) {
                    document.getElementById('progressText').textContent = 'Uploaded';
                    const response = await fetch('/api/user', {
                        method: 'GET', 
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest' // Добавляем заголовок для определения AJAX-запроса
                        }
                    });
                    var userData = await response.json();
                    var image_path = userData['image_path'];
                    document.getElementById('userImage').src = image_path;
                } else {
                    console.log('Upload failed:', xhr.statusText);
                }
            };
    
            xhr.onerror = function() {
                console.log('Upload failed. An error occurred during the transfer.');
            };
    
            xhr.send(formData);
        });
    }

    document.addEventListener('submit', function(event) {
        if (event.target.matches('#addCommentForm')) { 
            event.preventDefault();
            
            const movieContentDiv = document.querySelector('.movie-content');
            if (movieContentDiv) {
                const movieId = movieContentDiv.getAttribute('data-value');
                const commentText = event.target.elements.comment.value;

                addMovieComment(movieId, commentText)
                    .then(() => getComments(movieId))
                    .then(comments => {
                        const cont = document.getElementById('comments-container');
                        cont.innerHTML = comments;
                    })
                    .catch(error => {
                        console.error("Ошибка при добавлении комментария:", error);
                    });
            }
        }


    });
    let currentCommentId;

    document.addEventListener('click', function(e) { 
        if (e.target.matches('.yN_dsU')) {
            currentCommentId = e.target.getAttribute("data-comment-id");
            var modal = document.getElementById("reportModal");
            if (modal) {
                modal.style.display = "block";
            }
        }else if (e.target.matches('.close')) {
            var modal = document.getElementById("reportModal");
            if (modal) {
                modal.style.display = "none";
            }
        }else if (e.target.matches('#reportModal')) {
            var modal = document.getElementById("reportModal");
            if (modal && e.target == modal) {
                modal.style.display = "none";
            }
        }else if (e.target.matches('#submitReport')) {
            e.preventDefault();
            var reportType = document.querySelector("input[name='reportType']:checked").value;
            report(currentCommentId, reportType)

            var modal = document.getElementById("reportModal");
            if (modal) {
                modal.style.display = "none";
            }
        }else if (e.target.classList.contains('yN_dsM')) {
            handleDelete(e.target);
        }
    });

    document.addEventListener('submit', function(e) {
        if (e.target.matches('#add-movie-form')) {
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
            const jsonData = JSON.stringify(formDataObj);
    
            fetch('/movie/add', {
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
                    alert('Фильм успешно добавлен в базу данных.');
                } else {
                    alert('Произошла ошибка при добавлении фильма.');
                }
            })
            .catch(error => {
                alert('Ошибка: ' + error);
            });
        }
    });
    
    if (window.location.hash.startsWith('#comment=')) {
        const commentId = window.location.hash.split('=')[1];
    
        window.setTimeout(() => {
        const commentElement = document.querySelector(`button[data-comment-id="${commentId}"]`);
        
        if (commentElement) {
            commentElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
            commentElement.closest('.comment').style.border = '2px solid blue';
        }
        }, 0);
    }
  



});

async function handleDelete(button) {
    const Id = button.getAttribute('data-comment-id');
    fetch('/api/delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({type: 'commentary', commentId: Id })
        })
        .then(response => response.json())
        .then(async data => {
            if (data.success) {
                const movieContentDiv = document.querySelector('.movie-content');
                if (movieContentDiv) {
                    const movieId = movieContentDiv.getAttribute('data-value');
                    comments = await getComments(movieId);
                    const cont = document.getElementById('comments-container');
                    cont.innerHTML = comments;
    
                    alert('Комментарий удален');
                }else{
                    window.location.reload();
                }

            } else {
                alert('Ошибка при удалении комментария: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Произошла ошибка при запросе.');
        });
        
}
function report(currentCommentId, reportType){
    fetch('/api/report', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    commentId: currentCommentId,
                    typeId: reportType
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.result);
                } else {
                    alert('Ошибка при отправке жалобы: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Произошла ошибка при запросе.');
            });
}
async function addMovieComment(movieId, commentText) {
    const response = await fetch('/api/comment', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest' 
        },
        body: JSON.stringify({movie_id: movieId, comment: commentText}),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Comment added');
        } else {
            alert('Error while adding comment' + data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Произошла ошибка при запросе.');
    });
    
}
async function getComments(dataValue) {
            const response = await fetch(`/get-comments/${dataValue}`, {
                method: 'GET', 
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest' 
                }
            });
            const comments = await response.json();
            return comments.content;
}
async function getProfileForm() {
    const response = await fetch(`/get-profile-form`, {
        method: 'GET', 
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest' 
        }
    });
    const addForm = await response.json();
    return addForm.content;
}
async function getMovieForm(movieId) {
    const response = await fetch(`/get-movie-form/${movieId}`, {
        method: 'GET', 
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest' 
        }
    });
    const movieInfo = await response.json();
    return movieInfo.content;
}
async function getAddForm() {
    const response = await fetch(`/get-add-form`, {
        method: 'GET', 
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest' 
        }
    });
    const addForm = await response.json();
    return addForm.content;
}
function loadMovies() {
    moviesData = []; 
    fetchAllMovies()
        .then(data => {
            moviesData = data;
        })
        .catch(error => console.error('Ошибка при получении данных: ' + error));
}
async function fetchAllMovies() {
    const response = await fetch('/get-movies', {
        method: 'GET', 
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest' 
        }
    });
    var movies = await response.json(); 
    return movies;
}
function updateURL(data) {
    var url = window.location.pathname;
    if (data) {
        url = `/${data}`;
    }
    window.history.pushState(null, '', url);
}
function displayMovies(movies) {
    var moviesContainer = document.getElementById('moviesContainer');
    
    moviesContainer.innerHTML = ``;

    for (var i = 0; i < movies.length; i++) {
        var movieBlock = generateMovieBlock(movies[i]);
        moviesContainer.appendChild(movieBlock);
    }
    var detailsButtons = document.querySelectorAll('.details-button')
    detailsButtons.forEach((button) => {
        button.addEventListener('click', async (event)=>{
            event.preventDefault();
            data = button.dataset.value
            updateURL(data);
            var movieId = data.split('/')[1]; 
            document.getElementById("moviesContainer").innerHTML = await getMovieForm(movieId);
        })
    })
}
function generateMovieBlock(movie) {
    var movieBlock = document.createElement('div');
    movieBlock.classList.add('movie');

    var movieDetails = `
        <div class="movie-image">
            <img src="${movie.poster}" alt="">
        </div>
        <hr class="solid">
        <div class="movie-details">
            <h2>${movie.title}</h2>
            <p class="rating">Rating: ${movie.rating} / 10</p>
            <p class="genres">Genres: ${movie.genres}</p>
            <hr class="solid">
            <div class="description-container">
                <p class="description">${movie.description}</p>
            </div>
            <hr class="solid">
            <a id="details-button" data-value="movie/${movie.id}" class="details-button">Details</a>
        </div>
    `;

    movieBlock.innerHTML = movieDetails;
    return movieBlock;
}
function initilizeButtons(){
    var genreButtons = document.querySelectorAll('.genre-button');

    genreButtons.forEach(function(button) {
        button.addEventListener('click', function(event) {
            for (var i = 0; i < genreButtons.length; i++) {
                genreButtons[i].classList.remove("active");
            }            
            button.classList.add('active');
            event.preventDefault();
            data = this.dataset.value;
            updateURL(data);

            if(data === "home"){
                var filteredMovies = moviesData;
            }else{
                var filteredMovies = moviesData.filter(function(movie) {
                    return movie.genres.some(genre => genre.toLowerCase() === data.toLowerCase());
                });
            }
            
            displayMovies(filteredMovies);
        });
        
    });

    
    
}
function sortMovies(sortValue) {
    if (sortValue === 'rating') {
        if (currentSortDirection === 'asc') {
            currentSortDirection = 'desc';
        } else {
            currentSortDirection = 'asc';
        }
    }
    if(data === 'all' || data === 'home') 
    {
        var sortedMovies = moviesData;

    }else
    {
        var sortedMovies = moviesData.filter(function(movie) {
            return movie.genres.some(genre => genre.toLowerCase() === data.toLowerCase());

        });
    }
    
    sortedMovies.sort(function(a, b) {
        if (sortValue === 'asc') {
            var titleA = a.title.toUpperCase();
            var titleB = b.title.toUpperCase();
            return titleA.localeCompare(titleB); 
        } else if (sortValue === 'desc') {
            var titleA = a.title.toUpperCase();
            var titleB = b.title.toUpperCase();
            return titleB.localeCompare(titleA); 
        } else if (sortValue === 'rating') {
            if (currentSortDirection === 'asc') {
                return a.rating - b.rating; 
            } else {
                return b.rating - a.rating; 
            }
        }
    });
    displayMovies(sortedMovies);
}
window.addEventListener('popstate', ()=> {
    this.location.reload();
});

