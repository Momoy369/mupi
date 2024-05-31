<?php include 'include/koneksi.php';
$query = "SELECT * FROM tbl_setting WHERE id = 1";
$ms = mysqli_query($conn, $query);
$result = mysqli_fetch_assoc($ms);

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>MUPI - ADMIN LOGIN</title>

    <!-- Custom fonts for this template-->
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <!-- Outer Row -->
        <center>
            <div class="row justify-content-center col-lg-6">

                <div class="col-xl-10 col-lg-12 col-md-9">

                    <div class="card o-hidden border-0 shadow-lg my-5">
                        <div class="card-body p-0">
                            <!-- Nested Row within Card Body -->
                            <div class="col-lg-12">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h5 text-gray-900 mb-4">Welcome Back to
                                            <?= $result['app_name']; ?> Admin Panel!
                                        </h1>
                                    </div>
                                    <form class="user" method="post" action="action/login.php">
                                        <div class="form-group">
                                            <input type="email" name="email" class="form-control form-control-user"
                                                id="inputEmail" aria-describedby="emailHelp"
                                                placeholder="Enter Email Address...">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" name="password"
                                                class="form-control form-control-user" id="inputPassword"
                                                placeholder="Password">
                                        </div>
                                        <button class="btn btn-primary btn-user btn-block" type="submit">
                                            Login
                                        </button>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="#">Forgot Password?</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="#">Create an Account!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </center>

    </div>

    <?php include 'include/components.php'; ?>

</body>

</html>