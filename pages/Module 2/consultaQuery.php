<?php
include_once("../../inc/MySQLConnection.php");


  $start = $_POST['start'];

$end = $_POST['end'];


//$start = "2016-11-08";
//$end = "2016-11-09";

// List of events
 $json = array();

 // Query that retrieves events
 $query = "SELECT AP_ID,RE_ALIAS,AP_START,AP_END FROM apartados INNER JOIN recursos ON AP_RESID = RE_ID WHERE AP_START>='$start' AND AP_END<='$end'" ;

 //$query = "SELECT AP_ID,AP_RESID FROM apartados ";
$events = array();

$resul = mysqli_query($connection,$query);



//$row = mysqli_fetch_assoc($resul);


while($row = mysqli_fetch_assoc($resul)){
  
    
    $row;
    $id = $row['AP_ID'];
    $title = $row['RE_ALIAS'];
    $start = $row['AP_START'];
    $events[] = array(    
      "id" => $id,
       "title" =>$title,
    "start"=> $start
    );
}


 echo json_encode($events);

?>