<?php

//index.php

include('../DatabaseConn/Exam_Structure.php');

$exam = new Exam_Struct;

$exam->Admin_session_private();

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Admin Homepage</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@500&display=swap" rel="stylesheet">
 <!--   <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script scr="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.css"/>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
-->

    <!-- bootstrap Lib -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>


    <!-- datatable lib -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/guillaumepotier/Parsley.js@2.9.1/dist/parsley.js"></script>


</head>
<body>
    <style>
        <?php 
            include '../Css/Header_admin.css'; 
        ?>
    </style>
        <nav>
        <input type="checkbox" id="check">
        <label for="check" class="checkbtn">
            <i class="fas fa-bars"></i>
        </label>
        <label class="logo">Examination: Admin</label>
        <ul>
            <li><a class="active" href="index_admin.php">Home</a></li>
            <li><a href="">Result List</a></li>
            <li><a href="Examlist_Admin.php">Exam List</a></li>
            <li><a href="UserList_Admin.php">User List</a></li>
            <li><a href="profile_admin.php">Profile</a></li>
            <li><a href="logout.php">Log out</a></li>
        </ul>
        </nav>