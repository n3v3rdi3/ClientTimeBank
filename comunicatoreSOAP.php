<?php
session_start();
try {
    $server = new SoapClient('http://127.0.0.1:8080/axis2/services/TimeBankServer?wsdl', array('cache_wsdl' => WSDL_CACHE_NONE));

} catch (Exception $e) {
    echo "<h2>Exception Error!</h2>";
    echo $e->getMessage();
}


switch ($_POST['ACTION']) {

    case 1:
        $result = $server->loginUtente(array('username' => $_POST['USERNAME'], 'password' => $_POST['PASSWORD']));
        print_r($result);
        break;

    case 2:
        $result = $server->inserisciUtente(array('username' => $_POST['USERNAME'], 'password' => $_POST['PASSWORD'], 'email' => $_POST['EMAIL'], 'indirizzo' => $_POST['INDIRIZZO'], 'cap' => $_POST['CAP'], 'citta' => $_POST['COMUNE'], 'provincia' => $_POST['PROVINCIA']));
        print_r($result);
        break;

    case 3: //Cerca i comuni facendo parte di una provincia
        //$parameters=array('value'=>35);
        $result = $server->getComuniPerProvincia(array('provincia' => $_POST['PROVINCIA']));
        $comuni = json_decode($result->return, true);
        foreach ($comuni as $comune)
            echo "<option value=\"" . $comune["codice_istat"] . "\">" . $comune["nome"] . "</option>";
        break;

    case 4: //creaziono nuovo annuncio
        $result = $server->inserisciAnnuncio(array('dataAnnuncioFromClient' => $_POST['DATAORA'], 'descrizione' => $_POST['DESCRIZIONE'], 'creatore' => $_POST['CREATORE'], 'categoria' => $_POST['CATEGORIA']));
        print_r($result->return);
        break;
    case 5:
        if (isset($_SESSION['username'])) {
            echo $_SESSION['username'];
        } else {
            echo "no Utente";
        }
        break;
    case 6:
        $result = $server->richiediAnnuncio(array('id_annuncio' => $_POST['ID_ANNUNCIO'], 'creatore' => $_POST['CREATORE'], 'richiedente' => $_POST['RICHIEDENTE']));
        echo $result->return;
        break;
    case 7:
        $result = $server->cercaAnnunci(array('creatore' => $_POST['CREATORE'], 'provincia' => $_POST['PROVINCIA'], 'comune' => $_POST['COMUNE'], 'categoria' => $_POST['CATEGORIA'], 'all' => $_POST['ALL']));
        $result = json_decode($result->return, true);
        // print_r($result[0]["nomeComune"]);
        if ($result[0]["codiceErrore"] != -10) { //Se ci sono annunci
            foreach ($result as $chiave => $annuncio) {
                echo "<a href=\"mostraannuncio.php?id=" . $annuncio["id_annuncio"] . "\" class=\"bianco\"><div class=\"annuncio\"><span class=\"descrizioneAnnuncio\">" . $annuncio["descrizione"] . "</span><span
                class=\"categoriaAnnuncio\">" . $annuncio["nome_cat"] . "</span><span class=\"comuneAnnuncio\">" . $annuncio["nomeComune"] . "</span><span
                class=\"provinciaAnnuncio\">" . $annuncio["provincia"] . "</span></div></a>";
            }
        } else
            echo "<br /><div class=\"erroriCercaAnnunci\">Non ci sono annunci</div>";
        break;
    case 8:
        $result = $server->modificaCategoria(array('id_categoria' => $_POST['ID_CATEGORIA'], 'nuovoNome' => $_POST['NUOVONOME']));
        echo $result->return;
        break;
    case 9:
        $result = $server->eliminaCategoria(array('id_categoria' => $_POST['ID_CATEGORIA']));
        echo $result->return;
        break;
    case 10:
        $result = $server->eliminaCategoria(array('id_categoria' => $_POST['ID_CATEGORIA']));
        echo $result->return;
        break;
    case 11:
        $result = $server->eliminaUtente(array('username' => $_POST['username']));
        echo $result->return;
        break;


}//chiusura switch


?>