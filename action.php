<?php
function printOutput($result){
     $output =  "<table class=\"table table-bordered\">
                <thead>
                <tr>
                    <th  scope=\"col\">Id</th>
                    <th  scope=\"col\">First Name</th>
                    <th  scope=\"col\">Last Name</th>
                    <th  scope=\"col\">Email</th>
                    <th  scope=\"col\">Gender</th>
                    <th  scope=\"col\">Hobbies</th>
                    <th  scope=\"col\">Address</th>
                    <th  scope=\"col\">Image</th>
                    <th  scope=\"col\">Actions</th>
                </tr>
                </thead>
                <tbody>";
                foreach ($result as $key => $value) {
                    $output .= "<tr>
                        <th  scope=\"col\">". $value['id']."</th>
                        <td>". $value['fname']."</td>
                        <td>". $value['lname']."</td>
                        <td>". $value['email']."</td>
                        <td>". $value['gender']."</td>
                        <td>";

                            if (null == (unserialize($value['hobby']))) {
                                $output .= "No Hobbies";
                            }
                            else{
                                foreach (unserialize($value['hobby']) as $key => $hobby) {
                                     $output .= " ".$hobby;
                                }
                            }

                        $output .= "</td>
                        <td>". nl2br($value['address'])."</td>
                        <td>
                            <img src=\"image/". $value['image']."\">
                        </td>
                        <td>
                            <button type=\"button\" class=\"btn btn-primary\" data-toggle=\"modal\" data-target=\"#editModal\">
                          Edit
                        </button>
                        <button type=\"button\" class=\"btn btn-primary\" onclick=\"deleteUser(". $value['id'].")\">
                          Delete
                        </button>
                        </td>
                    </tr>";
                 }

            $output .= "</tbody>
                </table>";
            
            echo "$output";
}
    require('dbconnection.php');
    if (isset($_GET['action'])) {
        if ($_GET['action'] == "search") {
            if (isset($_GET['query'])){
                $query = $_GET['query'];
                if ($query != "") {
                    $sql = "SELECT * FROM `user-data` WHERE (
                    fname like '%$query%' OR
                    lname like '%$query%' OR
                    email like '%$query%')";
                    $result = mysqli_query($conn, $sql);
                }
                else{
                    echo "<script> alert('No Such Records')</script>";
                    $sql = "SELECT * FROM `user-data`";
                    $result = mysqli_query($conn, $sql);                
                }
            }
            else{
                $sql = "SELECT * FROM `user-data`";
                $result = mysqli_query($conn, $sql);;
            }
        mysqli_close($conn);
        printOutput($result);
           
        }

        if ($_GET['action'] == "delete") {
            if (isset($_GET['id'])){
                $id = $_GET['id'];
                $deletesql = "DELETE FROM `user-data` WHERE id = $id";
                mysqli_query($conn, $deletesql);
                if (isset($_GET['query'])){
                    $query = $_GET['query'];
                    if ($query != "") {
                        $sql = "SELECT * FROM `user-data` WHERE (
                        fname like '%$query%' OR
                        lname like '%$query%' OR
                        email like '%$query%')";
                        $result = mysqli_query($conn, $sql);
                    }
                    else{
                        echo "<script> alert('No Such Records')</script>";
                        $sql = "SELECT * FROM `user-data`";
                        $result = mysqli_query($conn, $sql);                
                    }
                }
                
                else{
                    $sql = "SELECT * FROM `user-data`";
                    $result = mysqli_query($conn, $sql);
                }
                printOutput($result);
            }
        }

        if ($_GET['action']=="new") {
            $fname = mysqli_real_escape_string($conn,$_POST['fName']);
            $lname = mysqli_real_escape_string($conn,$_POST['lName']);
            $email = mysqli_real_escape_string($conn,$_POST['email']);
            $gender = mysqli_real_escape_string($conn,$_POST['gender']);
            $hobby = array();
            if (isset($_POST['hobby1'])) {
                array_push($hobby,($_POST['hobby1']));
            }
            if (isset($_POST['hobby2'])) {
                array_push($hobby,($_POST['hobby2']));
            }
            if (isset($_POST['hobby3'])) {
                array_push($hobby,($_POST['hobby3']));
            }
            $serialhobby = serialize($hobby);
            $address = mysqli_real_escape_string($conn,$_POST['address']);
            
            $extension = stristr($_FILES['image']['name'], ".");
            $filename = $fname.$lname.$extension;
            $filename = str_replace(".", "-T-".date("Y_m_d_H_i").".", ($filename));
            $tempname = ($_FILES['image']['tmp_name']);
            $folder = "image/".$filename;

            if(move_uploaded_file($tempname, $folder)){
                echo "image moved";
                echo "<img src=\"image/".$filename."\">";
            }
            else{
                    $msg = "Failed to upload image";
            }
            if ($fname !== "" && $email !== "") {
                $sql = "INSERT INTO `user-data`(`fname`, `lname`, `email`, `gender`, `hobby`, `address`, `image`) VALUES ('$fname','$lname','$email','$gender','$serialhobby','$address','$filename')";
                if (mysqli_query($conn, $sql)) {
                    echo "New record created successfully";
                    header('Location:index.php');
                } else {
                    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                    header('Location:form.php');
                }
            }
            else{
                header('Location:form.php');
            }
            mysqli_close($conn);
            unset($_POST['fname']);
            unset($_POST['lname']);
            unset($_POST['email']);
            unset($_POST['gender']);
            unset($_POST['address']);
        }

        elseif ($_GET['action'] == "update") {
            if (isset($_GET['id'])) {
                $imgcount=1;
                $id = $_GET['id'];
                $fname = mysqli_real_escape_string($conn,$_POST['fName']);
                $lname = mysqli_real_escape_string($conn,$_POST['lName']);
                $email = mysqli_real_escape_string($conn,$_POST['email']);
                $gender = mysqli_real_escape_string($conn,$_POST['gender']);
                $hobby = array();
                if (isset($_POST['hobby1'])) {
                    array_push($hobby,($_POST['hobby1']));
                }
                if (isset($_POST['hobby2'])) {
                    array_push($hobby,($_POST['hobby2']));
                }
                if (isset($_POST['hobby3'])) {
                    array_push($hobby,($_POST['hobby3']));
                }
                $serialhobby = serialize($hobby);
                $address = mysqli_real_escape_string($conn,$_POST['address']);
                if ($_FILES['image']['name'] !== "") {
                    $extension = stristr($_FILES['image']['name'], ".");
                    $filename = $fname.$lname.$extension;
                    $filename = str_replace(".", "-T-".date("Y_m_d_H_i").".", ($filename));
                    $tempname = ($_FILES['image']['tmp_name']);
                    $folder = "image/".$filename;

                    if(move_uploaded_file($tempname, $folder)){
                    echo "image moved";
                    echo "<img src=\"image/".$filename."\">";
                    $imgcount = 0;
                    }
                    else{
                    $msg = "Failed to upload image";
                    }
                }
                if ($fname !== "" && $email !== "") {
                    if ($imgcount == 0) {
                       $sql = "UPDATE `user-data` SET `fname`='$fname',`lname`='$lname',`email`='$email',`gender`='$gender',`hobby`='$serialhobby',`address`='$address',`image`='$filename' WHERE id = $id";
                        if (mysqli_query($conn, $sql)) {
                            echo "New record created successfully";
                            header('Location:index.php');
                        } else {
                            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                            header('Location:form.php');
                        }
                    }
                    else{
                        $sql = "UPDATE `user-data` SET `fname`='$fname',`lname`='$lname',`email`='$email',`gender`='$gender',`hobby`='$serialhobby',`address`='$address' WHERE id = $id";
                        if (mysqli_query($conn, $sql)) {
                            echo "New record created successfully";
                            header('Location:index.php');
                        } else {
                            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                            header('Location:form.php');
                        }
                    }
                }
                else{
                    header('Location:form.php');
                }
                mysqli_close($conn);
                unset($_POST['fname']);
                unset($_POST['lname']);
                unset($_POST['email']);
                unset($_POST['gender']);
                unset($_POST['address']);
            }
            else{
                header("location:index.php");
            }
        }
    }
?>