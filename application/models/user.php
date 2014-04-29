<?php
class user extends model {

	function create( $email, $password, $first_name, $last_name){ 
        
        $sql = "INSERT INTO users (email, password,first_name,last_name,created_at) VALUES (?, SHA(?),?,?,NOW())";
            $statement_insert_admin = $databaseConnection->prepare($sql);
            $statement_insert_admin->bind_param('ss', $email, $password, $first_name,$last_name);
            $statement_insert_admin->execute();
            $statement_insert_admin->store_result();

            $newuser_id = $statement_insert_admin->insert_id;

            $sql = "INSERT INTO userroles(user_id, role_id) VALUES (?, ?)";
            $statement_add_user_to_role = $databaseConnection->prepare($sql);
            $statement_add_user_to_role->bind_param('dd', $newuser_id, 2);
            $statement_add_user_to_role->execute();
            $statement_add_user_to_role->close(); 

		return $newuser_id;
	}

	function update( $user_id, $email, $password, $first_name, $last_name){ 
		$sql = "UPDATE users SET email = '$email', password = '$password', first_name = '$first_name', last_name = '$last_name' WHERE id = '$user_id' LIMIT 1";

		return mysql_query_excute($sql);
	}

	function find_by_id( $id ){
		$sql = "SELECT * FROM users WHERE id = '$id' LIMIT 1";

		$result = mysql_query_excute($sql);

		return mysql_fetch_assoc($result);
	}

	function find_by_email( $email ){
		$sql = "SELECT * FROM users WHERE email = '$email' LIMIT 1";

		$result = mysql_query_excute($sql);
 
		return mysql_fetch_assoc($result);
	}

	function find_by_email_and_password( $email, $password ){
		$sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password' LIMIT 1";

		$result = mysql_query_excute($sql);

		return mysql_fetch_assoc($result);
	}
}
