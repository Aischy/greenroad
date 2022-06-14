//alert("javascript actif");

//<script type=\"text/javascript\" src=\"../JAVASCRIPT/cartographie1.js\"></script><script>getData();</script>


//---------AFFICHAGE DE LA MAP EN FONCTION DE LA VILLE---------------------------------------------------


/*function controlerChoixListe(liste){
    let coordGPS;
    if (menuVille.value == "Issy-les-Moulineaux") {
        coordGPS = [48.82529821100807, 2.2795955113574067];
    }else if (menuVille.value == "Paris") {
        coordGPS = [48.853993, 2.346685];
    }
    alert (coordGPS);
}*/



var map = L.map('map').setView([48.82529821100807, 2.2795955113574067], 17); //setView([latitude,longitude], échelle)
var tiles = L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
    maxZoom: 18,
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, ' +
        'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
    id: 'mapbox/streets-v11',
    tileSize: 512,
    zoomOffset: -1
}).addTo(map);




//-------CREATION DES MAP MARKERS-----------------------------------

//création de l'objet LeafIcon (les marqueurs)
var LeafIcon = L.Icon.extend({
    options: {
        iconSize: [38, 38], //taille de l'icône
        iconAnchor:   [19, 37]//point de l'icône correspondant à la localisation
    }
});

//SON
var sonvert = new LeafIcon({iconUrl: '../IMAGES/sonvert.png'}),
sonjaune = new LeafIcon({iconUrl: '../IMAGES/sonjaune.png'}),
sonorange = new LeafIcon({iconUrl: '../IMAGES/sonorange.png'}),
sonrouge = new LeafIcon({iconUrl: '../IMAGES/sonrouge.png'}),
sonviolet = new LeafIcon({iconUrl: '../IMAGES/sonviolet.png'});

//CO2
var co2vert = new LeafIcon({iconUrl: '../IMAGES/co2vert.png'}),
co2jaune = new LeafIcon({iconUrl: '../IMAGES/co2jaune.png'}),
co2orange = new LeafIcon({iconUrl: '../IMAGES/co2orange.png'}),
co2rouge = new LeafIcon({iconUrl: '../IMAGES/co2rouge.png'}),
co2violet = new LeafIcon({iconUrl: '../IMAGES/co2violet.png'});


//-----------COULEUR AFFICHAGE DES MARKERS----------------------------------

function colorMarkerSon(donnee, type) {
    let icon;
    if (type == 'son') {
        if (donnee>=85) {
            icon = sonviolet ;
        }else if (donnee>=80) {
            icon = sonrouge;
        }else if (donnee>=75) {
            icon = sonorange;
        }else if (donnee>=70) {
            icon = sonjaune;
        }else if (donnee<70) {
            icon = sonvert;
        }
    }
    return icon;
}


function getData(){
    const xmlHttpRequest = new XMLHttpRequest();

    xmlHttpRequest.open("GET", "../PHP/apiSon.php", true);
    xmlHttpRequest.send();

    xmlHttpRequest.onreadystatechange = function(){
        if (xmlHttpRequest.status === 200 && xmlHttpRequest.readyState === 4) {
            let response = xmlHttpRequest.response;
            let response_json = JSON.parse(response);
            console.log(response_json);

            for (var key in response_json) {

                L.marker([response_json[key].latitude, response_json[key].longitude], {icon: colorMarkerSon(parseInt(response_json[key].donnee),response_json[key].type)}).addTo(map).bindPopup("I am the key."+ key);

            }
        }
    }
}


//--------IMPORTATION DES CAPTEURS-----------------------------------

















//-------IMPORTATION DES DONNEES--------------------------------------------

//LENGHT LISTE CAPTEURS : Object.keys(capteurs).length
//alert("0")
/*$(function() {
    alert ("1");
            $.getJSON('capteurs.json', function(capteurs) {
                $('#ecranBis').html('la longueur est '+capteurs[5].idCapteur);
                alert ("2");
                //var i = "capteur5";
                alert(capteurs[5].idCapteur);
                $.getJSON('donnees.json', function(donnees) {
                    alert ("3");
                        



                        
                    });
               
            });
        });*/




/*var requestURL = 'capteurs.json';
var request = new XMLHttpRequest();
request.open('GET', requestURL);

request.responseType = 'json';
request.send();

request.onload = function() {
    var capteurs = request.response;
    showCapteur(capteurs);
}*/













