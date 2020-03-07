<?php

$_env = 'prod';
$domain = 'droit-de-vivre.com';
if( isset( $_SERVER['APPLICATION_ENV'] )  ) {
    $_env = $_SERVER['APPLICATION_ENV'];
}

$isIE8 = preg_match('/(?i)msie [5-8]/',$_SERVER['HTTP_USER_AGENT']);

if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)) {
        header('X-UA-Compatible: IE=edge');
}

/**
 * Router
 */
$tplDir = 'app/views/';

$routes = array();
$routes['index']                    = array('tpl' => 'site/index', 'lg' => 'fr');
$routes['histoire-de-chats.html']   = array('tpl' => 'site/histoire-de-chats', 'lg' => 'fr');
$routes['photos.html']              = array('tpl' => 'site/photos', 'lg' => 'fr');
$routes['contact.html']             = array('tpl' => 'site/contact', 'lg' => 'fr');
$routes['nous-aider.html']          = array('tpl' => 'site/nous-aider', 'lg' => 'fr');

list($url, $qs) = explode("?", $_SERVER["REQUEST_URI"], 2);

$urlPath            = $url; 
$urlPathElements    = explode('/', substr($urlPath, 1));

$urlLanguage    = $urlPathElements[0];
$urlSlug        = $urlPathElements[0];

if(strlen($urlSlug) === 0) {
    $urlSlug = 'index';
}
$isHome = ($urlSlug == 'index');




/**
 * Language detection
 */
$acceptedLanguages  = array('fr' => 'fr_FR'/*, 'en' => 'en_UK'*/);
$language   = 'fr_FR';
/*if(strlen($urlLanguage) == 2) {
    $language = (array_key_exists($urlLanguage, $acceptedLanguages)) ? $acceptedLanguages[$urlLanguage] : null;
}
if($language === null) {
    $browserLang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
    $language = (array_key_exists($browserLang, $acceptedLanguages)) ? $acceptedLanguages[$browserLang] : 'fr_FR';
    header('Location: /' . substr($language, 0, 2) . '/');
}
setcookie('ENROLMENT_LG', $language, time()+3600, '/', $domain);*/


/**
 * TPL
 */
$tpl = (isset($routes[$urlSlug])) ? $routes[$urlSlug]['tpl'] : false;
if($tpl !== false) {    
    $config = array(
            'app_dir' => __DIR__ . '/',
            'base_dir' => 'app/',
            'asset_path' => 'assets/',
            'default_layout' => 'default',
            'default_view' => 'index',
            'layout_path' => 'app/views/layouts/',
            'view_path' => 'app/views/'
    );        

    require_once 'app/lib/view.php';
    $app = new view($config);
    $app->language = $language;
    $app->layout('default');
    $app->display($tpl, array(  '_env'      => $_env, 
                                'isIE8'     => $isIE8,
                                'isHome'    => $isHome, 
                                'page'      => $urlSlug,
                                'language'  => $language));
 
}
else {
    header('Location: /404.html');
    exit();
}



?>
