<?php
    if (isset($_GET['id'])) {
        require('dbconnection.php');
        error_reporting(1);
        $id = $_GET['id'];
        $sql = "SELECT * FROM `user-data` WHERE id = $id";
        $result = mysqli_query($conn, $sql);
        if (mysqli_fetch_array($result)) {
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" />
        <title>Basic Detail Form</title>

        <style type="text/css">
        a{
            color: #ca473f;
            padding: 3px;
            border: 2px solid gray;
            text-decoration: none;
        }
        body{
            background: rgb(37 42 55);
            color: rgb(181 181 189);
            font-family: scandia-web,ui-sans-serif,system-ui,-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Arial,Noto Sans,sans-serif,Apple Color Emoji,Segoe UI Emoji,Segoe UI Symbol,Noto Color Emoji;
        }
        span{
            color: red;
        }
        img{
            width: 50px;
        }
        </style>
    </head>
    <body>
        <section>
            <a href="index.php">Back</a>
        </section>
        <section>
            <form id="form" action="action.php?action=update&id=<?= $id?>" method="POST" enctype="multipart/form-data">
                <?php foreach ($result as $key => $value) {            
                        function genderCheck($gender,$value){
                            if ($gender == $value) {
                                return "checked";
                            }
                        }
                        function hobbyCheck($hobby,$value){
                            $unserialhobby = unserialize($hobby);
                            if (in_array($value, $unserialhobby)) {
                                return "checked";
                            }
                        }
                    ?>
                <div class="form-group">
                    <label for="fName">First Name <span>*</span> : &nbsp;</label>
                    <input id="fName" name="fName" type="text" value="<?= $value['fname']?>" required>
                </div>
                <div class="form-group">
                    <label for="lName">Last Name <span>*</span> : &nbsp;</label>
                    <input id="lName" name="lName" type="text" value="<?= $value['lname']?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email <span>*</span> : &nbsp;</label>
                    <input id="email" name="email" type="email" value="<?= $value['email']?>" required>
                </div>
                <div class="form-group">
                    <label>Gender : &nbsp;</label>
                    <label for="male">Male</label>
                    <input type="radio" id="male" name="gender" value="Male" <?= genderCheck($value['gender'],"Male")?>>
                    <label for="female">Female</label>
                    <input type="radio" id="female" name="gender" value="Female" <?= genderCheck($value['gender'],"Female")?>>
                    <label for="others">Others</label>
                    <input type="radio" id="others" name="gender" value="Others" <?= genderCheck($value['gender'],"Others")?>>
                </div>
                <div class="form-group">
                    <label>Hobby : &nbsp; </label>
                    <label for="hobby1">Travelling</label>
                    <input type="checkbox" id="hobby1" name="hobby1" value="Travelling" <?= hobbyCheck($value['hobby'],"Travelling")?>>
                    <label for="hobby2">Reading</label>
                    <input type="checkbox" id="hobby2" name="hobby2" value="Reading" <?= hobbyCheck($value['hobby'],"Reading")?>>
                    <label for="hobby3">Swimming</label>
                    <input type="checkbox" id="hobby3" name="hobby3" value="Swimming" <?= hobbyCheck($value['hobby'],"Swimming")?>>
                </div>
                <div class="form-group">
                    <label for="address">Address <span>*</span> : &nbsp;</label>
                    <textarea id="address" name="address" required><?= $value['address']?></textarea>
                </div>
                <div class="form-group">
                    <label for="image">Image :- <?= $value['image']?></label>
                    <img src="image/<?= $value['image']?>">
                    <input type="file" id="image" name="image" value="<?= $value['image']?>">
                </div>
                <button type="submit" name="submit">Submit</button>
            <?php }?>
            </form>
        </section>
    </body>
    <script>
      $(document).ready(function () {
        $('#form').validate({
          rules: {
            fName: {
              required: true
            },
            lName: {
              required: true
            },
            email: {
              required: true,
              email: true
            }
          },
          messages: {
            fName: 'Please enter First Name.',
            lName: 'Please enter Last Name.',
            email: {
              required: 'Please enter Email Address.',
              email: 'Please enter a valid Email Address.',
            }
          },
          submitHandler: function (form) {
            form.submit();
          }
        });
      });
    </script>
</html>
<?php 
    }
    else{
        header("location:index.php");
    }
}
else{
    header("location:index.php");
}
?>
