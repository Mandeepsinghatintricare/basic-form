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
                            <button onclick=\"editData(". $value['id'].")\" type=\"button\" class=\"btn btn-primary\">
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

function clean($string) {
   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
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
            $fname = mysqli_real_escape_string($conn,$_POST['fname']);
            $lname = mysqli_real_escape_string($conn,$_POST['lname']);
            $email = mysqli_real_escape_string($conn,$_POST['email']);
            $gender = mysqli_real_escape_string($conn,$_POST['gender']);
            $hobby = array();

            $fname = clean($fname);
            $lname = clean($lname);

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
                // echo "image moved";
                // echo "<img src=\"image/".$filename."\">";
            }
            else{
                    $msg = "Failed to upload image";
            }
            // echo ($fname.$lname.$email.$gender.$serialhobby.$address.$filename);
            if ($fname !== "" && $email !== "") {
                $sql = "INSERT INTO `user-data`(`fname`, `lname`, `email`, `gender`, `hobby`, `address`, `image`) VALUES ('$fname','$lname','$email','$gender','$serialhobby','$address','$filename')";
                if (mysqli_query($conn, $sql)) {
                    // Success
                    $sql = "SELECT * FROM `user-data`";
                    $result = mysqli_query($conn, $sql);
                    printOutput($result);
                } else {
                    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                }
            }
            else{
                // Error
            }
            mysqli_close($conn);
            unset($_POST['fname']);
            unset($_POST['lname']);
            unset($_POST['email']);
            unset($_POST['gender']);
            unset($_POST['address']);
        }

        elseif ($_GET['action'] == "update") {

            $id = $_POST['userId'];
            $sql = "SELECT * FROM `user-data` WHERE id = $id";
            $result = mysqli_query($conn, $sql);
            if (mysqli_fetch_array($result)) {
                $user = mysqli_fetch_array($result);
                foreach ($result as $key => $value) {
                    $value['hobby']=unserialize($value['hobby']);
                    echo json_encode($value);
                }
            }
        }

        if ($_GET['action']=="edit") {

            $imgcount=1;
            $id = mysqli_real_escape_string($conn,$_POST['userId']);;
            $fname = mysqli_real_escape_string($conn,$_POST['fname']);
            $lname = mysqli_real_escape_string($conn,$_POST['lname']);
            $email = mysqli_real_escape_string($conn,$_POST['email']);
            $gender = mysqli_real_escape_string($conn,$_POST['gender']);
            $fname = clean($fname);
            $lname = clean($lname);
            if ($_POST['hobby'] != "") {
                $hobby = array($_POST['hobby']);
                $serialhobby = serialize($hobby);
            }
            $address = mysqli_real_escape_string($conn,$_POST['address']);
            if ($_FILES['image']['name'] !== "") {
                $extension = stristr($_FILES['image']['name'], ".");
                $filename = $fname.$lname.$extension;
                $filename = str_replace(".", "-T-".date("Y_m_d_H_i").".", ($filename));
                $tempname = ($_FILES['image']['tmp_name']);
                $folder = "image/".$filename;

                if(move_uploaded_file($tempname, $folder)){
                // Success
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
                        $sql = "SELECT * FROM `user-data`";
                        $result = mysqli_query($conn, $sql);
                        printOutput($result);
                        // Success
                    } else {
                        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                    }
                }
                else{
                    $sql = "UPDATE `user-data` SET `fname`='$fname',`lname`='$lname',`email`='$email',`gender`='$gender',`hobby`='$serialhobby',`address`='$address' WHERE id = $id";
                    if (mysqli_query($conn, $sql)) {
                        $sql = "SELECT * FROM `user-data`";
                        $result = mysqli_query($conn, $sql);
                        printOutput($result);
                        // Success
                    } else {
                        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                        //  Error
                    }
                }
            }
            else{
                // error
            }
            mysqli_close($conn);
            unset($_POST['fname']);
            unset($_POST['lname']);
            unset($_POST['email']);
            unset($_POST['gender']);
            unset($_POST['address']);
        }
    }
?>
