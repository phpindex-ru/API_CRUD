<a href="index.php">Главная</a><br><br>
<form class="form-inline" action="" method="post">
<input type="text" name="name" placeholder="Название">
<input type="text" name="description" placeholder="Описание">
<input type="number" name="price" min="1" max="100" step="0.2" placeholder="Цена">
<input type="hidden" name="method" value="put">
<button type="submit" name="submit">Отправить</button>

<?php
if(isset($_POST['submit']))	{
$name = $_POST['name'];
$description = $_POST['description'];
$price = $_POST['price'];
$method = $_POST['method'];
$url = "http://localhost/client/server.php?name=" . $name ."&description=". urlencode($description) ."&price=" . $price . "&method=" . $method;
$client = curl_init($url);
curl_setopt($client,CURLOPT_RETURNTRANSFER,true);
$response = curl_exec($client);
$result = json_decode(json_encode($response), True);
$array = json_decode($result,true); 
echo '<br><br>';
print_r($array['data']);
}
?>
</div>