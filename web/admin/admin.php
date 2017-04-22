<?php 
require ('../config.php');
require ($CLASS_CONNECTION);


class LibAdmin {
	
  public function addUser($f_name,$l_name,$email,$password,$pass_again,$utype) {
		global $pdo;

		if(!empty($email)&&!empty($password)&&!empty($pass_again)&&!empty($f_name)&&!empty($l_name)) {
			
			if($password!=$pass_again)
                    {
                        return 1;
                    }
                    else
                    {
                        $query=$pdo->prepare("SELECT count(*) FROM user WHERE User_Email='$email'");
                        $query->execute();
                        
                        if($query->fetchColumn())
                           {
                               return 2;
                           }
                        else
                           {
                             $password_hash=md5($password);
                             $date=time();
                             $new_date=(int)$date+(365*24*60*60);
                            
                            $query=$pdo->prepare("INSERT INTO user VALUES ('','$f_name','$l_name','$email','$password_hash','$date','$new_date','$utype','')");
                            if ($query->execute()) {
                            return 0;	 
                        } 
                        else {
                        	print_r($query->errorInfo());
                        }
           
                            
                
                           }
                    }
		} }

  public function isLoggedIn() {
  
  if (isset($_SESSION['lib']) and $_SESSION['lib'][1]==1) {
    return 1;
  }

    else {
      return 0;
    }
  }

  public function fetchByField ($tfield,$table,$field,$arg) {
        global $pdo;
        if(!empty($tfield)&&!empty($table)&&!empty($field)&&!empty($arg)) {
            $query=$pdo->prepare("SELECT $tfield FROM $table WHERE $field='$arg'");
            if($query->execute()) {
                $item=$query->fetch();
                if ($item) {
                    return $item;
                } else {
                    return 1;
                }
            }
        } else {
            return 2;
        } } 

  public function fetchUsers() {
    global $pdo;
        
            $query=$pdo->prepare("SELECT  User_Id, User_Fname,User_Lname,User_Email,Reg_Date,Valid_Date,User_Type FROM user");
            if($query->execute()) {
                $item=$query->fetchAll();
                if ($item) {
                    return $item;
                } else {
                    return 1;
                }
            }
        } 

  public function deleteUser($id) {
    global $pdo;
        if(!empty($id)) {
            $query=$pdo->prepare("DELETE FROM user WHERE  User_Id='$id'");
            if($query->execute()) {
                return 0;
                } else {
                    return 1;
                }
            }
      }

  public function fetchRequests() {
    global $pdo;
    $sql = "SELECT  User_Id,User_Fname,User_Lname, User_Email, Req_Date FROM user_req ORDER BY Req_Date ASC";
     $query=$pdo->prepare($sql);

     if($query->execute()) {
                $item=$query->fetchAll();
                if ($item) {
                    return $item;
                } else {
                    return 1; //no item
                }
            } 
          }

  public function deleteFromRequest($Id) {
     global $pdo;
        if(!empty($Id)) {
            $query=$pdo->prepare("DELETE FROM user_req WHERE  User_Id='$Id'");
            if($query->execute()) {
                return 0;
                } else {
                    return 1;
                }
            }
  }



    public function approveRequest($Id) {
      global $pdo;

      $item=$this->fetchByField ('*','user_req','User_Id',$Id);
      if ($item==1){
        return $item;
      }
      else {
        $f_name=$item['User_Fname'];
        $l_name=$item['User_Lname'];
        $email=$item['User_Email'];
        $password=$item['User_Password'];
        $utype=$item['User_Type'];

        $approve=$this->addUser($f_name,$l_name,$email,$password,$password,$utype);

        if($approve==0) {
          $delete=$this->deleteFromRequest($Id);
        }

        return $approve;
      }
    }

  
  public function nav() {
    if($this->isLoggedIn()==1) {

      echo'<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div>
      <ul class="nav navbar-nav">
        <li><a href="../index.php">Home</a></li>
        <li><a href="../titles.php">Titles</a></li>
        <li><a href="../authors.php">Authors</a></li> 
        <li><a href="../publishers.php">Publishers</a></li>
        <li><a href="../generes.php">Geners</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="index.php"><span class="glyphicon glyphicon-home"></span> Dashboard</a></li>
        <li><a href="signup.php"><span class="glyphicon glyphicon-user"></span> Add User</a></li>
        <li><a href="../logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
      </ul>
    </div>
  </div>
</nav>';
    }

    else {
        echo'<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div>
      <ul class="nav navbar-nav">
        <li><a href="../index.php">Home</a></li>
        <li><a href="../titles.php">Titles</a></li>
        <li><a href="../authors.php">Authors</a></li> 
        <li><a href="../publishers.php">Publishers</a></li>
        <li><a href="../generes.php">Geners</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="../login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
      </ul>
    </div>
  </div>
</nav>';
    }
  }


  }
?> 