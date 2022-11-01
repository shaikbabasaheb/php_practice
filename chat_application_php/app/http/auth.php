<?php  
session_start();

if(isset($_POST['username']) &&
   isset($_POST['password'])){

  
   include 'db.conn.php';
   
   $password = $_POST['password'];
   $username = $_POST['username'];
   
   if(empty($username)){
      # error message
     $em = "Username is required";
     header("Location: ../../index.php?error=$em");
   }else if(empty($password)){
      # error message
      $em = "Password is required";

      
      header("Location: ../../index.php?error=$em");
   }else {
      $sql  = "SELECT * FROM 
               user WHERE username=?";
      $stmt = $conn->prepare($sql);
      $stmt->execute([$username]);

      
      if($stmt->rowCount() === 1){
        # fetching user data
        $user = $stmt->fetch();

        # if both username's are strictly equal
        if ($user['username'] === $username) {
           
           # verifying the encrypted password
          if (password_verify($password, $user['password'])) {

          
            $_SESSION['username'] = $user['username'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['user_id'] = $user['user_id'];

            # redirect to 'home.php'
            header("Location: ../../home.php");

          }else {
            # error message
            $em = "Incorect Username or password";

            # redirect to 'index.php' and passing error message
            header("Location: ../../index.php?error=$em");
          }
        }else {
          # error message
          $em = "Incorect Username or password";

          # redirect to 'index.php' and passing error message
          header("Location: ../../index.php?error=$em");
        }
      }
   }
}else {
  header("Location: ../../index.php");
  exit;
}
?>