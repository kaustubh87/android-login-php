<?php


	
	
	function connect(){
		echo"inside connect";
		
		$con = mysql_connect("localhost", "kaustub2" ,"Shantadurga123!") or die(mysql_error());
		
		//mysql_select_db(hospital) or die(mysql_error());
		echo "Able to connect";
		return $con;
	
	}
	
	function close(){
		
		mysql_close();
		
		
	}
	
	function storeUser($name, $email, $password){
		
		$uuid = uniqid('',true);
		$hash = hashSSHA($password);
		$encrypted_password = $hash["encrypted"];
		$salt = $hash["salt"];
		
		$result = mysql_query("INSERT INTO users(unique_id, name, email, encrypted_password, salt, created_at) VALUES ('$uuid','$name','$email','$encrypted_password','$salt',NOW())");
		if($result)
		{
			$uid = mysql_insert_id();
			$result = mysql_query("SELECT * FROM users WHERE uid = $uid");
			return mysql_fetch_array($result);
		
		}
		else
		{
			return false;
			
		}
	
	}
	
	function getUserbyEmailandPassword($email, $password)
	{
		$result = mysql_query("SELECT * FROM users where email = '$email'") or die(mysql_error());
		$no_of_rows = mysql_num_rows($result);
        if ($no_of_rows > 0) {
            $result = mysql_fetch_array($result);
            $salt = $result['salt'];
            $encrypted_password = $result['encrypted_password'];
            $hash = $this->checkhashSSHA($salt, $password);
            // check for password equality
            if ($encrypted_password == $hash) {
                // user authentication details are correct
                return $result;
            }
        } else {
            // user not found
            return false;
        }

	}
	
	public function isUserExisted($email) {
        $result = mysql_query("SELECT email from users WHERE email = '$email'");
        $no_of_rows = mysql_num_rows($result);
        if ($no_of_rows > 0) {
            // user existed 
            return true;
        } else {
            // user not existed
            return false;
        }
    }
 
    /**
     * Encrypting password
     * @param password
     * returns salt and encrypted password
     */
    public function hashSSHA($password) {
 
        $salt = sha1(rand());
        $salt = substr($salt, 0, 10);
        $encrypted = base64_encode(sha1($password . $salt, true) . $salt);
        $hash = array("salt" => $salt, "encrypted" => $encrypted);
        return $hash;
    }
 
    /**
     * Decrypting password
     * @param salt, password
     * returns hash string
     */
    public function checkhashSSHA($salt, $password) {
 
        $hash = base64_encode(sha1($password . $salt, true) . $salt);
 
        return $hash;
    }
 
	
	//connect();



?>


