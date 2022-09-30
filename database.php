 <?php
$servername = "localhost";
$username = "unipgbookss";
$password = "9nhMT6nP7bYg";
$dbname = "my_unipgbookss";
header("Content-Type: application/json; charset=UTF-8");


function writeMsg() {
  echo "Hello world!";
}

//writeMsg(); // call the function


//$obj = json_decode($_POST["x"], false);
//$obj2=json_encode($obj)


$conn = new mysqli($servername, $username, $password, $dbname);
$stmt = $conn->prepare("SELECT * FROM Utenti");
//$stmt->bind_param("s", $obj->limit);
$stmt->execute();
$result = $stmt->get_result();
$outp = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode($outp);

//echo $_POST["x"];
?> 
