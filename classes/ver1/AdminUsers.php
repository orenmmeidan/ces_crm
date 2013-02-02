<?
class AdminUsers extends Query
{
    public $first_name;
    
    public function __construct($debug = false)
    {
        Query::__construct($debug);
        
    }
    
    public function isLoggedIn()
    {
        // if session remote value doesn't equal the remote address, don't allow access.
        if (isset($_SESSION['user_id']) && isset($_SESSION['remote']) && ($_SESSION['remote'] == $_SERVER['REMOTE_ADDR'])) {
            return true;
        }
        return false;
    }
    public function Logout()
    {
        $_SESSION['user_id']   = "";
        $_SESSION['remote']    = "";
        $_SESSION['user_type'] = "";
        session_unset();
        $uri = SITE_URL . "crm/";
        GenFunctions::real_redirect($uri);
    }
    public function Login($user = '', $pass = '')
    {
        if ($user == '') { //message - no user
            return "Login details incorrect";
        }
        if ($pass == '') { //message - no password
            return "Login details incorrect";
        }
        
        // I want to make sure that there are no sneaks trying to pass SQL queries!
        $user     = str_replace(' ', '', $user);
        // password is md5() encoded before comparison - no need to check value (might want spaces in password)
        $password = md5($pass);
        // clear session variables
        session_unset();
        
        // check if username is in the system
        $user = trim($user);
        if ($this->isSystemUser($user)) {
            // get user info from the database
            $admin_user_details = $this->getRow(PREFIX . "admin_users", "`user_name`='$user'", "", 1, false);
            // get the system lockout number
            $sys_lock           = $this->find_value(PREFIX . "security_settings", "value", "name", "lockcount", $debug);
            
            // check if user account is active
            
            // check if connection is allowed
            
            // check the password
            if ($password == $admin_user_details['password']) {
                $_SESSION['user_id']   = $admin_user_details['id'];
                $_SESSION['remote']    = $_SERVER['REMOTE_ADDR'];
                $_SESSION['user_type'] = $admin_user_details['administrator'];
                return TRUE;
            } else {
                //message - password does not match
                return "Login details incorrect";
                
            }
        } else {
            //message -not a system user
            return "Login details incorrect";
        }
        
        
        
    }
    public function isSystemUser($user)
    {
        // don't even look up if not in valid format
        if (!$this->isValidUsername($user))
            return FALSE;
        $num_of_users = $this->getCount(PREFIX . "admin_users", "`user_name`='$user'", "", 1, false);
        if ($num_of_users == 1)
            return TRUE;
        return FALSE;
    }
    //-----------------------------------------------------------------------------------------------------------
    
    /*
    isValidUsername: Check to see if the username is only alpha-numeric characters and is of the proper length according to the system settings.
    return: array('result','max','min')
    Result Values:
    0 if good
    -1 if too short
    -2 if too long
    -3 if invalid characters
    param: user [string] The username to check.
    */
    private function isValidUsername($user)
    {
        $max_username_length = $this->find_value(PREFIX . "security_settings", "value", "name", "max_username_length", $debug);
        $min_username_length = $this->find_value(PREFIX . "security_settings", "value", "name", "mmin_username_length", $debug);
        $regex               = '/^[a-zA-Z0-9]+$/';
        $test                = strlen($user);
        if ($test > $max_username_length)
            return array(
                'result' => -2,
                'max' => $max_username_length,
                'min' => $min_username_length
            );
        if ($test < $min_username_length)
            return array(
                'result' => -1,
                'max' => $max_username_length,
                'min' => $min_username_length
            );
        if (!preg_match($regex, $user))
            return array(
                'result' => -3,
                'max' => $max_username_length,
                'min' => $min_username_length
            );
        return array(
            'result' => 1,
            'max' => $max_username_length,
            'min' => $min_username_length
        );
    }
 	//-----------------------------------------------------------------------------------------------------------
    public function GetUserDetail($what_info)
    {
        $admin_user_details = $this->getRow(PREFIX . "admin_users", "`id`='" . $_SESSION['user_id'] . "'", "", 1, false);
        return $admin_user_details[$what_info];
    }
	//-----------------------------------------------------------------------------------------------------------
    public function GetUserFullDetails($content_arr)
    {
        $fields = array( );
        foreach ( $content_arr as $key => $value ) {
            $fields[ $key ] = $value;
        }
        $UserID = $fields[ "id" ];
        $AdminUserDetails = $this->getRow(PREFIX . "admin_users", "`id`='" . $UserID . "'", "", 1, false);
		$UserForm = $this->CreateEditUserForm("", $AdminUserDetails);
        return $UserForm;
    }
    //-----------------------------------------------------------------------------------------------------------
    public function DisplayUsers()
    {
        // don't even look up if not in valid format
        $rows             = $this->getRows(PREFIX . "admin_users", $filter, "", "", null, false);
        $table_titles_arr = array(
            "First Name",
            "Last Name",
            "Username",
            "Email",
            "Position"
        );
        $buffer           = "<div id='table' class='users'>
        <input type='submit' id='CreateNewUserFormButton' value='Create New user' />
		<table class='cruises scrollable' >
		<thead>
		<tr>
		";
        for ($i = 0; $i < sizeof($table_titles_arr); $i++) {
            $buffer .= "<th>" . $table_titles_arr[$i] . "</th>";
        }
        $buffer .= "
		</tr>
		</thead>
		<tbody>
		
		";
        for ($j = 0; $j < sizeof($rows); $j++) {
            $position = ($rows[$j]['administrator'] == 1) ? "Admin" : "Sales";
            $buffer .= "<tr >";
            $buffer .= "<td>" . $rows[$j]['fname'] . "</td>";
            $buffer .= "<td>" . $rows[$j]['lname'] . "</td>";
            $buffer .= "<td>" . $rows[$j]['user_name'] . "</td>";
            $buffer .= "<td>" . $rows[$j]['email'] . "</td>";
            $buffer .= "<td>" . $position . "</td>";
            
            
            $buffer .= "<td><input type='submit' class='edit_user' id='" . $rows[$j]['id'] . "' value='Edit' / ></td>";
            
            $buffer .= "
		</tr>";
        }
        $buffer .= "</tbody></table>
		</div>";
        return $buffer;
    }
    //-----------------------------------------------------------------------------------------------------------
    public function CreateUserForm($message, $user_arr)
    {
        $buffer = "
			<div class='form_div'>
			<h2><em>New User</em></h2>
			" . $message . "
			<form action='' method='post'>
			<label>First Name</label><input type='text' name='fname' value='" . $user_arr['fname'] . "' /><br />
			<label>Last Name</label><input type='text' name='lname' value='" . $user_arr['lname'] . "' /><br />
			<label>Username</label><input type='text' name='user_name' value='" . $user_arr['user_name'] . "' /><br />
			<label>Email</label><input type='text' name='email' value='" . $user_arr['email'] . "' /><br />
			<label>Password</label><input type='password' name='password' value='' /><br />
			<label>Position</label><select name='administrator'>
			<option value='0'";
        $buffer .= ($user_arr['administrator'] == 0) ? " selected " : "";
        $buffer .= ">Sales</option>
			<option value='1'";
        $buffer .= ($user_arr['administrator'] == 1) ? " selected " : "";
        $buffer .= ">Admin</option>
			</select><br />
			<label>&nbsp;</label><input type='submit' name='submit' value='Submit' />
			</form>
			</div>
			";
        
        return $buffer;
    }
    //-----------------------------------------------------------------------------------------------------------
    public function CreateEditUserForm($message, $user_arr)
    {
        $buffer = "
			<div class='form_div'>
			<h2><em>Edit User</em></h2>
			" . $message . "
			<form action='' method='post'>
			<input type='hidden' name='id' value='" . $user_arr['id'] . "' />
			<label>First Name</label><input type='text' name='fname' value='" . $user_arr['fname'] . "' /><br />
			<label>Last Name</label><input type='text' name='lname' value='" . $user_arr['lname'] . "' /><br />
			<label>Username</label><input type='text' name='user_name' value='" . $user_arr['user_name'] . "' /><br />
			<label>Email</label><input type='text' name='email' value='" . $user_arr['email'] . "' /><br />
			<label>Password</label><input type='password' name='password' value='' /><br />
			<label>Position</label><select name='administrator'>
			<option value='0'";
        $buffer .= ($user_arr['administrator'] == 0) ? " selected " : "";
        $buffer .= ">Sales</option>
			<option value='1'";
        $buffer .= ($user_arr['administrator'] == 1) ? " selected " : "";
        $buffer .= ">Admin</option>
			</select><br />
			<label>&nbsp;</label><input type='submit' name='submit_edit_user' value='Submit' />
			<!--<label>&nbsp;</label><input type='submit' name='delete_user'id='delete_user' value='Delete User' />-->
			</form>
			</div>
			";
        
        return $buffer;
    }
    //-----------------------------------------------------------------------------------------------------------
    public function ProcessUser($user_arr)
    {
        if ($user_arr['submit']) {
            //print_r($user_arr);	
            if ($this->ValidateName($user_arr['fname']) === false) {
                $message .= "<span>First Name is too short</span>";
            }
            if ($this->ValidateName($user_arr['lname']) === false) {
                $message .= "<span>Last Name is too short</span>";
            }
            if ($this->ValidateName($user_arr['user_name']) === false) {
                $message .= "<span>Username is too short</span>";
            }
            if ($this->ValidateUsername($user_arr['user_name'],"") === TRUE) {
                $message .= "<span>Username already exists. try a different one</span>";
            }
            if ($this->ValidateEmail($user_arr['email']) === false) {
                $message .= "<span>Email format is not valid</span>";
            }
            if ($this->CheckPW($user_arr['password']) < 3) {
                $message .= "<span>Password is too easy, use at least 4 charachters, letters and numbers.</span>";
            }
            
            if (!$message) {
                $fields   = array(
                    "fname" => $user_arr['fname'],
                    "lname" => $user_arr['lname'],
                    "user_name" => $user_arr['user_name'],
                    "email" => $user_arr['email'],
                    "password" => md5($user_arr['password'])
                );
                $new_id   = $this->add(PREFIX . "admin_users", $fields);
                $user_arr = ($new_id > 1) ? "" : $user_arr;
                $message  = "<p class='success'>New user was added</p>";
            } else {
                $message = "<p class='error'>" . $message . "</p>";
            }
        }
        $buffer .= $this->CreateUserForm($message, $user_arr);
        
        
        return $buffer;
    }
//-----------------------------------------------------------------------------------------------------------
    public function ProcessEditUser($user_arr)
    {
        if ($user_arr['submit_edit_user']) {
            //print_r($user_arr);	
            if ($this->ValidateName($user_arr['fname']) === false) {
                $message .= "<span>First Name is too short</span>";
            }
            if ($this->ValidateName($user_arr['lname']) === false) {
                $message .= "<span>Last Name is too short</span>";
            }
            if ($this->ValidateName($user_arr['user_name']) === false) {
                $message .= "<span>Username is too short</span>";
            }
            if ($this->ValidateUsername($user_arr['user_name'],$user_arr['id']) === TRUE) {
                $message .= "<span>Username already exists. try a different one</span>";
            }
            if ($this->ValidateEmail($user_arr['email']) === false) {
                $message .= "<span>Email format is not valid</span>";
            }
			if($user_arr['password']!=""){
	            if ($this->CheckPW($user_arr['password']) < 3) {
	                $message .= "<span>Password is too easy, use at least 4 charachters, letters and numbers.</span>";
	            }
			}
            
            if (!$message) {
                $fields   = array(
                    "fname" => $user_arr['fname'],
                    "lname" => $user_arr['lname'],
                    "user_name" => $user_arr['user_name'],
                    "email" => $user_arr['email']
                );
                if($user_arr['password']!=""){
                	$fields["password"] = md5($user_arr['password']);	
                }
				$where = "id='".$user_arr['id']."'";
                $new_id   = $this->update(PREFIX . "admin_users", $fields, $where, "");
                $user_arr = ($new_id > 1) ? "" : $user_arr;
                $message  = "<p class='success'>User details updated</p>";
            } else {
                $message = "<p class='error'>" . $message . "</p>";
            }
        }
        $buffer .= $this->CreateEditUserForm($message, $user_arr);
        
        
        return $buffer;
    }
    //-----------------------------------------------------------------------------------------------------------
    private function ValidateName($name)
    {
        if (trim($name) != "" && strlen($name) > 1) {
            return TRUE;
        } else {
            return FALSE;
        }
        
        
    }
    //-----------------------------------------------------------------------------------------------------------
    private function ValidateUsername($user, $user_id)
    {
        if($user_id>0){
        $UsernameID ="AND `id`!='".$user_id."'";
        }
		else{
		$UsernameID ="";	
		}
        $num_of_users = $this->getCount(PREFIX . "admin_users", "`user_name`='$user'".$UsernameID, "", 1, false);
        if ($num_of_users > 0)
            return TRUE;
        return FALSE;
        
    }
    //-----------------------------------------------------------------------------------------------------------
    public function ValidateEmail($email)
    {
        if (!preg_match('/^[a-z0-9&\'\.\-_\+]+@[a-z0-9\-]+\.([a-z0-9\-]+\.)*+[a-z]{2}/is', $email))
            return false;
        
        return true;
    }
    //-----------------------------------------------------------------------------------------------------------
    function CheckPW($password)
    {
        if (strlen($password) < 4) {
            return 0;
        } else {
            $strength = 0;
            $patterns = array(
                '/[a-z]/',
                '/[0-9]/',
                '/[?-?]/',
                '/[A-Z]/',
                '/[0-9]/',
                '/[¬!"£$%^&*()`{}\[\]:@~;\'#<>?,.\/\\-=_+\|]/'
            );
            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $password)) {
                    $strength++;
                }
            }
            return $strength;
        }
    }
}

?>