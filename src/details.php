<?php
include('functions.php');

function createInfo($name, $value) {
	return '<strong>' . $name . ':</strong><br/>' . $value . '<br/>';
}

function getValue($node, $name) {
	return $node->getElementsByTagName($name)->item(0)->nodeValue;
}

if (!empty($_REQUEST['id'])) {
	$dom = new DOMDocument();
	$dom->load('podaci.xml');
	$xp = new DOMXPath($dom);
	
	$res = $xp->query('/podaci/stranka[@id="' . $_REQUEST['id'] . '"]');
	if ($res->length == 0) {
		echo 'Nema informacija';
	} else {
		$s = $res->item(0);
		$address = $s->getElementsByTagName('adresa')->item(0);

		echo createInfo('Puni naziv', getValue($s, 'puni_naziv'));
		echo createInfo('Skraćeni naziv', getValue($s, 'skraćeni_naziv'));
		echo createInfo('Ideologija', $s->getAttribute('ideologija'));
		echo createInfo('Politički položaj', $s->getAttribute('politički_položaj'));
		
		echo '<strong>Adresa:</strong><br/>';
		if ($address != NULL) {
			echo 	getValue($address, 'ulica')
			 		. ', ' . getValue($address, 'kućni_broj')
					. ', ' . getValue($address, 'mjesto')
					. ', ' . $address->getElementsByTagName('mjesto')->item(0)->getAttribute('poštanski_broj')
					. ', ' . getValue($address, 'država')
					. '<br/>';
		} else {
			echo 'Adresa nije poznata';
		}

		$osobe = $s->getElementsByTagName('osoba');
		if ($osobe->length != 0) {
			echo '<strong>Osobe:</strong><br/>';
			foreach ($osobe as $o) {
				echo 	getValue($o, 'ime')
						. ' ' . getValue($o, 'prezime')
						. ' (' . $o->getAttribute('kategorija') . ')'
						. '<br/>';
			}
		}


		$fb = new FBJson($s);
		$lok = $fb->searchLocation();
		echo 'Location:' . $lok;
		echo 'Naziv:' . getValue($s, 'puni_naziv');
		echo 'Web:' . $fb->getWeb();
	}
}
?>