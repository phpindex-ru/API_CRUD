<?php
if(isset($_GET['id']))	{
$id = $_GET['id'];
$url = "http://localhost/client/server.php?method=delete&id=" . $id;
$client = curl_init($url);
curl_setopt($client,CURLOPT_RETURNTRANSFER,true);
$response = curl_exec($client);
$result = json_decode(json_encode($response), True);
//echo '<pre>';
$array = json_decode($result,true);  
//print_r($array);

header('location: index.php');
}
?>
</div>