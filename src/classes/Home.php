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
                    <oyunKodu>1</oyunKodu>
                </params>
            </APIRequest>")
        ));

        $response = curl_exec($curl);
        $xml = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml,  JSON_UNESCAPED_UNICODE);
        $array = json_decode($json, TRUE);
        curl_close($curl);

        $urunler = array();

        
        foreach($array['params']['epinUrunListesi']['urun'] as $urun) {
            $urunler[$urun['id']] = $urun;
        }
        


        $smarty->assign('urunler', $urunler);

        $smarty->display('products.html');
    }

    public function order()
    {
        global $smarty;
        //print_r($_POST);
        /*
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
                    <oyunKodu>" . $_POST['oyunKodu'] ."</oyunKodu>
                    <urunKodu>" . $_POST['urunKodu'] ."</urunKodu>
                    <adet>" . $_POST['adet'] ."</adet>
                    <character>" . $_POST['character'] ."</character>
                </params>
            </APIRequest>")
        ));

        $response = curl_exec($curl);
        $xml = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml,  JSON_UNESCAPED_UNICODE);
        $array = json_decode($json, TRUE);
        curl_close($curl);

        $order = array();

        
        foreach($array['params']['epin_list']['epin'] as $epin) {
            $order[] = array(
                'code' => $epin['code'], 
                'desc' => $epin['desc']
            );
        }
        


        $smarty->assign('order', $order);
        */

        $input = file_get_contents('php://input');
        $data = json_decode($input, true);

        if (json_last_error() === JSON_ERROR_NONE) {
            
            echo json_encode(["status" => "success", "message" => "Veri başarıyla alındı."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Geçersiz JSON verisi."]);
        }


        $smarty->display('order.html');
    }
}
