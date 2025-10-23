<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/db.php';
function auth_login($username,$password){
  $data = db_load();
  foreach($data['users'] as $u){
    if ($u['username']==$username && password_verify($password,$u['password'])){
      $_SESSION['user'] = ['id'=>$u['id'],'username'=>$u['username'],'role'=>$u['role']];
      return true;
    }
  }
  return false;
}
function auth_check(){ if(!isset($_SESSION['user'])){ header('Location: login.php'); exit; } }
function auth_logout(){ session_unset(); session_destroy(); }
?>
