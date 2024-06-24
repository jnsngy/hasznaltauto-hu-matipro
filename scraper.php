<?php
require 'scraperwiki.php';
require 'scraperwiki/simple_html_dom.php';
$page_counter = 0;
$next_page = FALSE;

do { 
   $kovetkezo = "";
   $page_counter++;
   $pageurl = "https://www.hasznaltauto.hu/talalatilista/PCOHKVGRN3RDAEH4C57QDJCAPPXOHHOQEGOVBJMT7INC3SISJRWC7MU5VBIPLX3PTVALCI7OFGVRS33WO26GXEELIJ7HUOJPURUCCYCDJ3ARM67RZLMFYCT7IGB7UDC5UAAVUSABHI73QZP4IWFB35JKMAECGEDTPMGKRQMVI3MZQO4TIJGBT6CITGSRJWFZGLF5WPRCMLNPMKXHAD7GEQMH6IEM45GDP6SJQD67M4URU4DAFMCVV6IHA6FNQIDIWIHTRLVZIOD7VAEBD3ID7JDAPXSIPHWLLOMUJFTSGXF7KPOUFRILZ3XEFRRWQDOHADDVWBS3WDT556HMU2TLXBKXFSYSEBLPGZYJ6DT5U6BY7UVYVHSWZUHYGKK7DU537XOVE5XOG4M7ZC5OK4K7VTGSW7B5TEL5K42V5VIULS4OU7BA6ND7T4HGZBIFAZBX6VEK7BVM67G7M2WS5RTJF4TLG7TCRW5MSUHOR3R7VSV3EOUANOMCUK5SC2VUACIN76T3SNIDV2CVFVNR2IJV7S3CCWRXQL2UUPLFPBW3TGXV5BR34OOKATU5H6RJ5TBD6Z6MCEDSTO74DRHGK3CSDV6XIPWPJHQXEOCNMTEHHSR3EKLHQEOS6FOYGM2N77YFVXQUJPB3ECL7EU4NWMZIZGUS6MYS4YS4RTYVHDE7YZ4BDY6FDAX4L2YXA7WMMALNQOXHGYJKFTT7F7MLSYPQHNJFF6CYC64TTVDI6GOKJWYIWGWNQXNCQ6R6HQRGY4D4FAGAISGHQAHZH54XS232STPRKWOA5WVLR5WGMGCFRVWYQE7GJPMGTSESMIG534IKLA2YCA42KOWJ37LN3DYDRYEXS3WLSTZZOYBP22JCWQTUWB3S2SSPR47YMLYYVJZIN5IKXFOJLKGU6DSXIN5UMX7OZE22ATNC6NVTJGJX4ULVLHKBTOEP36QHEBIL6HA/page{$page_counter}";
   
   $html_content = scraperWiki::scrape($pageurl);
    
   $html = str_get_html($html_content);
   foreach ($html->find("div.talalati_lista") as $talalat) {  
      foreach ($talalat->find("h2 a") as $el) {
        $tipus = $el->innertext;
        $url = $el->href;
        $kod = substr($url, -7); 
      }
      foreach ($talalat->find("div.talalati_lista_vetelar strong") as $ar) {
        $ar = str_replace("&nbsp;", " ", $ar->innertext);
      }
      foreach ($talalat->find("p.talalati_lista_infosor") as $info) {
        $info = str_replace("&ndash;", ",", $info->innertext);
        $info = str_replace("&nbsp;", " ", $info);
        $info = str_replace("&sup3;", "3", $info);
        $info = explode(",",$info);
      }
      foreach ($talalat->find("div.felszereltseg") as $felszereltseg) {
        $felszereltseg = str_replace("&nbsp;", " ", $felszereltseg->innertext);
      }
   
      scraperwiki::save(   
        array('id'),
        array(
          'id' => $kod * 100000 + "-" + date("m") * 100 + date("d"),
          'kod' => $kod,
          'type' => $el->innertext,
          'price' => $ar,
          'info' => $info,
          'url' => $url,
          'felsz' => $felszereltseg,
          'crapedate' => date("Y/m/d"),
        )
      );
   }
   foreach ($html->find("div.oldalszamozas a[title=Következő oldal]") as $kovetkezo) {
     print $page_counter . "\n";
   }
} while ($kovetkezo != "");
?>
