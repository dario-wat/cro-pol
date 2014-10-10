<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE xsl:stylesheet [<!ENTITY nbsp "<xsl:text> </xsl:text>">]>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
  <xsl:output method="xml" indent="yes"
    doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"
    doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN" />

  <xsl:template match="/">
    <html xmlns="http://www.w3.org/1999/xhtml">
      <head>        
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" /> 
        <link rel="stylesheet" type="text/css" href="dizajn.css" />
        <title>Hrvatske političke stranke</title>
      </head>
      
      <body>
        <div id="header">
          <a href="index.html">
            <img src="http://www.rgbstock.com/largephoto/barunpatro/mf6JWUc.jpg" alt="Logo" />
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
	    </div>
        
        <div id="wrapper">
          <div id="body">
            <table>
              <tr>
                <th>Puni naziv</th>
                <th>Skraćeni naziv</th>
                <th>Politički položaj</th>
                <th>Adresa</th>
                <th>Facebook adresa</th>
              </tr>
              <xsl:for-each select="podaci/stranka">
                <tr>
                  <td><xsl:value-of select="puni_naziv" /></td>
                  <td><xsl:value-of select="skraćeni_naziv" /></td>
                  <td><xsl:value-of select="@politički_položaj" /></td>
                  <td>
                    <xsl:choose>
                      <xsl:when test="adresa">
                        <xsl:value-of select="adresa/ulica" /> &nbsp;
                        <xsl:value-of select="adresa/kućni_broj" />, &nbsp;
                        <xsl:value-of select="adresa/mjesto/@poštanski_broj" /> &nbsp;
                        <xsl:value-of select="adresa/mjesto" />
                      </xsl:when>
                      <xsl:otherwise>
                        <i>Adresa nije poznata</i>
                      </xsl:otherwise>
                    </xsl:choose>
                  </td>
                  <td>
                    <xsl:element name="a">
                      <xsl:attribute name="href">
                        <xsl:value-of select="facebook" />
                      </xsl:attribute>
                      Facebook
                    </xsl:element>
                  </td>
                </tr>
              </xsl:for-each>
            </table>

            <h3>Osobe</h3>
            <table>
              <tr>
                <th>Stranka</th>
                <th>Ime i prezime</th>
                <th>Kategorija</th>
              </tr>
              <xsl:for-each select="//osoba">
                <tr>
                  <td><xsl:value-of select="../skraćeni_naziv" /></td>
                  <td>
                    <xsl:value-of select="ime" />&nbsp;
                    <xsl:value-of select="prezime" />
                  </td>
                  <td><xsl:value-of select="@kategorija" /></td>
                </tr>                                  
              </xsl:for-each>
            </table>

            <h4>Političke stranke bez poznatog osoblja</h4>
            <ul>
              <xsl:for-each select="podaci/stranka">
                <xsl:if test="not(osoba)">
                  <li><xsl:value-of select="puni_naziv"/></li>
                </xsl:if>
              </xsl:for-each>
            </ul>
          </div>

          <div id="footer">
            Autor stranice: Dario Vidas<br/>
            Kontakt: <a href="mailto:dario.vidas@fer.hr">Webmaster</a>
          </div>
        </div>
      </div>
      </body>
    </html>
  </xsl:template>

</xsl:stylesheet>
