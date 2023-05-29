
<?php 
include 'config.php';

session_start();
$user = $_SESSION['username'];

$db = new Database();
$db->select('options','site_name',null,null,null,null);
$site_name = $db->getResult();


$url = "https://uat.esewa.com.np/epay/main";
$data =[
    'amt'=> $_POST['product_total'],
    'pid'=>  $_POST['product_id'],
    'scd'=> 'epay_payment'
];
?>
    <div style="color:white;display:flex;justify-content:center;align-items:center;background-color: #df3500;height:50px">
                <b>PAYMENT METHOD</b>
    </div>
<form action="https://uat.esewa.com.np/epay/main" method="POST">
    <input value="<?php echo $data['amt'];?>" name="tAmt" type="hidden">
    <input value="<?php echo $data['amt'];?>" name="amt" type="hidden">
    <input value="0" name="txAmt" type="hidden">
    <input value="0" name="psc" type="hidden">
    <input value="0" name="pdc" type="hidden">
    <input value="epay_payment" name="scd" type="hidden">
    <input value="<?php echo $data['scd'];?>" name="pid" type="hidden">
    <input value="http://merchant.com.np/page/esewa_payment_success?q=su" type="hidden" name="su">
    <input value="http://merchant.com.np/page/esewa_payment_failed?q=fu" type="hidden" name="fu">
    <input style="height:200px"type="image" src="https://cdn.esewa.com.np/ui/images/esewa_og.png?111">

</form>
<?php
$curl = curl_init($url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);


$response = json_decode($response); 
 //echo '<pre>';
 //print_r($response);
//exit;
// $_SESSION['TID'] = $response->payment_request->id;
$params1 = [
    'item_number' => $_POST['product_id'],
    // 'txn_id' => $response->payment_request->id,
    'payment_gross' => $_POST['product_total'],
    'payment_status' => 'credit',
];
$params2 = [
    'product_id' => $_POST['product_id'],
    'product_qty' => $_POST['product_qty'],
    'total_amount' => $_POST['product_total'],
    'product_user' => $_SESSION['user_id'],
    'order_date' => date('Y-m-d'),
];
$db = new Database();
$db->insert('payments',$params1);
$db->insert('order_products',$params2);
$db->getResult();

// header('Location: '.$response->payment_request->longurl);


?>