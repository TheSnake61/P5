const repbtns = document.querySelectorAll("#repbtn");
const cancbtn = document.getElementById("cancbtn");
var parentId = document.getElementById("comment_parentId");
var comtitle = document.getElementById("comtitle");

parentId.value = null;
cancbtn.style.display = "none";
console.log(parentId.value);


repbtns.forEach(repbtn => {
    repbtn.onclick = function () {



        var id = repbtn.dataset.id;
        parentId.value = id;
        console.log(parentId.value);
        console.log(id);
        cancbtn.style.display = "flex";
        cancbtn.scrollIntoView();
        repbtns.forEach(repbtn => {
            repbtn.style.display = "none";
        });
        comtitle.innerHTML = "Répondre à un commentaire:";




    };
});

cancbtn.onclick = function () {
    cancbtn.style.display = "none";
    repbtns.forEach(repbtn => {

        parentId.value = null;
        console.log(parentId.value);
        repbtn.style.display = "flex";
        comtitle.innerHTML = "Ajouter un commentaire:";


    });
};