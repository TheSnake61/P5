function ajaxGet(url, callback) {
    req = new XMLHttpRequest();
    req.open("GET", url);
    req.addEventListener("load", function () {
        if (req.status >= 200 && req.status < 400) {
            callback(req.responseText);
        } else {
            console.error(req.status + " " + req.statusText + " " + url);
        }
    });
    req.addEventListener("error", function () {
        console.error("Erreur réseau avec l'URL " + url);
    });
    req.send(null);
};

const p = document.getElementById("spotify");
p.onclick = function() {
    ajaxGet("https://api.spotify.com/v1/playlists/{37i9dQZF1EtlPE3n1WDDJ1}","Accept: application/json", "Content-Type: application/json", "Authorization: Bearer BQA2-4msF8oTY-kqysmUZuWtFFUSYVHy5_XreT5xHc4--qGFZdBSa6csuLvfwVE82J9ZbroiK_lWh9pSBd5SVLmOUSLWJHaAf4k4gosB0Fg7aylG2RNnSxW4qUF4bgyS4UTGvOZ7yK5jxKBmz3qnIQ");
};