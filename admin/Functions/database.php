<?php
    require_once ("/config/dbconfig.php");
    
    function prep_DB_content ()
    {
        global $databaseConnection;
        $admin_role_id = 1;

        ConnectDB();

        $sql_query = file_get_contents ( "database.sql", TRUE ) or die("Could not read file");
  
        $ret = mysqli_multi_query($databaseConnection, $sql_query) or die("Could not execute db script");

        $databaseConnection->close();

        ConnectDB(); //connection must be closed & reopened for multi_query

        create_roles();
        create_admin(); 

        $databaseConnection->close();
      
    }

    function ConnectDB() {
        global $databaseConnection;
        // Create database connection
        $databaseConnection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        if ($databaseConnection->connect_error)
        {
            die("Database Connection failed: " . $databaseConnection->connect_error);
        }
    }

    function create_tables($databaseConnection)
    {
        $query_users = "CREATE TABLE IF NOT EXISTS users (id INT NOT NULL AUTO_INCREMENT, username VARCHAR(50), password CHAR(40), PRIMARY KEY (id))";
        $databaseConnection->query($query_users);

        $query_roles = "CREATE TABLE IF NOT EXISTS roles (id INT NOT NULL, name VARCHAR(50), PRIMARY KEY (id))";
        $databaseConnection->query($query_roles);

        $query_users_in_roles = "CREATE TABLE IF NOT EXISTS users_in_roles (id INT NOT NULL AUTO_INCREMENT, user_id INT NOT NULL, role_id INT NOT NULL, ";
        $query_users_in_roles .= " PRIMARY KEY (id), FOREIGN KEY (user_id) REFERENCES users(id), FOREIGN KEY (role_id) REFERENCES roles(id))";
        $databaseConnection->query($query_users_in_roles);

        $query_pages = "CREATE TABLE IF NOT EXISTS pages (id INT NOT NULL AUTO_INCREMENT, menulabel VARCHAR(50), content TEXT, PRIMARY KEY (id))";
        $databaseConnection->query($query_pages);
    }

    function create_roles()
    {
        global $databaseConnection;

        if(!$databaseConnection) die("databaseConnection dead");

        $query_check_roles_exist = "SELECT id FROM roles WHERE id <= 2";
        $statement_check_roles_exist = $databaseConnection->prepare($query_check_roles_exist);

        $statement_check_roles_exist->execute();

        $statement_check_roles_exist->store_result();
        
        if ($statement_check_roles_exist->num_rows == 0)
        {
            $query_insert_roles = "INSERT INTO roles (name) VALUES ('admin'), ('user')";
            $statement_inser_roles = $databaseConnection->prepare($query_insert_roles);
            $statement_inser_roles->execute();
        }

        $databaseConnection->close();
        
    }

    function create_admin()
    {
        global $databaseConnection;

        // HACK: Storing config values in variables so that they aren't passed by reference later.
        $default_admin_username = DEFAULT_ADMIN_USERNAME;
        $default_admin_password = DEFAULT_ADMIN_PASSWORD;
        $default_admin_firstname = DEFAULT_ADMIN_FIRSTNAME;
        $default_admin_lastname = DEFAULT_ADMIN_LASTNAME;

        
        ConnectDB();
        
        if(!$databaseConnection) die("databaseConnection dead");

        $query_check_admin_exists = "SELECT id FROM users WHERE email = ? LIMIT 1";

        $statement_check_admin_exists = $databaseConnection->prepare($query_check_admin_exists) or die("problem preparing query");

        $statement_check_admin_exists->bind_param('s', $default_admin_username);

        $statement_check_admin_exists->execute() or die("error executing query");
        $statement_check_admin_exists->store_result();

        if($statement_check_admin_exists->num_rows == 0)
        {
            $query_insert_admin = "INSERT INTO users (email, password,first_name,last_name,created_at) VALUES (?, SHA(?),?,?,NOW())";
            $statement_insert_admin = $databaseConnection->prepare($query_insert_admin);
            $statement_insert_admin->bind_param('ss', $default_admin_username, $default_admin_password, $default_admin_firstname,$default_admin_lastname);
            $statement_insert_admin->execute();
            $statement_insert_admin->store_result();

            $admin_user_id = $statement_insert_admin->insert_id;
            $query_add_admin_to_role = "INSERT INTO userroles(user_id, role_id) VALUES (?, ?)";
            $statement_add_admin_to_role = $databaseConnection->prepare($query_add_admin_to_role);
            $statement_add_admin_to_role->bind_param('dd', $admin_user_id, $admin_role_id);
            $statement_add_admin_to_role->execute();
            $statement_add_admin_to_role->close();
        }
    }
 
?>