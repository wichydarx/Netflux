console.log('Hello World from app.js');

async function fetchVideos (){
    await fetch('/api/videos')
    .then(response => response.json())
    .then(data => console.log(data));
}

fetchVideos();