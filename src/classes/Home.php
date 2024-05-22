<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
class Home
{
    public function index()
    {
        global $smarty;

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://www.turkpin.net/api.php',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'DATA' => "<APIRequest>
                <params>
                    <cmd>epinOyunListesi</cmd>
                    <username>api@turkpin.net</username>
                    <password>@.nwjExrK4U5b_S@y</password>
                </params>
            </APIRequest>")
        ));

        $response = curl_exec($curl);
        $xml = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml,  JSON_UNESCAPED_UNICODE);
        $array = json_decode($json, TRUE);
        curl_close($curl);

        $games = array();

        foreach($array['params']['oyunListesi']['oyun'] as $oyun) {
            $games[$oyun['id']] = $oyun['name'];
        }


        $smarty->assign('games', $games);

        $smarty->assign('template', 'home.html');
    }

    public function products()
    {
        global $smarty;

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://www.turkpin.net/api.php',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'DATA' => "<APIRequest>
                <params>
                    <cmd>epinUrunleri</cmd>
                    <username>api@turkpin.net</username>
                    <password>@.nwjExrK4U5b_S@y</password>
                    <oyunKodu>" . $_GET['oyun'] . "</oyunKodu>
                </params>
            </APIRequest>")
        ));

        $response = curl_exec($curl);
        $xml = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml,  JSON_UNESCAPED_UNICODE);
        $array = json_decode($json, TRUE);
        curl_close($curl);

        $urunler = array();

        if(isset($array['params']['epinUrunListesi']['urun'][0])) {
            foreach($array['params']['epinUrunListesi']['urun'] as $urun) {
                $urunler[$urun['id']] = $urun;
            }
        } else {
            $urunler[$array['params']['epinUrunListesi']['urun']['id']] = $array['params']['epinUrunListesi']['urun'];
        }

        


        $smarty->assign('urunler', $urunler);

        $smarty->display('products.html');
    }

    public function order()
    {
        global $smarty;

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://www.turkpin.net/api.php',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'DATA' => "<APIRequest>
                <params>
                    <cmd>epinSiparisYarat</cmd>
                    <username>api@turkpin.net</username>
                    <password>@.nwjExrK4U5b_S@y</password>
                    <oyunKodu>" . $_GET['oyunKodu'] ."</oyunKodu>
                    <urunKodu>" . $_GET['urunKodu'] ."</urunKodu>
                    <adet>" . $_GET['adet'] ."</adet>
                    <character>" . $_GET['character'] ."</character>
                </params>
            </APIRequest>")
        ));

        $response = curl_exec($curl);
        $xml = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml,  JSON_UNESCAPED_UNICODE);
        $array = json_decode($json, TRUE);
        curl_close($curl);

        $siparis = array();
        
        if(isset($array['params']['epin_list']['epin'][0])){
            foreach($array['params']['epin_list']['epin'] as $epin) {
                $siparis[] = array(
                    'code' => $epin['code'], 
                    'desc' => $epin['desc']
                );
            }
        } else {
            $siparis[] = array(
                'code' => $array['params']['epin_list']['epin']['code'], 
                'desc' => $array['params']['epin_list']['epin']['desc']
            );
        }
        
        


        $smarty->assign('siparis', $siparis);
        $smarty->display('order.html');
    }
}
