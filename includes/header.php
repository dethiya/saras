<?php include ('init.php'); ?>
<?php ob_start();?>
<?php $session_user=User::find($session->user_id); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title><?=$page_title?></title>
        <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
        <meta content="" name="description" />
        <meta content="" name="author" />
        <link rel="icon" href="images/mi5.ico"  type="image/icon type">
        <link href="assets/css/apple/app.min.css" rel="stylesheet" />
        <link href="assets/plugins/ionicons/css/ionicons.min.css" rel="stylesheet" />
        <link href="assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
        <link href="assets/plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
        <link href="assets/plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" />
        <link href="assets/plugins/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" />
        <link href="assets/css/styles.css" rel="stylesheet" />
        <link href="assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css" integrity="sha512-6S2HWzVFxruDlZxI3sXOZZ4/eJ8AcxkQH1+JjSe/ONCEqR9L4Ysq5JdT5ipqtzU7WHalNwzwBv+iE51gNHJNqQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    </head>
    <body>
<!--        <div id="page-loader" class="fade show">-->
<!--            <span class="spinner"></span>-->
<!--        </div>-->

        <div id="page-container" class="fade page-sidebar-fixed page-header-fixed">