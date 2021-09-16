<?php 
namespace bot;

class sql
{
	private $db;
	function __construct($db)
	{
		$this->db = $db;
	}


	public function AddPhoto($prId,$photo)
	{
		$sql = "SELECT * FROM `notUpdatedProducts` WHERE `pr_id` = '$prId'";
		$prSQL = mysqli_query($this->db,$sql);
		$productPh = mysqli_fetch_assoc($prSQL);
		if(!empty($productPh))
		{
			$sql = "UPDATE `notUpdatedProducts` SET `photo_url` = '$photo' WHERE `pr_id` = '$prId'";
		}else
		{
			$sql = "INSERT INTO `notUpdatedProducts` (`id`, `pr_id`, `photo_url`) VALUES (NULL, '$prId', '$photo')";
		}
		//$this->debSql($sql);
		mysqli_query($this->db,$sql);
	}

	public function groupHideShow($act,$grId)
	{	
		$hideShow = 1;
		if($act == 'show')
		{
			$hideShow = 0;
		}
		$sql = "SELECT * FROM `notUpdatedGroups` WHERE `group_id` = '$grId'";
		$groupSQL = mysqli_query($this->db,$sql);
		$group = mysqli_fetch_assoc($groupSQL);
		if(!empty($group))
		{
			$sql = "UPDATE `notUpdatedGroups` SET `hide` = '$hideShow' WHERE `group_id` = '$grId'";
		}else
		{
			$sql = "INSERT INTO `notUpdatedGroups` (`id`, `group_id`, `act`, `hide`) VALUES (NULL, '$grId', 'showHide', '1')";
		}
		//$this->debSql($sql);
		mysqli_query($this->db,$sql);
	}

	public function contrSelectAdr($query)
	{
		$sql = "SELECT `name` FROM `adress` WHERE `name` = '$query' and `act` = 'street'";
		$streetSQL = mysqli_query($this->db,$sql);
		$street = mysqli_fetch_assoc($streetSQL);
		if(!empty($street))
		{
			return true;
		}else
		{
			return false;
		}
	}

	public function selectAdress ($query)
	{	
		$sql = "SELECT `name` FROM `adress` WHERE `name` LIKE '%$query%' and `act` = 'street'";
		$streetSQL = mysqli_query($this->db,$sql);
		$street = [];
		while($street[] = mysqli_fetch_assoc($streetSQL)){}
		array_pop($street);
		if(!empty($street))
		{
			return $street;
		}else
		{
			$sql = "SELECT `name` FROM `adress` WHERE ";
			$queryArr = explode(' ', $query);
			$arrayLength = count($query);
			$counter = 0;
			foreach($queryArr as $q)
			{
			    if($counter == $arrayLength){
					$sql .= "`name` LIKE '%$q%' ";
			    }else
			    {
			    	$sql .= "`name` LIKE '%$q%' or ";
			    }
				$counter++;
			}
			$sql .=  "and `act` = 'street'";
			$streetsSQL = mysqli_query($this->db,$sql);
			$streets = [];
			while($streets[] = mysqli_fetch_assoc($streetsSQL)){}
			array_pop($streets);
			return $streets;
		}
	}

	public function orderUpdateProduct($modifers,$pr_id,$usId)
	{
		$order = $this->getBuyersData($usId,'us_order');
		$order = json_decode($order['us_order'],true);
		foreach($order['order']['items'] as $i=>$item)
		{
			if($pr_id == $item['id'])
			{
				$order['order']['items'][$i]['modifiers'] = $modifers;
			}
		}
		$order = json_encode($order,0,512);
		$this->updateBuyers('choose_us_order',$order,$usId);
	}
	public function getOrderPr($pr_id,$usId)
	{
		$order = $this->getBuyersData($usId,'us_order')['us_order'];
		$order = json_decode($order,true);
		foreach($order['order']['items'] as $i=>$item)
		{
			if($pr_id == $item['id'])
			{
				return $item;
			}
		}
	}




	public function ClearCart($usId)
	{
		$sql = "DELETE FROM `cart` WHERE `user_tel_id` = '$usId'";
		mysqli_query($this->db,$sql);
	}
	public function updateBuyers($action,$data,$usId)
	{
		$sql ='';
		$act = str_replace('choose_', '', $action);
		$sql = "UPDATE `buers` SET `$act` = '$data' WHERE `telegram_id` = '$usId'";
		mysqli_query($this->db,$sql);
	}
	public function nextStepForm($step,$usId)
	{
			$sql = "UPDATE `buers` SET `last_move` = '$step' WHERE `telegram_id` = '$usId'";
			mysqli_query($this->db,$sql);
	}
	public function stopForm($usId)
	{
			$sql = "UPDATE `buers` SET `last_move` = '' WHERE `telegram_id` = '$usId'";
			mysqli_query($this->db,$sql);
	}
	public function getBuyersData($usId,$data)
	{
			$sql = "SELECT `$data` FROM `buers` WHERE `telegram_id` = '$usId'";
			//$this->debSql($sql);
			$us = mysqli_query($this->db,$sql);
			$name = mysqli_fetch_assoc($us);
			return $name;

	}

	public function getLastMove($usId)
	{
		$sql = "SELECT last_move FROM `buers` WHERE `telegram_id` = '$usId'";
		$us = mysqli_query($this->db,$sql);
		$LM = mysqli_fetch_assoc($us);
		return $LM;
	}
	public function startForm($usId,$name = '')
	{
		$sql = "SELECT * FROM `buers` WHERE `telegram_id` = '$usId'";
		$us = mysqli_query($this->db,$sql);
		$user = mysqli_fetch_assoc($us);
		if(empty($user))
		{
			$sql = "INSERT INTO `buers`(`id`, `name`,`telegram_id`, `last_move`) VALUES (NULL,'$name','$usId','choose_selfservice')";
			mysqli_query($this->db,$sql);
		}else
		{	
			$sql = "UPDATE `buers` SET `last_move` = 'choose_selfservice' WHERE `telegram_id` = '$usId'";
			mysqli_query($this->db,$sql);
		}
	}

	public function cartTotalPrice($usId)
	{
		$sql = "SELECT * FROM `cart` WHERE `user_tel_id` = '$usId'";
		$pr = mysqli_query($this->db,$sql);
		$products = [];
		while($products[] = mysqli_fetch_assoc($pr)){}
		array_pop($products);
		$price = 0;
		foreach($products as $product)
		{	
			$price += $product['amount'] * $product['price'];
			$product['pr_modifiers'] = json_decode($product['pr_modifiers'],true);
			if(!empty($product['pr_modifiers']))
			{
				foreach ($product['pr_modifiers'] as $i => $pr_modifier) 
				{
					$modifier = $this->getProductWithId($pr_modifier['id']);
					$price += $pr_modifier['amount'] * $modifier['price'];
				}
			}
			//$this->debSql($product);
		}
		return $price;
	}
	public function cartTotalPriceOnePr($usId,$prId)
	{
		$sql = "SELECT * FROM `cart` WHERE `user_tel_id` = '$usId' AND `product_id` = '$prId'";
		$pr = mysqli_query($this->db,$sql);
		$products = [];
		while($products[] = mysqli_fetch_assoc($pr)){}
		array_pop($products);
		$price = 0;
		foreach($products as $product)
		{	
			$price += $product['amount'] * $product['price'];
			$product['pr_modifiers'] = json_decode($product['pr_modifiers'],true);
			if(!empty($product['pr_modifiers']))
			{
				foreach ($product['pr_modifiers'] as $i => $pr_modifier) 
				{
					$modifier = $this->getProductWithId($pr_modifier['id']);
					$price += $pr_modifier['amount'] * $modifier['price'];
				}
			}
			//$this->debSql($product);
		}
		return $price;
	}
	public function cartCountPr($usId)
	{
		$sql = "SELECT COUNT(*) as `total` FROM `cart` WHERE `user_tel_id` = '$usId'";
		$pr = mysqli_query($this->db,$sql);
		$count = mysqli_fetch_assoc($pr);
		return $count;
	}

	public function cartUpdateProduct($mods,$prId,$usId)
	{
		$mods = json_encode($mods);
		$sql = "UPDATE `cart` SET `pr_modifiers` = '$mods' WHERE `product_id` = '$prId' AND `user_tel_id` = '$usId'";
		$pr = mysqli_query($this->db,$sql);
	}
	public function cartDeleteProduct($prId,$usId)
	{
		
		$sql = "DELETE FROM `cart` WHERE `product_id` = '$prId' AND `user_tel_id` = '$usId'";
		$pr = mysqli_query($this->db,$sql);
	}
	public function cartRemoveProduct($prId,$usId)
	{
		
		$sql = "UPDATE `cart` SET `amount`=(`amount`-1) WHERE `product_id` = '$prId' AND `user_tel_id` = '$usId'";
		$pr = mysqli_query($this->db,$sql);
	}
	public function cartAddProduct($prId,$usId)
	{
		
		$sql = "UPDATE `cart` SET `amount`=(`amount`+1) WHERE `product_id` = '$prId' AND `user_tel_id` = '$usId'";
		$pr = mysqli_query($this->db,$sql);
	}

	public function getCartPr($prId,$usId)
	{

		$sql = "SELECT * FROM `cart` WHERE `product_id` = '$prId' AND `user_tel_id` = '$usId'";
		$pr = mysqli_query($this->db,$sql);
		$product = mysqli_fetch_assoc($pr);
		return $product;
	}
	public function getAllCartPr($usId)
	{

		$sql = "SELECT * FROM `cart` WHERE `user_tel_id` = '$usId'";
		$prod = mysqli_query($this->db,$sql);
		$products = [];
		while($products[] = mysqli_fetch_assoc($prod)){}
		array_pop($products);
		return $products;
	}
	public function addToCart($prid,$usId,$groupMod = [])
	{
		$product = $this->getProductWithId($prid);
		$product_id = $product['product_id'];
		$code = $product['code'];
		$name = $product['name'];
		$price = $product['price'];
		$user_tel_id = $usId;
		$amount = 1;
		$modifers = [];
		if(!empty($groupMod))
		{
			$modif = $this->getProductWithId($groupMod['id']);
			$modifers[] = 
			[
				'id' => $groupMod['id'],
				'name' => $modif['name'],
				'amount' => 1,
				'groupId' => $groupMod['groupId'],
			];
		}
			//$this->debSql($modifers);
		$product['modifiers'] = json_decode($product['modifiers'],true);
		if(!empty($product['modifiers']))
		{
			foreach($product['modifiers'] as $modifier)
			{

				$modif = $this->getProductWithId($modifier['modifierId']);
				$mod = [
			        "id" => $modifier['modifierId'],
			        "name" => $modif['name'],
			        "amount" => $modifier['defaultAmount'],
			    ];
				array_push($modifers, $mod);
			}

		}
		if(!empty($modifers))
		{
			$modifers = json_encode($modifers);
			$sql = "INSERT INTO `cart` (`id`, `product_id`, `amount`, `name`, `pr_modifiers`, `price`, `user_tel_id`,`code`) VALUES (NULL, '$product_id', '$amount', '$name', '$modifers', '$price', '$user_tel_id','$code');";
		}else
		{
			$sql = "INSERT INTO `cart` (`id`, `product_id`, `amount`, `name`, `pr_modifiers`, `price`, `user_tel_id`,`code`) VALUES (NULL, '$product_id', '$amount', '$name', NULL, '$price', '$user_tel_id','$code');";
		}
		$us = mysqli_query($this->db,$sql);
	}
	public function getProductWithId($id)
	{
		$sql = "SELECT `products`.*, `nup`.`photo_url` FROM `products`
				LEFT JOIN `notUpdatedProducts` AS `nup` ON `products`.`product_id` = `nup`.`pr_id`
				WHERE `product_id` = '$id'";
		$pr = mysqli_query($this->db,$sql);
		$product = mysqli_fetch_assoc($pr);
		return $product;
	}
	public function getProductWithLineId($id)
	{
		$sql = "SELECT `products`.*, `nup`.`photo_url` FROM `products`
				LEFT JOIN `notUpdatedProducts` AS `nup` ON `products`.`product_id` = `nup`.`pr_id`
				WHERE `products`.`id` = '$id'";
		$pr = mysqli_query($this->db,$sql);
		$product = mysqli_fetch_assoc($pr);
		return $product;
	}
	public function getProductWithCode($code)
	{
		$sql = "SELECT `products`.*, `nup`.`photo_url` FROM `products`
				LEFT JOIN `notUpdatedProducts` AS `nup` ON `products`.`product_id` = `nup`.`pr_id`
				WHERE `products`.`code` = '$code'";
		$pr = mysqli_query($this->db,$sql);
		$product = mysqli_fetch_assoc($pr);
		return $product;
	}
	public function getProductsForCatId($catId)
	{
		$sql = "SELECT * FROM `products` WHERE `productCategoryId` = '$catId'";
		//$this->debSql($sql);
		$prod = mysqli_query($this->db,$sql);
		$products = [];
		while($products[] = mysqli_fetch_assoc($prod)){}
		array_pop($products);
		return $products;
	}
	public function getProductsForGrId($grId)
	{
		$sql = "SELECT `products`.*, `nup`.`photo_url` FROM `products`
				LEFT JOIN `notUpdatedProducts` AS `nup` ON `products`.`product_id` = `nup`.`pr_id`
				WHERE `products`.`groupId` = '$grId'";
		//$this->debSql($sql);
		$prod = mysqli_query($this->db,$sql);
		$products = [];
		while($products[] = mysqli_fetch_assoc($prod)){}
		array_pop($products);
		return $products;
	}
	public function getCategories()
	{
		$sql = "SELECT * FROM `categories` WHERE `is_off` = 0";		
		$cat = mysqli_query($this->db,$sql);
		$categories = [];
		while($categories[] = mysqli_fetch_assoc($cat)){}
		array_pop($categories);
		return $categories;

	}
	public function getGropupsbyAdm()
	{
		$sql = "SELECT * FROM `groups` LEFT JOIN `notUpdatedGroups` as `nug` on `groups`.`gr_id` = `nug`.`group_id` and `nug`.`act` = 'showHide' ORDER BY `groups`.`id` ASC";		
		$cat = mysqli_query($this->db,$sql);
		$groups = [];
		while($groups[] = mysqli_fetch_assoc($cat)){}
		array_pop($groups);
		return $groups;

	}
	public function getGropups()
	{
		$sql = "SELECT * FROM `groups` LEFT JOIN `notUpdatedGroups` as `nug` on `groups`.`gr_id` = `nug`.`group_id` and `nug`.`act` = 'showHide' WHERE `nug`.`hide` = '0' ORDER BY `groups`.`id` ASC";		
		$cat = mysqli_query($this->db,$sql);
		$groups = [];
		while($groups[] = mysqli_fetch_assoc($cat)){}
		array_pop($groups);
		return $groups;

	}

	public function clearCacheProducts()
	{
		$sql = "TRUNCATE TABLE products";
		$us = mysqli_query($this->db,$sql);
		$sql = "TRUNCATE TABLE categories";
		$us = mysqli_query($this->db,$sql);
		$sql = "TRUNCATE TABLE groups";
		$us = mysqli_query($this->db,$sql);
	}
	public function clearCacheAdress()
	{
		$sql = "TRUNCATE TABLE adress";
		$us = mysqli_query($this->db,$sql);
	}
	public function getGroupName($id)
	{
		$sql = "SELECT `name` FROM `groups` WHERE `gr_id` = '$id'";
		$us = mysqli_query($this->db,$sql);
		$gr_name = mysqli_fetch_assoc($us);
		return $gr_name;
	}
	public function setNewCity($city)
	{
		$name = $city['name'];
		$id = $city['id'];
		$sql = "INSERT INTO `adress` (`id`, `name`, `act`,`parent_id`,`this_id`) VALUES (NULL, '$name','city',' ','$id');";
		$us = mysqli_query($this->db,$sql);
	}
	public function setNewStreet($street)
	{
		$name = $street['name'];
		$id = $street['id'];
		$parentId = $street['cityId'];
		$sql = "INSERT INTO `adress` (`id`, `name`, `act`,`parent_id`,`this_id`) VALUES (NULL, '$name','street','$parentId','$id');";
		$this->debSql($sql);
		$us = mysqli_query($this->db,$sql);
	}
	public function setNewGroup($name,$id)
	{
		$sql = "INSERT INTO `groups` (`id`, `name`, `gr_id`) VALUES (NULL, '$name', '$id');";
		$us = mysqli_query($this->db,$sql);

		$sql = "SELECT * FROM `notUpdatedGroups` WHERE `group_id` = '$id'";
		$groupSQL = mysqli_query($this->db,$sql);
		$group = mysqli_fetch_assoc($groupSQL);
		if(empty($group))
		{
			$sql = "INSERT INTO `notUpdatedGroups` (`id`, `group_id`, `act`, `hide`) VALUES (NULL, '$id', 'showHide', '0')";
			mysqli_query($this->db,$sql);
		}
	}
	public function setNewCategoty($name,$id)
	{
		$sql = "INSERT INTO `categories` (`id`, `name`, `cat_id`, `is_off`) VALUES (NULL, '$name', '$id', '0');";
		$us = mysqli_query($this->db,$sql);
	}
	public function setNewProduct($code,$description,$id,$name,$modifiers,$groupModifiers,$price,$productCategoryId,$type,$groupId)
	{
		$modifiers = json_encode($modifiers);
		$groupModifiers = json_encode($groupModifiers);
		$sql = "INSERT INTO `products` (`id`, `product_id`, `code`, `description`, `name`, `price`, `productCategoryId`, `type`, `modifiers`, `groupModifers`,`groupId`) VALUES (`id`, '$id', '$code', '$description', '$name', '$price', '$productCategoryId', '$type', '$modifiers', '$groupModifiers','$groupId');";
		$us = mysqli_query($this->db,$sql);
	}

	public function getOrgId()
	{
		$sql = "SELECT `org_id` FROM `user` WHERE `id` = 0";
		$us = mysqli_query($this->db,$sql);
		$getOrgId = mysqli_fetch_assoc($us);
		return $getOrgId;
	}
	public function setOrId($id)
	{
		$sql = "INSERT INTO `user` (`id`, `org_id`) VALUES ('1', '$id');";
		$us = mysqli_query($this->db,$sql);
	}
	public function getOrId()
	{
		$sql = "SELECT * FROM `user`";
		$us = mysqli_query($this->db,$sql);
		$OrId = mysqli_fetch_assoc($us);
		return $OrId;
	}

	public function debSql ($data)
	{
		ob_start();
		print_r($data);
		$out = ob_get_clean(); 
		file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/sqlDEB.txt', $out, FILE_APPEND); 
	}
}