<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!has_role("Admin")) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
}
?>
<?php
//we'll put this at the top so both php block have access to it
if(isset($_GET["id"])){
	$id = $_GET["id"];
}
?>
<?php
//saving
if(isset($_POST["save"])){
	//TODO add proper validation/checks
	$name = $_POST["name"];
	$state = $_POST["state"];
	$br = $_POST["current_amount"];
	$min = $_POST["account_min"];
	$max = $_POST["account_max"];
	$nst = date('Y-m-d H:i:s');//calc
	$user = get_user_id();
	$db = getDB();
	if(isset($id)){
		$stmt = $db->prepare("UPDATE F20_Eggs set name=:name, state=:state, current_amount=:br, account_min=:min, account_max=:max, next_stage_time=:nst where id=:id");
		//$stmt = $db->prepare("INSERT INTO F20_Eggs (name, state, current_amount, account_min, account_max, next_stage_time, user_id) VALUES(:name, :state, :br, :min,:max,:nst,:user)");
		$r = $stmt->execute([
			":name"=>$name,
			":state"=>$state,
			":br"=>$br,
			":min"=>$min,
			":max"=>$max,
			":nst"=>$nst,
			":id"=>$id
		]);
		if($r){
			flash("Updated successfully with id: " . $id);
		}
		else{
			$e = $stmt->errorInfo();
			flash("Error updating: " . var_export($e, true));
		}
	}
	else{
		flash("ID isn't set, we need an ID in order to update");
	}
}
?>
<?php
//fetching
$result = [];
if(isset($id)){
	$id = $_GET["id"];
	$db = getDB();
	$stmt = $db->prepare("SELECT * FROM F20_Eggs where id = :id");
	$r = $stmt->execute([":id"=>$id]);
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<form method="POST">
	<label>Account Name</label>
	<input name="name" placeholder="Name" value="<?php echo $result["name"];?>"/>
	<label>State</label>
	<select name="state" value="<?php echo $result["state"];?>">
		<option value="0" <?php echo ($result["state"] == "0"?'selected="selected"':'');?>>Open</option>
                <option value="1" <?php echo ($result["state"] == "1"?'selected="selected"':'');?>>Closed</option>
                <option value="2" <?php echo ($result["state"] == "2"?'selected="selected"':'');?>>Pending</option>
                <option value="3" <?php echo ($result["state"] == "3"?'selected="selected"':'');?>>Expired</option>
	</select>
	<label>Deposit / Withdraw Amount</label>
	<input type="number" min="1" name="current_amount" value="<?php echo $result["current_amount"];?>" />
	<label>Account Minimum</label>
	<input type="number" min="1" name="account_min" value="<?php echo $result["account_min"];?>" />
	<label>Account Maximum</label>
	<input type="number" min="1" name="account_max" value="<?php echo $result["account_max"];?>" />
	<input type="submit" name="save" value="Update"/>
</form>


<?php require(__DIR__ . "/partials/flash.php");