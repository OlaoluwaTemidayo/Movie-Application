    
const API_KEY = '2b6d0226ebea157ba4d75651899a42e9';
const API_URL = `https://api.themoviedb.org/3/movie/popular?api_key=${API_KEY}`;
const IMAGE_BASE_URL = 'https://image.tmdb.org/t/p/w500';

document.addEventListener('DOMContentLoaded', function() {
    fetchMovies();
});

async function fetchMovies() {
    try {
        const response = await fetch(API_URL);
        const data = await response.json();
        displayMovies(data.results);
    } catch (error) {
        console.error('Error fetching movies:', error);
        document.getElementById('movies-grid').innerHTML = 
            '<p class="error">Error loading movies. Please try again later.</p>';
    }
}

function displayMovies(movies) {
    const moviesGrid = document.getElementById('movies-grid');
    moviesGrid.innerHTML = '';

    movies.forEach(movie => {
        const movieCard = createMovieCard(movie);
        moviesGrid.appendChild(movieCard);
    });
}

function createMovieCard(movie) {
    const card = document.createElement('div');
    card.className = 'movie-card';
    card.innerHTML = `
        <img src="${IMAGE_BASE_URL}${movie.poster_path}" alt="${movie.title}">
        <div class="movie-info">
            <h2>${movie.title}</h2>
            <p>Rating: ${displayRating(movie.vote_average)}</p>
            <p>Release Date: ${formatDate(movie.release_date)}</p>
            <p>Overview: ${movie.overview}</p>
            <a href="movie.php?id=${movie.id}" class="btn">View Details</a>
        </div>
    `;
    return card;
}


function displayRating(rating) {
    const stars = Math.round(rating / 2); // Convert 10-point scale to 5-star scale
    return '★'.repeat(stars) + '☆'.repeat(5 - stars);
}

function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString();
}