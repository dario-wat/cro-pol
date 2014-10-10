<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd"> 
<?php include('functions.php'); ?>

<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8"> 
    <link rel="stylesheet" type="text/css" href="design.css">
    <title>Hrvatske političke stranke</title>
    <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.7.2/leaflet.css" />

    <script src="http://cdn.leafletjs.com/leaflet-0.7.2/leaflet.js"></script>
    <script>
      function promijeniBoju(redak) {
        redak.setAttribute("style", "background-color: #eeb;");
      }
      function vratiBoju(redak) {
        redak.removeAttribute("style");
      }
    </script>
    <script src="detalji.js"></script>
  </head>

  <body>
    <div id="header">
      <a href="index.html">
        <img src="http://www.rgbstock.com/largephoto/barunpatro/mf6JWUc.jpg" alt="Logo">
      </a>
      Političke stranke u Hrvatskoj
    </div>

    <div id="container">
      <div id="leftmenu">
        <div id="navigation">
          <ul>
            <li><a href="index.html">Home</a></li>
            <li><a href="obrazac.html">Pretraživanje</a></li>
            <li><a href="podaci.xml">Podaci</a></li>
            <li><a href="http://www.fer.hr/predmet/or">Stranica predmeta</a></li>
            <li><a href="http://www.fer.unizg.hr" target="_blank">FER</a></li>
            <li><a href="mailto:dario.vidas@fer.hr">Kontakt</a></li>
          </ul>
        </div>

        <div id="detail">Klikni na Detalji za dodatne informacije</div>
      </div>

      <div id="wrapper">
        <div id="body">
          <h2>Rezultati pretraživanja</h2>
          <?php 
            $res = results();
            if ($res->length == 0) {
          ?>
            Nema pronađenih rezultata.
          <?php
            } else {
          ?>
            <h3>Podaci s fejsa</h3>
            <table>
              <tr>
                <th>Puni naziv</th>
                <th>Skraćeni naziv</th>
                <th>Adresa</th>
                <th>Akcija</th>
              </tr>
          <?php
              foreach($res as $r) {
                $fb = new FBJson($r);
          ?>
                <tr onmouseover="promijeniBoju(this)" onmouseout="vratiBoju(this)">
                  <td><?= $r->getElementsByTagName('puni_naziv')->item(0)->nodeValue;?></td>
                  <td><?= $r->getElementsByTagName('skraćeni_naziv')->item(0)->nodeValue;?></td>
                  <td><?= $fb->getAddress();?></td>
                  <td id="<?= "col-" . $r->getAttribute("id");?>">
                    <a href="javascript:showDetails('<?= $r->getAttribute("id");?>')">Detalji</a>
                  </td>
                </tr>
          <?php  
              }
          ?>
            </table>
          <?php
            }
          ?>

          <div id="map"></div>
        </div>
        <div id="footer">
          Autor stranice: Dario Vidas<br>
          Kontakt: <a href="mailto:dario.vidas@fer.hr">Webmaster</a>
        </div>
      </div>
    </div>
  </body>
</html>