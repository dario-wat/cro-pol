<?php
	
function addToQuery(&$query, $name, $param, $multi=false) {
	if (!empty($_REQUEST[$param])) {
		if ($multi) {
			$s = [];
			foreach ($_REQUEST[$param] as $val) {
				$s[] = "contains(translate(" . $name .
					",'QWERTZUIOPASDFGHJKLYXCVBNMŠĐČĆŽ','qwertzuiopasdfghjklyxcvbnmšđčćž'),'" . 
					mb_strtolower($val, 'UTF-8') . "')";
			}
			$query[] = "(" . implode(" or ", $s) . ")";
		} else {
			$query[] = "contains(translate(" . $name . 
				",'QWERTZUIOPASDFGHJKLYXCVBNMŠĐČĆŽ','qwertzuiopasdfghjklyxcvbnmšđčćž'),'" . 
				mb_strtolower($_REQUEST[$param], 'UTF-8') . "')";
		}
	}
}

function results() {
	$dom = new DOMDocument();
	$dom->load('podaci.xml');
	$xp = new DOMXPath($dom);
	
	$params = [	'puni' 			=>	['puni_naziv', 			false],
				'skraceni'		=>	['skraćeni_naziv', 		false],
				'polozaj'		=>	['@politički_položaj', 	false],
				'vrdjel'		=>	['@vrijeme_djelovanja', true],
				'ideologija'	=>	['@ideologija', 		true],
				'ulica'			=>	['adresa/ulica', 		false],
				'kucbroj'		=>	['adresa/kućni_broj', 	false],
				'mjesto'		=>	['adresa/mjesto', 		false],
				'kateg'			=>	['osoba/@kategorija', 	false],
				'oime'			=>	['osoba/ime', 			false],
				'oprezime'		=>	['osoba/prezime', 		false]
				];
	$query = [];
	foreach ($params as $p => $n) {
		addToQuery($query, $n[0], $p, $n[1]);
	}
	
	$xpQuery = implode(" and ", $query);
	if ($xpQuery != "") {
		$xpQuery = "[" . $xpQuery . "]";
	}
	$res = $xp->query("/podaci/stranka" . $xpQuery);
	return $res;
}

function genAddress($node) {
	$adr = $node->getElementsByTagName('adresa');
	if ($adr->length == 0) {
		return 'Adresa nije poznata';
	}
	$adresa = $adr->item(0);
	$mjesto = $adresa->getElementsByTagName('mjesto')->item(0);
	$str = 	$adresa->getElementsByTagName('ulica')->item(0)->nodeValue 		. ' ' .
			$adresa->getElementsByTagName('kućni_broj')->item(0)->nodeValue . ' ' .
			$mjesto->getAttribute('poštanski_broj')							. ' ' .
			$mjesto->nodeValue;
	return $str;
}

function getAllOsobe($res) {
	$osobe = [];
	foreach ($res as $n) {
		$osobe[] = $n->getElementsByTagName('osoba');
	}
	$cpy = $osobe;
	$osobe = [];
	foreach ($cpy as $os) {
		foreach ($os as $o) {
			$osobe[] = $o;
		}
	}
	return $osobe;
}

class FBJson {

	function __construct($r) {
		$json = file_get_contents('http://graph.facebook.com/' . $r->getAttribute('id'));
		$this->json_arr = json_decode($json, true);
		
		$json = file_get_contents('http://graph.facebook.com/' . $r->getAttribute('id') . '?fields=picture');
		$this->json_pic_arr = json_decode($json, true);
	}

	public function getAddress($type=3) {
		$str = $this->json_arr['location']['country'];
		if ($type >= 2) {
			$str = $this->json_arr['location']['city']   . ', ' . $str;
		}
		if ($type >= 3) {
			$str = $this->json_arr['location']['street'] . ', ' . $str;
		}
		return $str;
	}

	private function searchNominatim($str_lokacije) {
		return simplexml_load_file('http://open.mapquestapi.com/nominatim/v1/search?q='
				. rawurlencode($str_lokacije) . '&format=xml');
	}

	private function placeString($place) {
		return $place['lat'] . ', ' . $place['lon'];
	}

	public function searchLocation() {
		$types = [3, 2, 1];
		foreach ($types as $type) {
			$search_results = $this->searchNominatim($this->getAddress($type));
			if ($search_results->count() != 0) {
				$place = $search_results->place[0];
				return $this->placeString($place);
			}
		}
		return 'Nema lokacije';
	}

	public function getPictureURL() {
		return $this->json_pic_arr['picture']['data']['url'];
	}

	public function getWeb() {
		if (isset($this->json_arr['link'])) {
			return $this->json_arr['link'];
		}
		return '';
	}
};

?>