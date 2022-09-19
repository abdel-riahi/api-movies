export function fetchmovie(){
    const genre = document.querySelectorAll('input[name="genre"]');
    genre.forEach((element) => {
        element.addEventListener('change', function(){
            if(this.checked){
                let id = this.value;
                fetch('/api/post/movies_genre/'+ id, {
                    headers: { "Content-Type": "text/html; charset=utf-8" },
                    method: 'POST'
                })
                .then(function(response) {
                    return response.text();
                })
                .then(function(html) {
                    document.querySelector("#list-movies").innerHTML = html; 
                })
                .catch((error) => {
                    console.error('Error:', error);
                })
            }
        })
    })
}

    
