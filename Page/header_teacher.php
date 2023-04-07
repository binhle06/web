<?php

//index.php

include('../DatabaseConn/Exam_Structure.php');

$exam = new Exam_Struct;

$exam->Teacher_session_private();

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Admin Homepage</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@500&display=swap" rel="stylesheet">
 

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
            include '../Css/Header_Teacher.css'; 
        ?>
    </style>
        <nav>
        <input type="checkbox" id="check">
        <label for="check" class="checkbtn">
            <i class="fas fa-bars"></i>
        </label>
        <label class="logo">Examination: Teacher</label>
        <ul>
            <li><a class="active" href="index_teacher.php">Home</a></li>
            <li><a href="Examlist_Teacher.php">Exam List</a></li>
            <li><a href="ResultList_Teacher.php">ResultList</a></li>
            <li><a href="profile_teacher.php">Profile</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
        </nav>