<?php

require_once 'Home.php';

class Main
{
    public $router;

    public function __construct()
    {
        global $lang, $smarty;

        $lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'tr';

        if (isset($_GET['lang'])) {
            $lang = $_GET['lang'];
            $_SESSION['lang'] = $lang;
        }

        require_once __DIR__ . "/../languages/{$lang}.php";

        $smarty = new Smarty\Smarty();
        $this->router = new \Bramus\Router\Router();

        $smarty->clearAllCache();
        $smarty->force_compile = true;
        $smarty->clearCompiledTemplate();
        $smarty->compile_check = true;
        $smarty->caching = Smarty\Smarty::CACHING_OFF;

        $smarty->setTemplateDir('src/templates');
        $smarty->setCompileDir('/tmp');
        $smarty->assign('LANG', $lang);
        $smarty->assign('langs', ['tr' => 'Türkçe', 'en' => 'English']);
        
    }

    var $compile_check   =  true; 

    public function run()
    {
        global $smarty;

        $this->router->get('/', function () use ($smarty) {
            $home = new Home();

            if(isset($_GET['route'])) {
                if($_GET['route'] == "products") {
                    $home->products();
                }
                if($_GET['route'] == "order") {
                    $home->order();
                }
            } else {
                $home->index();
                $smarty->display('index.html');
            }
            
        });

        
        $this->router->run();
        
    }
}
