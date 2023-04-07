<?php
//index.php

include('DatabaseConn/Exam_Structure.php');

$exam = new Exam_Struct;


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>LoginForm</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@500&display=swap" rel="stylesheet">
    <script scr="https://parsleyjs.org/dist/parsley.js"></script>
    <script scr="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <script scr="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.css"/>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.js"></script>

</head>

<body>
<style>
        <?php 
            include 'Css/Login.css'; 
        ?>
    </style>


    <section>
        <div class="imgBx">
            <img src="/ProjectExamManagement/Image/Test_Image.jpg">
        </div>
        <div class="ContentBx">
            <div class="FormBx">
                    <h2>Login</h2>
                <form method="post" id="LoginForm">
                    <div class="InputBx">
                        <span>Username</span>
                        <input type="text" name="username" id="username" required>
                    </div>
                    <div class="InputBx">
                        <span>Password</span>
                        <input type="password" name="password" id="password" required>
                    </div>
                    <br>
                    <br>
                    <div class="InputBx">
                        <input type="hidden" name="action" value="Login">
                        <input type="submit" name="submit" value="Login">
                    </div>
                    
                    <div class="InputBx">
                        <p>Don't have an account yet? <a href="register.php">Sign up</a></p>
                    </div>
                </form>
            </div>
        </div>
    </section>
    
    
</body>

</html>

<script>
    $(document).ready(function() {
    $('#LoginForm').submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: 'DatabaseConn/Ajax_func.php',
            data: $(this).serialize(),
            dataType:"json",
            success:function(data)
        {
 
                if (data.success  == "1")
                {
                    location.href = 'Page/index.php';
                }
                else if (data.success == "2")
                {
                    location.href = 'Page/index_teacher.php';
                }
                else if (data.success == "3")
                {
                    location.href = 'Page/index_admin.php';
                }
                else
                {
                    $('#message').html('<div class="alert alert-danger">'+data.error+'</div>');
                }
        }
        });
     });
    });
</script>