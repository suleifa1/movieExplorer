<?php
// Model/MovieModel.php

include_once './tech/config.php'; 

class MovieModel {
    protected $db;

    public function __construct() {
        $this->db = $this->dbConnect();
    }
    private function dbConnect() {
        global $dbConfig;
        $conn = new mysqli($dbConfig['servername'], $dbConfig['username'], $dbConfig['password'], $dbConfig['dbname']);
        
        if ($conn->connect_error) {
            error_log("Connection error: " . $conn->connect_error);
            return null;
        }
        
        return $conn;
    }
    public function getAllMovies() {
        $sql = "SELECT m.id, m.title, m.description, m.poster, m.rating, GROUP_CONCAT(g.name) AS genres
                FROM movies AS m
                LEFT JOIN movies_has_genres AS mg ON m.id = mg.movies_id
                LEFT JOIN genres AS g ON mg.genres_id = g.id
                GROUP BY m.id";
    
        $stmt = $this->db->prepare($sql);
        
        $stmt->execute();
    
        $result = $stmt->get_result();
    
        $movies = [];
        while ($row = $result->fetch_assoc()) {
            $genres = isset($row['genres']) ? (string)$row['genres'] : '';
            $row['genres'] = $genres === '' ? [] : explode(',', $genres);
            $movies[] = $row;
        }
        
        $stmt->close();
        
        return $movies;
    }
    public function getMovieIdByReportId($reportId) {
        $stmt = $this->db->prepare("SELECT comments_id FROM reports WHERE id = ?");
        $stmt->bind_param('i', $reportId);
        $stmt->execute();
        $result = $stmt->get_result();
        $report = $result->fetch_assoc();
        $stmt->close();
    
        if (!$report) {
            return false;
        }
    
        $commentsId = $report['comments_id'];
        $stmt = $this->db->prepare("SELECT movies_id FROM comments WHERE id = ?");
        $stmt->bind_param('i', $commentsId);
        $stmt->execute();
        $result = $stmt->get_result();
        $comment = $result->fetch_assoc();
        $stmt->close();
    
        return $comment ? $comment['movies_id'] : false;
    }
    public function getMovieById($movieId) {
        $sql = "SELECT m.id, m.title, m.description, m.poster, m.rating, GROUP_CONCAT(g.name) AS genres
                FROM movies AS m
                LEFT JOIN movies_has_genres AS mg ON m.id = mg.movies_id
                LEFT JOIN genres AS g ON mg.genres_id = g.id
                WHERE m.id = ?
                GROUP BY m.id";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $movieId);
        $stmt->execute();
        $result = $stmt->get_result();
        $movie = $result->fetch_assoc();
        if ($movie) {
            $movie['genres'] = explode(',', $movie['genres']);
        }
        return $movie;
    }
    public function addMovie($title, $description, $rating, $poster, $genres) {
        $this->db->begin_transaction();
        try {
            $sql = "INSERT INTO movies (title, description, rating, poster) VALUES (?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('ssds', $title, $description, $rating, $poster);
            $stmt->execute();
            $movieId = $stmt->insert_id;
    
            $this->addMovieGenres($movieId, $genres); 
    
            $this->db->commit();
            return $movieId;
        } catch (mysqli_sql_exception $e) {
            $this->db->rollback();
            return false;
        }
    }
    public function addMovieGenres($movie_id, $genres) {
        $genre_sql = "INSERT INTO movies_has_genres (movies_id, genres_id) VALUES (?, ?)";
        $stmt = $this->db->prepare($genre_sql);
        if(is_array($genres)){
            foreach ($genres as $genre_id) {
                $stmt->bind_param("ii", $movie_id, $genre_id);
                $stmt->execute();
            }
        }else{
            $stmt->bind_param("ii", $movie_id, $genres);
            $stmt->execute();
        }

    }
    public function getGenres() {
        $genre_query = "SELECT * FROM genres";
        $stmt = $this->db->prepare($genre_query);
    
        $stmt->execute();
    
        $result = $stmt->get_result();
    
        $genres = [];
        while ($row = $result->fetch_assoc()) {
            $genres[] = $row;
        }
    
        $stmt->close();
    
        return $genres;
    }
    public function getMoviesByGenre($genreName) {
        $conn = $this->dbConnect();
        
        $sql = "SELECT m.id, m.title, m.description, m.poster, m.rating, GROUP_CONCAT(g.name SEPARATOR ', ') AS genres
                FROM movies AS m
                LEFT JOIN movies_has_genres AS mg ON m.id = mg.movies_id
                LEFT JOIN genres AS g ON mg.genres_id = g.id
                GROUP BY m.id
                HAVING LOWER(genres) LIKE LOWER(CONCAT('%', ?, '%'))";
        
        $stmt = $conn->prepare($sql);
        $genreName = "%$genreName%"; 
        $stmt->bind_param('s', $genreName);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $movies = [];
        while ($row = $result->fetch_assoc()) {
            $row['genres'] = explode(',', $row['genres']); 
            $movies[] = $row; 
        }
        
        $stmt->close();
        $conn->close();
        
        return $movies;
    }
    public function updateMovie($movieId, $title, $description, $rating, $poster, $genres) {
        $this->db->begin_transaction();
        try {
            
            $sql = "UPDATE movies SET title = ?, description = ?, rating = ?, poster = ? WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('ssdsi', $title, $description, $rating, $poster, $movieId);
            $stmt->execute();
            $stmt->close();
    
            
            $sql = "DELETE FROM movies_has_genres WHERE movies_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param('i', $movieId);
            $stmt->execute();
            $stmt->close();
    
            $this->addMovieGenres($movieId, $genres);
    
            $this->db->commit();
            return true;
        } catch (mysqli_sql_exception $e) {
            $this->db->rollback();
            return false;
        }
    }
    public function deleteMovie($movieId) {
        $sql = "DELETE FROM movies WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        if ($stmt === false) {
            return false;
        }
    
        $stmt->bind_param('i', $movieId);
        $result = $stmt->execute();
        $stmt->close();
    
        return $result; 
    }
}
