//<script type=\"text/javascript\" src=\"../JAVASCRIPT/cartographie1.js\"></script><script>getData();</script>

//---------ONGLETS---------------------------------------------------------------------------------------

ong1 = document.getElementById('a1');
ong2 = document.getElementById('a2');
contenu = document.getElementById('ongletsContent');

contenu.innerHTML = '<iframe src="graphSon.php" title="Carte Son" width="1000" height="630" text-align="center" background-color="white"></iframe>';

function nonactive(){
  ong1.className = "";
  ong2.className = "";
}

function active(moi){
  nonactive(); // nettoyage
  moi.className="active"; // je deviens active
}

ong1.addEventListener("click",function(){
  contenu.innerHTML = '<iframe src="graphSon.php" title="Carte Son" width="1000" height="630" text-align="center" background-color="white"></iframe>';
  active(this);
})

ong2.addEventListener("click",function(){
  contenu.innerHTML = '<iframe src="graphCo2.php" title="Carte CO2" width="1000" height="630" text-align="center" background-color="white"></iframe>';
  active(this);
})