<?php

const dtInicialJornalSecao = "27-04-2001";
const dtFinalJornalSecao   = "26-12-2017";

//Script de redirecionamento para jornal específico. Utilizado no QRCode
$uri = substr($_SERVER["REQUEST_URI"], 1); // 20060427NO167
if (is_numeric(substr($uri, 0, 6)) && (strlen($uri) == 11 || strlen($uri) == 12)) {
    $ano = substr($uri, 0, 2);
    $mes = substr($uri, 2, 2);
    $meses = ['01' => 'Janeiro', '02' => 'Fevereiro', '03' => 'Março', '04' => 'Abril', '05' => 'Maio', '06' => 'Junho', '07' => 'Julho', '08' => 'Agosto', '09' => 'Setembro', '10' => 'Outubro', '11' => 'Novembro', '12' => 'Dezembro'];
    $mesExtenso = $meses[$mes];
    $dia = substr($uri, 4, 2);
    $tpJornal = substr($uri, 6, 2);
    $nuJornal = substr($uri, 8, 3);
    $letra = strlen($uri) == 12 ? " " . substr($uri, -1) : "";
    $tpDirDodf = array('NO' => ' INTEGRA', 'EX' => ' EDICAO EXTRA', 'SU' => ' SUPLEMENTO', 'ES' => ' EDICAO ESPECIAL');
    $anoRefDiario = ($ano > 67) && ($ano <= 99 ) ? '19' : '20';
    $dataCompleta = date($dia . '-' . $mes . '-' . $anoRefDiario . $ano);

    if ((strtotime($dataCompleta) >= strtotime(dtInicialJornalSecao)) && (strtotime($dataCompleta) <= strtotime(dtFinalJornalSecao))) {
        $textoDirDodf = 'SECAO1';
        $textoLinkDiario = '%s %s-%s-%s %s%s';
    } elseif (strtotime($dataCompleta) > strtotime(dtFinalJornalSecao)) {
        $textoDirDodf = ' INTEGRA';
        $textoLinkDiario = '%s %s-%s-%s %s';
    } else {
        $textoDirDodf = ' ';
        $textoLinkDiario = '%s %s-%s-%s';
    }

    switch ($tpJornal) {
        case "NO":
            $urlJornal  = sprintf("/index/visualizar-arquivo/?pasta=".$anoRefDiario.$ano."|%s_%s|DODF %s %s-%s-".$anoRefDiario."%s%s%s|&arquivo=DODF ".$textoLinkDiario.".pdf", $mes, $mesExtenso, $nuJornal, $dia, $mes, $ano, '', $letra, $nuJornal, $dia, $mes, trim($textoDirDodf == "SECAO1" ? $anoRefDiario.$ano : $anoRefDiario.$ano ), trim($textoDirDodf),$letra);
            break;
        case "EX":
            $urlJornal = sprintf(
                    "/index/visualizar-arquivo/?pasta=%s|%s|DODF %s %s EDICAO EXTRA%s|&arquivo=DODF %s %s %s%s.pdf",
                    ($anoRefDiario . $ano),
                    $mes . '_' . $mesExtenso,
                    $nuJornal,
                    $dia . '-' . $mes. '-' . $anoRefDiario . $ano,
                    $letra,
                    $nuJornal,
                    $dia . '-' . $mes. '-' . $anoRefDiario . $ano,
                    trim($tpDirDodf[$tpJornal]),
                    $letra
            );
            break;
        case "SU":
            $urlJornal = sprintf(
                    "/index/visualizar-arquivo/?pasta=%s|%s|DODF %s %s SUPLEMENTO%s|&arquivo=DODF %s %s %s%s.pdf",
                    ($anoRefDiario . $ano),
                    $mes . '_' . $mesExtenso,
                    $nuJornal,
                    $dia . '-' . $mes. '-' . $anoRefDiario . $ano,
                    $letra,
                    $nuJornal,
                    $dia . '-' . $mes. '-' . $anoRefDiario . $ano,
                    trim($tpDirDodf[$tpJornal]),
                    $letra
            );
            break;
    }
    header("Location: $urlJornal");
}

ob_start('ob_gzhandler');
header('Access-Control-Allow-Origin: *');

setlocale(LC_ALL, "pt_BR.utf-8", "pt_BR", "pt_BR.iso-8859-1", "portuguese");
date_default_timezone_set('America/Sao_Paulo');

// Define path to application directory
defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
        APPLICATION_ENV,
        APPLICATION_PATH . '/configs/application.ini'
);

if (APPLICATION_ENV == 'development') {
    ini_set('error_reporting', E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
}

$application->bootstrap()
        ->run();

/** @todo Ativar na produção */
if (APPLICATION_ENV == 'production') {
    $html = ob_get_clean();
    echo preg_replace('/\s+/', ' ', $html);
}