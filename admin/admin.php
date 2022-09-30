<?php
include("jwt_helper.php");
$servername = "localhost";
$username = "unipgbookss";
$password = "9nhMT6nP7bYg";
$dbname = "my_unipgbookss";
header("Content-Type: application/json; charset=UTF-8");
$conn = new mysqli($servername, $username, $password, $dbname);

function login($obj,$conn)
{
    $stmt = $conn->prepare("SELECT * FROM Utente, Cuoco WHERE Utente.CF = Cuoco.CF AND username = ? AND password = ? ;");
    $stmt->bind_param("ss", $obj->username, $obj->password);
    $stmt->execute();
    $result = $stmt->get_result();
    $outp = $result->fetch_all(MYSQLI_ASSOC);
    
    if (sizeof($outp)==1 && $outp[0]["username"]){
        $id=$outp[0]["username"];
        $token = array();
        $token['id'] = $id;
        echo json_encode(array("token"=>JWT::encode($token, 'sskey')));
    }
    else{
        echo json_encode(array("token"=>"errore"));
    }

    $result -> free_result();

}
function validate($obj,$conn){
    try {
        $token = JWT::decode($obj->token, 'sskey');
        if (isset($token->id)) {
            $stmt = $conn->prepare("SELECT * FROM Utente, Cuoco WHERE username = ? AND Utente.CF = Cuoco.CF;");
            $stmt->bind_param("s", $token->id);
            $stmt->execute();
            $result = $stmt->get_result();
            $outp = $result->fetch_all(MYSQLI_ASSOC);
            $result -> free_result(); //todo testa
            if (sizeof($outp)==1 && !is_null($outp[0]["username"])){
                return $outp[0]["username"];
            }
        }
        else {
            return "errore";
        }
      }
      catch(Exception $e) {
        return "errore";
      }
    
}
function mirror($obj){
    echo json_encode($obj);
}
function oldDate($date){
    return (date("Y-m-d")>$date);
}
function deleteMenu($obj,$conn){ //data e tipo
    $validation=validate($obj,$conn);
    if ($validation=="errore" || is_null($validation)){
        echo json_encode(array("token"=>"errore","status"=>"Non hai i permessi per eseguire questa azione"));
        return;
    }
    $stmt = $conn->prepare("DELETE FROM Menu WHERE id_mensa = 1 AND data = ? AND tipo = ?;");
    $stmt->bind_param("ss", $obj->data, $obj->tipo);
    $stmt->execute();
    echo json_encode(array("token"=>"success","username"=>$validation));
}
function updateMenu($obj,$conn){
    $validation=validate($obj,$conn);
    if ($validation=="errore" || is_null($validation)){
        echo json_encode(array("token"=>"errore","status"=>"Non hai i permessi per eseguire questa azione"));
        return;
    }

    if (oldDate($obj->data)){
        echo json_encode(array("token"=>"errore","status"=>"Non puoi inserire una data passata"));
        return;
    }
    $stmt = $conn->prepare("REPLACE INTO Menu (id_mensa, data, primi, secondi, contorni, dessert, tipo) VALUES (1,?,?,?,?,?,?);");
    $stmt->bind_param("ssssss", $obj->data, $obj->primi, $obj->secondi, $obj->contorni, $obj->dessert,$obj->tipo);
    $stmt->execute();
    echo json_encode(array("token"=>"success"));
    //$result = $stmt->get_result();
    //$outp = $result->fetch_all(MYSQLI_ASSOC);
    //parametri e query
    
}
//writeMsg(); // call the function
function getMenu($obj,$conn){
    $stmt = $conn->prepare("SELECT * FROM Menu WHERE id_mensa = 1 AND data = ?;");
    $stmt->bind_param("s", $obj->data);
    $stmt->execute();
    $result = $stmt->get_result();
    $outp = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($outp);
    //parametri e query
    
}

$obj = json_decode($_POST["x"], false);
/*
if ($obj->fun=="login"){
    login($obj,$conn);
}
*/
/*
$stmt = $conn->prepare("SELECT * FROM Utente WHERE username = ? AND password = ?;");
$stmt->bind_param("ss", $obj->username, $obj->password);
$stmt->execute();
$result = $stmt->get_result();
$outp = $result->fetch_all(MYSQLI_ASSOC);
if (sizeof($outp)==1) {
    //echo json_encode(array("CF" => "nonva", "Cognome" => "nonva"));
    $arr = array (
        array("CF"=>$outp[0]["CF"],"Cognome"=>sizeof($outp)),
      );
    echo json_encode($arr);
}
else {
    $arr = array (
        array("CF"=>"nonva","Cognome"=>sizeof($outp)),
      );
    echo json_encode($arr);
}

$result -> free_result();
*/

//$obj2=json_encode($obj)

if ($obj->fun=="login"){
    login($obj,$conn);
}  
if ($obj->fun=="updateMenu"){
    updateMenu($obj,$conn);
} 
if ($obj->fun=="getMenu"){
    getMenu($obj,$conn);
} 
if ($obj->fun=="mirror"){
    mirror($obj);
} 
if ($obj->fun=="deleteMenu"){
    deleteMenu($obj,$conn);
} 
if ($obj->fun=="getUsername"){
    $validation=validate($obj,$conn);
    if ($validation=="errore" || is_null($validation)){
        echo json_encode(array("token"=>"errore","status"=>"Non hai i permessi per eseguire questa azione"));
    }
    else{
        echo json_encode(array("token"=>"success","username"=>$validation));
    }
}
//echo $_POST["x"];
$conn->close();
?>