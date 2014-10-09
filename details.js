var req;

function loadXMLDoc(url) {
	if (window.XMLHttpRequest) {
		req = new XMLHttpRequest();
	} else if (window.ActiveXObject) {
		req = new ActiveXObject("Microsoft.XMLHTTP");
	}
	if (req) {
		req.onreadystatechange = doSomething;
		req.open("GET", url, true);
		req.send(null);
	}
}

function prepare(arr) {
	var coords = arr[1].split(', ');
	var web = '';
	if (arr[3].length != 0) {
		web = '<a href="' + arr[3] + '">Službena stranica</a>';
	}
	return '<strong>' + arr[2] + '</strong><br/>'
			+ 'Širina: ' + coords[0] + '<br/>'
			+ 'Visina: ' + coords[1] + '<br/>'
			+ web;
}

function doSomething () {
	if (req.readyState == 4) {
		if (req.status == 200) {
			spl = req.responseText.split(/Location:|Naziv:|Web:/);
			document.getElementById("detail").innerHTML = spl[0];

			var str = prepare(spl);
			showMap(spl[1].split(", "), str);
		} else {
			alert("Nije primljen 200 OK, nego:\n" + req.statusText);
		}
		document.getElementById("col-" + idg).innerHTML = col;
		clicked = false;
	}
}

var map;
var marker;

function showMap(coords, text) {
	if (!document.getElementById("map").getAttribute("style")) {
		document.getElementById("map").setAttribute("style", "height: 250px;");
		map = L.map("map");
		marker = L.marker(coords).addTo(map);
	}
	map.setView(coords, 13);
	var OpenStreetMap_Mapnik = L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
		attribution: '&copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors,' +
			' <a href="http://creativecommons.org/licenses/by-sa/2.0/"> CC-BY-SA</a>'
	});
	OpenStreetMap_Mapnik.addTo(map);
	
	marker.setLatLng(coords);
	marker.bindPopup(text).openPopup();
}

var col;
var idg;
var clicked = false;

function showDetails(id) {
	if (clicked) {
		return;
	}
	idg = id;
	clicked = true;
	col = document.getElementById("col-" + id).innerHTML;
	document.getElementById("col-" + id).innerHTML = " Tražim...";
	var url = "detalji.php?id=" + id;
	loadXMLDoc(url);
}