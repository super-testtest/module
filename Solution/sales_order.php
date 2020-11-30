<?php


use \Magento\Framework\App\Bootstrap;

include('./app/bootstrap.php');

$bootstrap = Bootstrap::create(BP, $_SERVER);
$objectManager = $bootstrap->getObjectManager();
$url = \Magento\Framework\App\ObjectManager::getInstance();
$storeManager = $url->get('\Magento\Store\Model\StoreManagerInterface');
$mediaurl= $storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
$state = $objectManager->get('\Magento\Framework\App\State');
$state->setAreaCode('adminhtml');

//$order = $objectManager->create('\Magento\Sales\Model\OrderRepository')->get(127578);

//echo "<pre>"; print_r($order->debug());die; 

$conn ='';
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "m2_migrated_bluemountain";

$conn = new mysqli($servername, $username, $password, $dbname);

$dataarr= array(
100051181,100051183,100051182,100051184,100051185,100051186,100051187,100051188,100051189,100051190,100051191,100051192,100051193,100051194,100051195,100051196,100051197,100051198,100051199,100051200,100051201,100051202,100051203,100051204,100051205,100051206,100051207,100051208,100051209,100051210,100051211,100051212,100051213,100051214,100051215,100051216,100051217,100051218,100051219,100051220,100051221,100051222,100051223,100051224,100051225,100051226,100051227,100051228,100051229,100051230,100051231,100051233,100051232,100051234,100051235,100051236,100051237,100051238,100051239,100051240,100051241,100051242,100051243,100051244,100051245,100051246,100051248,100051247,100051249,100051250,100051251,100051252,100051253,100051254,100051255,100051257,100051258,100051260,100051262,100051263,100051264,100051265,100051266,100051267,100051268,100051269,100051270,100051271,100051274,100051275,100051276,100051277,100051278,100051279,100051280,100051281,100051282,100051283,100051284,100051285,100050487,100051286,100051287,100051288,100051289,100051290,100051291,100051292,100051293,100051294,100051295,100051296,100051297,100051298,100051299,100051300,100051301,100051302,100051303,100051304,100051305,100051306,100051307,100051308,100051309,100051310,100051311,100051312,100051313,100051314,100051315,100051316,100051317,100051318,100051319,100051320,100051321,100051322,100051323,100051324,100051325,100051327,100051328,100051329,100051330,100051331,100051332,100051333,100051334,100051335,100051336,100051337,100051338,100051339,100051340,100051341,100051342,100051343,100051344,100051345,100051346

);

foreach ($dataarr as $key => $value) { 

	$sql = "SELECT customer_email,store_id FROM `sales_order` WHERE increment_id='".$value."' AND customer_is_guest=0" ;
	$result = $conn->query($sql);
	$customer_id="";


	if ($result->num_rows > 0) {
  
	    while ($row = $result->fetch_assoc()) {
	       //echo "<pre>";print_r($row['customer_email']);
	    	if($row['customer_email']!=''){

	    		$email=$row['customer_email'];
	    		$storeid=$row['store_id'];
	    		$customer_id=getcustomerId($email);
	    		if($customer_id){

	    			$sql1 = "UPDATE `sales_order` SET `customer_id`='".$customer_id."' WHERE increment_id='".$value."'" ;
					$result1 = $conn->query($sql1);
	    			
	    		}

	    	}
	      
	    }
	} 

}	

function getcustomerId($email){
	$conn ='';
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "m2_migrated_bluemountain";
	$customer_id ='';
	$conn = new mysqli($servername, $username, $password, $dbname);
	$sql = "SELECT entity_id FROM `customer_entity` WHERE email='".strtolower($email)."'";
	$result = $conn->query($sql);
//echo $sql;die;
	

	if ($result->num_rows > 0) {
  
	    while ($row = $result->fetch_assoc()) {

	    		$customer_id = $row['entity_id'];
	      
	    }
	} 

	return $customer_id;
}

$conn->close();

