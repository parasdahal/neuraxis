<?php
require ($CLASS_CONNECTION);

class User {
	
  public function isLoggedIn() {
  
    if (isset($_SESSION['neuraxis'])) {
      return 1;
    }

      else {
        return 0;
      	} }

  public function fetch($tfield,$table,$field,$arg) {
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

      public function fetchByField ($tfield,$table,$field,$arg) {
        global $pdo;
        if(!empty($tfield)&&!empty($table)&&!empty($field)&&!empty($arg)) {
            $query=$pdo->prepare("SELECT $tfield FROM $table WHERE $field='$arg'");
            if($query->execute()) {
                $item=$query->fetchAll();
                if ($item) {
                    return $item;
                } else {
                    return 1;
                }
            }
        } else {
            return 2;
        } }


  public function login($email,$password) {
    global $pdo;
    if(!empty($email) and !empty($password)) {
      $query=$pdo->prepare("SELECT id from users WHERE email='$email' and password='$password'");
      $query->execute();
      $result=$query->fetch();
      if($result) {
      $_SESSION['neuraxis']=$result['id'];
       return 0;
      } 
      else if(!$result){
        return 1;
      }
    }
    }
  
  public function addInstance($name,$config,$state) {

      global $pdo;

      if(!empty($name)&&!empty($config)&&!empty($state)) {

                            $user=$_SESSION['neuraxis'];
                             $query=$pdo->prepare("INSERT INTO instance VALUES ('','$user','$name','$config','$state','0',NOW())");

                              if ($query->execute()) {
                              return 0;  
                          } 
                          else {
                            return $query->errorInfo();
                          }
                             }
                      }

public function delete($table,$id) {

      global $pdo;

      if(!empty($table)&&!empty($id)) {

                             $query=$pdo->prepare("DELETE FROM instance WHERE id='$id'");

                              if ($query->execute()) {
                              return 0;  
                          } 
                          else {
                            return $query->errorInfo();
                          }
                             }
                      }
  }
?>  