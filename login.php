<?php include "functions.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?= style_script() ?>
    <title>Login</title>
</head>
<body class="text-center">
    <?php
    $errorMsg = null;
    
    $check_login_row = $query->fetch_assoc();
      $total_count = $check_login_row['total_count'];
    
    if ($total_count == 3)  {
        $errorMsg = "To many failed login attempts. Please login after 10 mintus";
    }
        if (isset($_POST['username']) && isset($_POST['password'])) {

            session_start();
            $user = $_POST['username'];
            $pass = $_POST['password'];
            $salt = "XDrBmrW9g2fb";
            $pdo = pdo_connect();
            $stmt = $pdo->prepare('SELECT * FROM users WHERE username = "' . $user . '" AND password = "' . hash('sha256', $pass . $salt) . '" LIMIT 1');
            $stmt->execute();
            $errorMsg = $stmt->rowCount();
            if ($stmt->rowCount() > 0) {
                $_SESSION['user'] = $user;
                header("location: index.php");
            } else {
                $total_count++;
                $rem_attm = 3-$total_count;
                if($rem_attm == 0){
                    $errorMsg = "To many failed login attempts. Please login after 10 mintus";
                }   else{
                $errorMsg = "Wrong usename or password";
                }
            }
            $timeout = 1; // setting timeout dalam menit
            $logout = "logout.php"; // redirect halaman logout
            $timeout = $timeout * 60; // menit ke detik
            if(isset($_SESSION['start_session'])){
                $elapsed_time = time()-$_SESSION['start_session'];
                    if($elapsed_time >= $timeout){
                        session_destroy();
                    }
            }  
            $_SESSION['start_session']=time();
        }

    ?>
    <form class="form-signin" method="POST">
        <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
        <label for="inputUsername" class="sr-only">Username</label>
        <input type="username" id="inputUsername" name="username" class="form-control" placeholder="Username" required autofocus>
        <br>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password" required>
        <div class="checkbox mb-3">
            <label>
                <?= $errorMsg ?>
            </label>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
        <p class="mt-5 mb-3 text-muted">hk &copy; 2021</p>
    </form>
</body>

</html>