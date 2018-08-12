<?php
header("Content-Type:application/json");
include_once("connection.php");

switch($_GET['method']) {
 case 'update':
 if(!empty($_GET['name'])) {
    $name=$_GET['name'];
    $description = $_GET['description'];
    $price = $_GET['price'];
    $id = $_GET['id'];
    $update = updateItem($name, $description, $price, $id);
    if($update) {
        jsonResponse(200,"", $update);
        } else {
            jsonResponse(400,"Invalid Request",NULL);
        }
 }
 break;
 case 'post':
 if(!empty($_GET['name'])) {
    $name=$_GET['name'];
    $price_start = $_GET['price_start'];
    $price_end = $_GET['price_end'];
    $items = getItems($name, $price_start, $price_end);
    if(empty($items)) {
    jsonResponse(200, "Items Not Found", $items);
    } else {
    jsonResponse(200, "Item Found", $items);
    }
    } else {
    jsonResponse(400, "Invalid Request", NULL);
    }
 break;
 case 'put':
 if(!empty($_GET['name'])) {
    $name=$_GET['name'];
    $description = $_GET['description'];
    $price = $_GET['price'];
    $send = putItems($name, $description, $price);
    if($send) {
        jsonResponse(200,"", $send);
        } else {
            jsonResponse(400,"Invalid Request",NULL);
        }
 }
 break;
 case 'delete':
 if(!empty($_GET['id'])) {
    $id=$_GET['id'];
    $delete = deleteItem($id);
    if($delete) {
        jsonResponse(200,"", $delete);
        } else {
            jsonResponse(400,"Invalid Request",NULL);
        }
 }
 break;
 default:
 http_response_code(405);
}

function jsonResponse($status,$status_message,$data) {
header("HTTP/1.1 ".$status_message);
$response['status']=$status;
$response['status_message']=$status_message;
$response['data']=$data;
$json_response = json_encode($response);
echo $json_response;
}

function getItems($name, $price_start, $price_end) {
    function addWhere($where, $add, $and = true) {
        if ($where) {
          if ($and) $where .= " AND $add";
          else $where .= " OR $add";
        }
        else $where = $add;
        return $where;
      }
      if (!empty($_GET['name'])) {
        $where = "";
        if ($_GET["price_start"]) $where = addWhere($where, "`price` >= '".htmlspecialchars($price_start))."'";
        if ($_GET["price_end"]) $where = addWhere($where, "`price` <= '".htmlspecialchars($price_end))."'";
        if ($_GET['name']) $where = addWhere($where, "`name` LIKE '%".$name)."%'";
       $query = "SELECT * FROM `items`";
       if ($where) $query .= " WHERE $where ORDER BY `id` DESC";
       $db = Db::getInstance();
       $stmt = $db->prepare($query);
       $stmt->execute();
       if($stmt->rowCount()>0)
       {
              while($rows=$stmt->fetch(PDO::FETCH_ASSOC))
              {

                $data[] = $rows;
              }
       } elseif($stmt->rowCount() == 0) {
        $data = 'Not found';
       } else {

       }
      } 
    return $data;
}

function putItems($name, $description, $price) {
    $sql = "INSERT into items (`name`,`description`,`price`) VALUES(:name,:description,:price); ";
    $db = Db::getInstance();
    $query = $db->prepare($sql);
    $query->bindParam(':name', $name);
    $query->bindParam(':description', $description);
    $query->bindParam(':price', $price);
    if($query->execute())
    {
    return 'SUCCESS';
    }
    else
    {
    return 'ERROR';
    } 
}

function deleteItem($id) {
    $sql = "DELETE FROM items WHERE id=:id";
    $db = Db::getInstance();
    $query = $db->prepare($sql);
    $query->bindParam(':id', $id);
    if($query->execute())
    {
    return 'SUCCESS';
    }
    else
    {
    return 'ERROR';
    } 
}

function updateItem($name, $description, $price, $id) {
    $sql = "UPDATE items SET name=:name, description=:description, price=:price WHERE id=:id";
    $db = Db::getInstance();
    $query = $db->prepare($sql);
    $query->bindParam(':name', $name);
    $query->bindParam(':description', $description);
    $query->bindParam(':price', $price);
    $query->bindParam(':id', $id);
    if($query->execute())
    {
    return 'SUCCESS';
    }
    else
    {
    return 'ERROR';
    } 
}
?>
