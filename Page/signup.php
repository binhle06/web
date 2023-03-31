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
            include 'Css/Register.css'; 
        ?>
    </style>

    <section>
        
        <div class="ContentBx">
            <div class="FormBx">
                    <h2>Đăng kí</h2>
                    <form method="post" name="loginform" id="loginform">
                    <div class="InputBx">
                        <span>User Name</span>
                        <input type="text" name="uid">
                    </div>
                    <div class="InputBx">
                        <span>Name</span>
                        <input type="text" name="email">
                    </div>
                    <div class="InputBx">
                        <span>Password</span>
                        <input type="password" name="pwd">
                    </div>
                    <div class="InputBx">
                        <span>Confirm password</span>
                        <input type="password" name="pwdRepeat">
                    </div>
                    <br>
                    <br>
                    <div class="InputBx">
                        <input type="submit" name="submit" value="Signup">
                    </div>
                    
                    <div class="InputBx">
                        <p>Bạn chưa có tài khoản ? <a href="Page/signup.php">Đăng nhập</a></p>
                    </div>
                </form>
            </div>
        </div>
        <div class="imgBx">
            <img src="Image/Test_Image.jpg">
        </div>
    </section>
    

<script>
    $(document).ready(function() {
    $('#loginform').submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: '/login.php',
            data: $(this).serialize(),
            success: function(response)
            {
                var jsonData = JSON.parse(response);
  
                // user is logged in successfully in the back-end
                // let's redirect
                if (jsonData.success == "1")
                {
                    location.href = 'index.php';
                }
                else if (jsonData.success == "2")
                {
                    location.href = 'index_teacher.php';
                }
                else if (jsonData.success == "3")
                {
                    location.href = 'index_admin.php';
                }
                else
                {
                    alert('Invalid Credentials!');
                }
           }
       });
     });
});
</script>
    
</body>

</html>