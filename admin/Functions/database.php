<?php
  
    function prep_DB_content ()
    {
        global $database_link;
        

        ConnectDB();

        $sql_query = file_get_contents ( "database.sql", TRUE ) or die("Could not read file");
  
        $ret = mysqli_multi_query($database_link, $sql_query) or die("Could not execute db script");

        $database_link->close();

        ConnectDB(); //connection must be closed & reopened for multi_query

        create_roles();
        create_admin(); 

        $database_link->close();
      
    }

    function ConnectDB() {
        global $database_link;
        // Create database connection
        $database_link = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        if ($database_link->connect_error)
        {
            die("Database Connection failed: " . $database_link->connect_error);
        }
    }

    function create_roles()
    {
        global $database_link;

        if(!$database_link) die("databaseConnection dead");

        $query_check_roles_exist = "SELECT id FROM roles WHERE id <= 2";
        $statement_check_roles_exist = $database_link->prepare($query_check_roles_exist);
        $statement_check_roles_exist->execute();

        $statement_check_roles_exist->store_result();
        
        if ($statement_check_roles_exist->num_rows == 0)
        {
            $query_insert_roles = "INSERT INTO roles (name) VALUES ('admin'), ('user')";
            $statement_inser_roles = $database_link->prepare($query_insert_roles);
            $statement_inser_roles->execute();
        }

        $database_link->close();
        
    }

    function create_admin()
    {
        global $database_link;

        // HACK: Storing config values in variables so that they aren't passed by reference later.
        $default_admin_username = DEFAULT_ADMIN_USERNAME;
        $default_admin_password = DEFAULT_ADMIN_PASSWORD;
        $default_admin_firstname = DEFAULT_ADMIN_FIRSTNAME;
        $default_admin_lastname = DEFAULT_ADMIN_LASTNAME;

        $admin_role_id = 1;

        ConnectDB();
        
        if(!$database_link) die("database Connection dead");

        $query_check_admin_exists = "SELECT id FROM users WHERE email = ? LIMIT 1";

        $statement_check_admin_exists = $database_link->prepare($query_check_admin_exists);

        $statement_check_admin_exists->bind_param('s', $default_admin_username);

        $statement_check_admin_exists->execute() or die("error executing query");
        $statement_check_admin_exists->store_result();

        if($statement_check_admin_exists->num_rows == 0)
        {
            $query_insert_admin = "INSERT INTO users (email, password,first_name,last_name,created_at) VALUES (?, SHA(?),?,?,NOW())";
            $statement_insert_admin = $database_link->prepare($query_insert_admin);
            $statement_insert_admin->bind_param('ssss', $default_admin_username, $default_admin_password, $default_admin_firstname,$default_admin_lastname);
            $statement_insert_admin->execute();
            $statement_insert_admin->store_result();

            $admin_user_id = $statement_insert_admin->insert_id;

            $query_add_admin_to_role = "INSERT INTO userroles(user_id, role_id) VALUES (?, ?)";
            $statement_add_admin_to_role = $database_link->prepare($query_add_admin_to_role);
            $statement_add_admin_to_role->bind_param('dd', $admin_user_id, $admin_role_id);
            $statement_add_admin_to_role->execute();
            $statement_add_admin_to_role->close();
        }
    }

    function create_tables()
    {
        $query_users = "CREATE TABLE IF NOT EXISTS users (id INT NOT NULL AUTO_INCREMENT, username VARCHAR(50), password CHAR(40), PRIMARY KEY (id))";
        $database_link->query($query_users);

        $query_roles = "CREATE TABLE IF NOT EXISTS roles (id INT NOT NULL, name VARCHAR(50), PRIMARY KEY (id))";
        $database_link->query($query_roles);

        $query_users_in_roles = "CREATE TABLE IF NOT EXISTS users_in_roles (id INT NOT NULL AUTO_INCREMENT, user_id INT NOT NULL, role_id INT NOT NULL, ";
        $query_users_in_roles .= " PRIMARY KEY (id), FOREIGN KEY (user_id) REFERENCES users(id), FOREIGN KEY (role_id) REFERENCES roles(id))";
        $database_link->query($query_users_in_roles);

        $query_pages = "CREATE TABLE IF NOT EXISTS pages (id INT NOT NULL AUTO_INCREMENT, menulabel VARCHAR(50), content TEXT, PRIMARY KEY (id))";
        $database_link->query($query_pages);
    }
 
?>