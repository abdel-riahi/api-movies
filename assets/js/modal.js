export function getMovie() { 
  const movie = document.querySelector('#list-movies .movie')
  movie.addEventListener('click', function (){
      fetch(movie.dataset.url, {
      headers: { "Content-Type": "text/html; charset=utf-8" },
      method: 'POST',
      })
      .then(function(response) {
        return response.text();
      })
      .then(function(html) {
        document.querySelector("#myModal .body-content").innerHTML = html; 
        const modal = document.querySelector("#myModal");
        const span = document.querySelector(".close");
        modal.classList.add("show");
        span.addEventListener('click', function() {
          modal.classList.remove("show");
        })
        window.addEventListener('click', function(event) {
          if (event.target == modal) {
            modal.classList.remove("show");
          }
        })
      })
      .catch((error) => {
        console.error('Error:', error);
      }); 
  });
}
    




