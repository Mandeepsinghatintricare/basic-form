<?php
    require('dbconnection.php');
    $sql = "SELECT * FROM `user-data`";
    $result = mysqli_query($conn, $sql);;
    mysqli_close($conn);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Data</title>
    <style type="text/css">
        body{
            background: rgb(37 42 55);
            color: rgb(255 255 255);
            font-family: scandia-web,ui-sans-serif,system-ui,-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Arial,Noto Sans,sans-serif,Apple Color Emoji,Segoe UI Emoji,Segoe UI Symbol,Noto Color Emoji;
        }
        #search-box{
            margin-bottom: 10px;
        }
        table{
            margin: auto;
            height: 50%;
            width: 75%;
        }
        table * {
            border-right: 2px solid lightskyblue;
            border-bottom: 2px solid lightskyblue;
        }
        th{
            color: #ca473f;
            border-top: 2px solid lightskyblue;
        }
        tbody > tr > *:first-child, thead *:first-child{
            border-left: 2px solid lightskyblue;
        }
        a{
            margin: auto;
            color: #ca473f;
            padding: 5px;
            border: 2px solid lightskyblue;
            text-decoration: none;
            display: block;
            text-align: center;
        }
        img{width: 100px;}
        section > a{
            width: 10%;
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>

    <script type="text/javascript">
        function searchUser(str){
            if(str.length == 0){
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function(){
                    if(this.readyState == 4 && this.status == 200){
                        document.getElementById('output').innerHTML = this.responseText;
                    }
                }
                xmlhttp.open("GET", "action.php?action=search&query="+str, true);
                xmlhttp.send();
            }
            else{
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function(){
                    if(this.readyState == 4 && this.status == 200){
                        document.getElementById('output').innerHTML = this.responseText;
                    }
                }
                xmlhttp.open("GET", "action.php?action=search&query="+str, true);
                xmlhttp.send();
            }
        }

        function deleteUser(id){
            let confirmDelete = confirm("Do You Want to delete User Record of user no "+id);
            if (confirmDelete) {
                var query = document.getElementById('search').value;
                console.log(query);
                var xmlhttp = new XMLHttpRequest();
                                xmlhttp.onreadystatechange = function(){
                        if(this.readyState == 4 && this.status == 200){
                            document.getElementById('output').innerHTML = this.responseText;
                        }
                    }
                xmlhttp.open("GET", "action.php?action=delete&id="+id+"&query="+query, true);
                xmlhttp.send();
                console.log(id);
            }
        }
    </script>
</head>
<body>
    <section id="search-box">
        <form action="index.php" method="POST">
            <label for="search">Search</label>
            <input id="search" type="text" name="query" onkeyup="searchUser(this.value)">
        </form>
    </section>
    <section>
        <a href="form.php">Add New User</a>
    </section>
    <hr>
    <section id="output">
        <h3 id="test"></h3>
        <table>
            <thead>
                <th>Id</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Gender</th>
                <th>Hobbies</th>
                <th>Address</th>
                <th>Image</th>
                <th>Actions</th>
            </thead>
            <tbody>
                <?php
                    foreach ($result as $key => $value) {
                ?>
                <tr class="trow">
                    <td><?= $value['id']?></td>
                    <td><?= $value['fname']?></td>
                    <td><?= $value['lname']?></td>
                    <td><?= $value['email']?></td>
                    <td><?= $value['gender']?></td>
                    <td><?php 
                        if (null == (unserialize($value['hobby']))) {
                            echo "No Hobbies";
                        }
                        else{
                            foreach (unserialize($value['hobby']) as $key => $hobby) {
                                    echo " ".$hobby;
                            }
                        }
                        ?>  
                    </td>
                    <td><?= nl2br($value['address'])?></td>
                    <td>
                        <img src="image/<?= $value['image']?>">
                    </td>
                    <td>
                        <a href="edit-form.php?id=<?= $value['id']?>">Edit</a>
                        <a onclick="deleteUser(<?= $value['id']?>)">Delete</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </section>
</body>
</html>
