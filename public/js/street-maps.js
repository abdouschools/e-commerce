var villes = {
    "Griffa-noisy": {
    "lat": 48.827734,
    "lon": 2.578548
    },
    "Griffa-Chelles": {
    "lat": 48.8858065,
    "lon": 2.6174950999999997
    },
    "Griffa-torcy": {
    "lat": 48.8485718,
    "lon": 2.6458763
    },
    "Griffa-lagny": {
    "lat": 48.87296,
    "lon": 2.713016
    }
    };
    var tableauMarqueurs = [];
    
    // On initialise la carte
    var carte = L.map('maCarte').setView([
    48.852969, 2.349903
    ], 7);
    
    // On charge les "tuiles"
    L.tileLayer('https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', { // Il est toujours bien de laisser le lien vers la source des données
    attribution: 'données © <a href="//osm.org/copyright">OpenStreetMap</a>/ODbL - rendu <a href="//openstreetmap.fr">OSM France</a>',
    minZoom: 1,
    maxZoom: 20
    }).addTo(carte);
    L.Routing.control({geocoder: L.Control.Geocoder.nominatim()}).addTo(carte)
    var marqueurs = L.markerClusterGroup();
    
    // On personnalise le marqueur
    var icone = L.icon({
    iconUrl: "image/icon.png",
    iconSize: [
    50, 50
    ],
    iconAnchor: [
    25, 50
    ],
    popupAnchor: [0, -50]
    })
    
    // On parcourt les différentes villes
    for (ville in villes) { // On crée le marqueur et on lui attribue une popup
    var marqueur = L.marker([
    villes[ville].lat,
    villes[ville].lon
    ], {icon: icone}); // .addTo(carte); Inutile lors de l'utilisation des clusters
    marqueur.bindPopup("<p>" + ville + "</p>");
    marqueurs.addLayer(marqueur);
    // On ajoute le marqueur au groupe
    
    // On ajoute le marqueur au tableau
    tableauMarqueurs.push(marqueur);
    }
    // On regroupe les marqueurs dans un groupe Leaflet
    var groupe = new L.featureGroup(tableauMarqueurs);
    
    // On adapte le zoom au groupe
    carte.fitBounds(groupe.getBounds().pad(0.5));
    
    carte.addLayer(marqueurs);