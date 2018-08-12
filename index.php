<a href="insert.php">Добавить</a><br><br>
<form action="" method="post">
<input type="text" name="name" placeholder="Название">
<input type="number" name="price_start" min="1" max="100" step="0.2" placeholder="От">
<input type="number" name="price_end" min="1" max="100" step="0.2" placeholder="До">
<input type="hidden" name="method" value="post">
<button type="submit" name="submit">Поиск</button>
</form>

<?php
if(isset($_POST['submit']))	{
if(empty($_POST['name'])) {
    $_POST['name'] = "";
};
$name = $_POST['name'];
$price_start = $_POST['price_start'];
$price_end = $_POST['price_end'];
$method = $_POST['method'];
$url = "http://localhost/client/server.php?name=" . $name ."&price_start=". $price_start ."&price_end=" . $price_end . "&method=" . $method;
$client = curl_init($url);
curl_setopt($client,CURLOPT_RETURNTRANSFER,true);
$response = curl_exec($client);
$result = json_decode(json_encode($response), True);
$array = json_decode($result,true);  

if($array['status_message'] === 'Invalid Request') {
print $array['status_message'];
} elseif($array['data'] === 'Not found') {
print $array['data'];
} else {
    foreach($array['data'] as $key => $value) {
        echo $value['name']; 
        echo " <a href='update.php?id=";
        echo $value['id'];
        echo "'>редактировать</a>";
        echo " <a href='delete.php?id=";
        echo $value['id'];
        echo "'>удалить</a>";
        echo "<br>";
        echo $value['description'] . "<br><br>";
        }
}
}
?>