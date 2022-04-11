<?php 	require('dbconnection.php');
    	$sql = "SELECT * FROM `user-data`";
    	$result = mysqli_query($conn, $sql);;
    	mysqli_close($conn); ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Enter Your Details</title>
    <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
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

        $(() => {
	        // function will get executed 
	        // on click of submit button
	        $("#submitButtonNew").click(function(ev) {
	        	let formData = new FormData(document.getElementById("newDataForm"));
	         	ev.preventDefault();
	            $.ajax({
	                type: "POST",
	                url: "action.php?action=new",
	                enctype: 'multipart/form-data',
	                processData: false,  // do not process the data as url encoded params
	                cache: false,
				    contentType: false,
	                data: formData,
	                success: function(data) {
	                      
	                    // Ajax call completed successfully
	                    document.getElementById('output').innerHTML = (data);
	                   	alert("Form Submited Successfully");
	                    document.getElementById("newDataForm").reset();
	                    $('#newModal').modal('hide');
	                },
	                error: function(data) {
	                      
	                    // Some error in ajax call
	                    alert("some Error");
	                    // document.getElementById('output').innerHTML = this.responseText;
	                }
	            });
	        });
	    });

        function editData(id){
        	let userId = id;
            $.ajax({
                type: "POST",
                url: "action.php?action=update",
                data: {userId : userId},
                success: function(data) {
                    // console.log(data);
                    let result = ($.parseJSON(data));
                    // console.log(result);
                    // Ajax call completed successfully
                    $('#editModal').modal('show');
                    document.getElementById('userId').value = (result['id']);
                    document.getElementById('fnameNew').value = (result['fname']);
                    document.getElementById('lnameNew').value = (result['lname']);
                    document.getElementById('emailNew').value = (result['email']);
                    if (result['gender']=="Male") {
                    	document.getElementById('maleNew').checked = true;
                    }
                    else if (result['gender']=="Female") {
                    	document.getElementById('femaleNew').checked = true;
                    }
                    else if (result['gender']=="Others"){
                    	document.getElementById('otherNew').checked = true;
                    }
                    if(jQuery.inArray("Travelling", result['hobby']) !== -1){
                    	document.getElementById("travellingNew").checked = true;
                    }
                    if(jQuery.inArray("Reading", result['hobby']) !== -1){
                    	document.getElementById("readingNew").checked = true;
                    }
                    if(jQuery.inArray("Swimming", result['hobby']) !== -1){
                    	document.getElementById("swimmingNew").checked = true;
                    }
                    document.getElementById('inputAddressNew').value = (result['address']);
                    // document.getElementById('imageNew').value = (result['image']);
                    // $('#newModal').modal('hide');
                },
                error: function(data) {
                      
                    // Some error in ajax call
                    alert("some Error");
                    // document.getElementById('output').innerHTML = this.responseText;
                }
            });
        	// $('#editModal').modal('show');
        }

        $(() => {
	        // function will get executed 
	        // on click of submit button
	        $("#submitButtonEdit").click(function(ev) {
	        	let formData = new FormData(document.getElementById("editDataForm"));
	        	let hobby = [];  
	           	$('.newHobby').each(function(){  
	                if($(this).is(":checked"))  
	                {  
	                     hobby.push($(this).val());  
	                }  
	           }); 
	           formData.append('hobby', hobby);
	           
	         	ev.preventDefault();
	            $.ajax({
	                type: "POST",
	                url: "action.php?action=edit",
	                enctype: 'multipart/form-data',
	                processData: false,  // do not process the data as url encoded params
	                cache: false,
				    contentType: false,
	                data: formData,
	                success: function(data) {
	                      
	                    // console.log(data);
	                    // Ajax call completed successfully
	                    document.getElementById('output').innerHTML = (data);
	                   	alert("Form Updated Successfully");
	                    document.getElementById("editDataForm").reset();
	                    $('#editModal').modal('hide');
	                },
	                error: function(data) {
	                      
	                    // Some error in ajax call
	                    alert("some Error");
	                    // document.getElementById('output').innerHTML = this.responseText;
	                }
	            });
	        });
	    });

        function closeEdit(){
        	document.getElementById("editDataForm").reset();
        }
	    
    </script>
    <style type="text/css">
        img{width: 100px;}
    </style>
</head>
<body class="dark">
<div class="container">
    <br>
	<section id="search-box">
        <form action="index.php" method="POST">
            <label for="search">Search</label>
            <input id="search" type="text" name="query" onkeyup="searchUser(this.value)">
        </form>
    </section>
    <br>
    <div>
		<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newModal">
		  Add New User
		</button>
	</div>
	<hr>
    <div id="output" class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                	<th scope="col">Id</th>
                    <th scope="col">First Name</th>
                    <th scope="col">Last Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Gender</th>
                    <th scope="col">Hobby</th>
                    <th scope="col">Address</th>
                    <th scope="col">Images</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach ($result as $key => $value) {
                ?>
                <tr>
                    <th scope="row"><?= $value['id']?></th>
                    <td><?= $value['fname']?></td>
                    <td><?= $value['lname']?></td>
                    <td>@<?= $value['email']?></td>
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
                        <img src="image/<?= $value['image']?>" alt="#">
                    </td>
                    <td>
                        <!-- <a href="edit-form.php?id=<?= $value['id']?>">Edit</a> -->
                        <button onclick="editData(<?= $value['id']?>)" type="button" class="btn btn-primary">
                          Edit
                        </button>
                        <button type="button" class="btn btn-primary" onclick="deleteUser(<?= $value['id']?>)">
                          Delete
                        </button>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

	<!-- Modal For New Users -->
	<div class="modal fade" id="newModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title">Enter Your Details</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	            <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	        <form id="newDataForm" action="" method="POST" enctype="multipart/form-data">
	          <div class="form-row">
	            <div class="form-group col-md-6">
	                <input id="fname" name="fname" type="text" class="form-control" placeholder="First name">
	            </div>
	            <div class="form-group col-md-6">
	                <input id="lname" name="lname" type="text" class="form-control" placeholder="Last name">
	            </div>
	            </div>
	            <div class="form-group">
	                <label for="inputEmail4">Email</label>
	                <input id="email" name="email" type="email" class="form-control" id="inputEmail4" placeholder="Email">
	            </div>
	            <div class="form-group">
	                <div class="form-check form-check-inline">
	                  <input class="form-check-input" type="radio" name="gender" id="male" value="Male">
	                  <label class="form-check-label" for="male">Male</label>
	                </div>
	                <div class="form-check form-check-inline">
	                  <input class="form-check-input" type="radio" name="gender" id="female" value="Female">
	                  <label class="form-check-label" for="female">Female</label>
	                </div>
	                <div class="form-check form-check-inline">
	                  <input class="form-check-input" type="radio" name="gender" id="other" value="Others">
	                  <label class="form-check-label" for="other">Others</label>
	                </div>
	            </div>
	            <div class="form-group">
	                <div class="form-check form-check-inline">
	                  <input class="form-check-input" type="checkbox" name="hobby1" id="hobby1" value="Travelling">
	                  <label class="form-check-label" for="hobby1">Travelling</label>
	                </div>
	                <div class="form-check form-check-inline">
	                  <input class="form-check-input" type="checkbox" name="hobby2" id="hobby2" value="Reading">
	                  <label class="form-check-label" for="hobby2">Reading</label>
	                </div>
	                <div class="form-check form-check-inline">
	                  <input class="form-check-input" type="checkbox" name="hobby3" id="hobby3" value="Swimming">
	                  <label class="form-check-label" for="hobby3">Swimming</label>
	                </div>
	            </div>
	            <div class="form-group">
	                <label for="inputAddress">Address</label>
	                <input name="address" type="text" class="form-control" id="inputAddress" placeholder="1234 Main St">
	            </div>
	            <div class="custom-file">
	              <input type="file" class="custom-file-input" name="image" id="image">
	              <label class="custom-file-label" for="image">Choose file</label>
	            </div>
	        </form>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	        <button id="submitButtonNew" type="submit" class="btn btn-primary">Add User</button>
	      </div>
	    </div>
	  </div>
	</div>

	<!-- Modal For Edittind User Values-->
	<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLongTitle">Enter Your Details</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	            <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	        <form id="editDataForm" action="" method="POST" enctype="multipart/form-data">
	        	<div class="form-row">
	        		<div class="form-group">
	                	<input id="userId" name="userId" type="text" class="form-control" placeholder="userId" readonly>
	            	</div>
	        	</div>
	          <div class="form-row">
	            <div class="form-group col-md-6">
	                <input id="fnameNew" name="fname" type="text" class="form-control" placeholder="First name">
	            </div>
	            <div class="form-group col-md-6">
	                <input id="lnameNew" name="lname" type="text" class="form-control" placeholder="Last name">
	            </div>
	            </div>
	            <div class="form-group">
	                <label for="inputEmail4">Email</label>
	                <input id="emailNew" name="email" type="email" class="form-control" id="inputEmail4" placeholder="Email">
	            </div>
	            <div class="form-group">
	                <div class="form-check form-check-inline">
	                  <input class="form-check-input" type="radio" name="gender" id="maleNew" value="Male">
	                  <label class="form-check-label" for="male">Male</label>
	                </div>
	                <div class="form-check form-check-inline">
	                  <input class="form-check-input" type="radio" name="gender" id="femaleNew" value="Female">
	                  <label class="form-check-label" for="female">Female</label>
	                </div>
	                <div class="form-check form-check-inline">
	                  <input class="form-check-input" type="radio" name="gender" id="otherNew" value="Others">
	                  <label class="form-check-label" for="other">Others</label>
	                </div>
	            </div>
	            <div class="form-group">
	                <div class="form-check form-check-inline">
	                  <input class="form-check-input newHobby" type="checkbox" id="travellingNew" value="Travelling">
	                  <label class="form-check-label" for="travelling">Travelling</label>
	                </div>
	                <div class="form-check form-check-inline">
	                  <input class="form-check-input newHobby" type="checkbox" id="readingNew" value="Reading">
	                  <label class="form-check-label" for="reading">Reading</label>
	                </div>
	                <div class="form-check form-check-inline">
	                  <input class="form-check-input newHobby" type="checkbox" id="swimmingNew" value="Swimming">
	                  <label class="form-check-label" for="swimming">Swimming</label>
	                </div>
	            </div>
	            <div class="form-group">
	                <label for="inputAddress">Address</label>
	                <input name="address" type="text" class="form-control" id="inputAddressNew" placeholder="1234 Main St">
	            </div>
	            <div class="custom-file">
	              <input type="file" class="custom-file-input" name="image" id="imageNew">
	              <label class="custom-file-label" for="image">Choose file</label>
	            </div>
	        </form>
	      </div>
	      <div class="modal-footer">
	        <button onclick="closeEdit()" type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	        <button id="submitButtonEdit" type="submit" class="btn btn-primary">Update</button>
	      </div>
	    </div>
	  </div>
	</div>
</body>
</html>