<?php

    $tipDraft       = $_POST['tip_draft'];
    $numeDraft      = empty($_POST['nume_draft'])       ? "???" : getUnescapedFromPOST('nume_draft');
    $descriere      = empty($_POST['descriere'])        ? "???" : getUnescapedFromPOST('descriere');
    $perioada       = empty($_POST['perioada'])         ? "???" : getUnescapedFromPOST('perioada');
    $reguli         = empty($_POST['reguli'])           ? "???" : getUnescapedFromPOST('reguli');
    $organizator    = empty($_POST['organizator'])      ? "???" : getUnescapedFromPOST('organizator');
    $castigator     = empty($_POST['castigator'])       ? "???" : getUnescapedFromPOST('castigator');
    $clasament      = getUnescapedFromPOST('clasament');

    $linkDiscutii   = getUnescapedFromPOST('link_discutii');
    $linkAlegeri    = getUnescapedFromPOST('link_alegeri');
    $linkOtherName  = getUnescapedFromPOST('link_other_name');
    $linkOtherURL   = isset($linkOtherName) ? getUnescapedFromPOST('link_other_url') : "";

    $nrParticipanti = empty($_POST['nr_participanti'])  ? "???" : getUnescapedFromPOST('castigator');
    $lista_participanti = getUnescapedFromPOST('lista_participanti');
    $arrayDescrieri = array();
    $arrayParticipanti = null;


    if (isset($lista_participanti) && empty($lista_participanti)) {
        $lista_participanti = "???";

    } else if (isset($lista_participanti)) {
        $arrayParticipanti = explode(",", $lista_participanti);
        for ($i = 0; $i < count($arrayParticipanti); $i++) {
            $currId = "descriere" . $i;
            if (isset($_POST[$currId])) $arrayDescrieri[] = $_POST[$currId];
        }
    }

    $color = getColor($tipDraft);
    echo <<<_START_HTML
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
    </head>
    <body>
_START_HTML;

    /* Info generale */

    echo "<pre style = \"white-space: pre-wrap; background-color: lightgrey\">";
    echo "[color=$color][size=150][u]".$numeDraft."[/u][/size][/color]";
    echo "[table]";
    echo "[row][cell=30][color=$color]Descriere[/color][/cell][cell]"                  . $descriere . "[/cell][/row]";
    echo "[row][cell=30][color=$color]Perioada[/color][/cell][cell]"                   . $perioada . "[/cell][/row]";
    echo "[row][cell=30][color=$color]Linkuri[/color][/cell][cell]".getLinkuri($linkDiscutii,$linkAlegeri, $linkOtherName, $linkOtherURL). "[/cell][/row]";
    echo "[row][cell=30][color=$color]Reguli (noutăţi/diferenţe)[/color][/cell][cell]" . $reguli . "[/cell][/row]";
    echo "[row][cell=30][color=$color]Game Master[/color][/cell][cell]"                . $organizator . "[/cell][/row]";
    echo "[row][cell=30][color=$color]Număr participanţi[/color][/cell][cell]"         . $nrParticipanti . "[/cell][/row]";
    echo "[row][cell=30][color=$color]Participanţi[/color][/cell][cell]"               . $lista_participanti . "[/cell][/row]";
    echo "[row][cell=30][color=$color]Câştigător[/color][/cell][cell]"                 . $castigator . "[/cell][/row]";
    echo "[row][cell=30][color=$color]Clasament[/color][/cell][cell]". (empty($clasament) ? "???" :"[spoiler=Arată clasament]$clasament [/spoiler]")."[/cell][/row]";
    echo "[/table]";
    echo "</pre>";

    /* Liste descrieri */


    echo "<pre style = \"white-space: pre-wrap; background-color: lightgrey\">";
    echo "[color=$color]Liste alese[/color]";
    echo "[spoiler=Alegeri][table]";

    for ($i = 0; $i < count($arrayDescrieri); $i += 3) {

        $nume1  = isset($arrayParticipanti[$i])     ? trim($arrayParticipanti[$i])     : "";
        $lista1 = isset($arrayDescrieri[$i])        ? showDescriere($arrayDescrieri[$i], $nume1) : showDescriere("", $nume1);

        $nume2  = isset($arrayParticipanti[$i + 1]) ? trim($arrayParticipanti[$i + 1]) : "";
        $lista2 = isset($arrayDescrieri[$i + 1])        ? showDescriere($arrayDescrieri[$i + 1], $nume2) : showDescriere("", $nume2);

        $nume3  = isset($arrayParticipanti[$i + 2]) ? trim($arrayParticipanti[$i + 2]) : "";
        $lista3 = isset($arrayDescrieri[$i + 2])        ? showDescriere($arrayDescrieri[$i + 2], $nume3) : showDescriere("", $nume3);

        echo "[row][cell=33][color=$color][b]" . $nume1 . PHP_EOL . "[/b][/color]" . $lista1 . "[/cell]"
                . "[cell=33][color=$color][b]" . $nume2 . PHP_EOL . "[/b][/color]" . $lista2 . "[/cell]"
                . "[cell=33][color=$color][b]" . $nume3 . PHP_EOL . "[/b][/color]" . $lista3 . "[/cell]" . "[/row]";
    }

    echo "[/table]";
    echo "[/spoiler]";
    echo "</pre>";

    echo <<<_START_HTML
    </body>
    </html>
_START_HTML;


function getUnescapedFromPOST($nume)
{
    if (get_magic_quotes_gpc())
        return stripslashes($_POST[$nume]);
    else return $_POST[$nume];
}

function getColor($tip)
{
    switch($tip) {
        case 'video': return '#19a4cc';
        case 'music': return '#ad1d7b';
        default     : return '#c41233';   // if game
    }
}

function showDescriere($desc, $playerName)
{
    $desc = trim($desc);
    if (!empty($desc)) return $desc;

    // now $desc is empty
    if ( !empty($playerName)) return "???".PHP_EOL;
    else return "";

}

function getLinkuri($linkDiscutii, $linkAlegeri, $linkOtherName, $linkOtherUrl) {

    $urls = (empty($linkDiscutii)   ? "" : "[url=$linkDiscutii]Thread discuţii[/url]".PHP_EOL).
            (empty($linkAlegeri)    ? "" : "[url=$linkAlegeri]Thread alegeri[/url]"  .PHP_EOL).
            (empty($linkOtherName)  ? "" : "[url=$linkOtherUrl]$linkOtherName [/url]" );

    return empty($urls) ? "???" : $urls;


}