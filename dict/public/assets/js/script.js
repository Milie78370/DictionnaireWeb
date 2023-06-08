
// cacher le bouton filtrage en JS

document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("filter").onclick = function() {
        document.getElementById("form").hidden = false;
    };
});

document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("btnrecherche").onclick = function (e){
        document.getElementById("form").hidden = true;
        document.getElementById("cardToutProd").hidden = true;

    };
});

