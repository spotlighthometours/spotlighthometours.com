<?php
    /**
     * @author William Merfalen
     * @date 2014-11-07
     * @purpose Porting "products.cfm" to PHP
     */
    require '../../repository_inc/classes/inc.global.php';
    error_reporting(-1);
    ini_set('display_errors',1);
    global $db; 
    setlocale(LC_MONETARY, 'en_US');
    $values = array(
        'productName' =>  substr(isset($_POST['productName']) ? $_POST['productName'] : "" ,0,50),
        'onePerOrder' => (isset($_POST['onePerOrder'])) ? ($_POST['onePerOrder'] == 'on' ? 1 : 0 ) : 0,
        'unitPrice' => (isset($_POST['unitPrice']) ? round(floatval($_POST['unitPrice']),2) : NULL ),
        'chargeSalesTax' => (isset($_POST['chargeSalesTax'])) ? ($_POST['chargeSalesTax'] == 'on' ? 1 : 0 ) : 0,
        'description'=> (isset($_POST['description'])) ? substr($_POST['description'],0,1000) : ''
    );
    switch(isset($_GET['action']) ? $_GET['action'] : ""){
        case "insertProduct": 
            $db->insert("products",$values);
            break;
        case 'updateProduct':
            $db->update("products",$values," productID=" . intval($_POST['productID']));
            break;
        case 'deleteProduct':
            $db->delete("products","productID=".intval($_GET['product']));
            break;
        default:
            break;
    }
    include '_' . (isset($_GET['pg']) ? preg_replace('|[^a-zA-Z]*|','',$_GET['pg']) : "listproducts") . '.php';
?>
