<?php
	include 'includes/session.php';

	if(isset($_POST['add'])){
		$firstname = $_POST['firstname'];
		$email = $_POST['email'];
		$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
		$filename = $_FILES['photo']['name'];
		if(!empty($filename)){
			move_uploaded_file($_FILES['photo']['tmp_name'], '../images/'.$filename);	
		}
		//generate voters id
		$set = '123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$voter = substr(str_shuffle($set), 0, 15);

		$sql = "INSERT INTO voters (voters_id, password, firstname, email, photo) VALUES ('$voter', '$password', '$firstname', '$email', '$filename')";
		if($conn->query($sql)){
			$_SESSION['success'] = 'Voter added successfully';

			//sent the vote id to the email
            $subject = "Email Verification Code";
            $message = "Your verification code is $voter";
            $sender = "From: assajean123@gmail.com";
            if(mail($email, $subject, $message, $sender)){
                $info = "We've sent a User ID code to your email - $email";
                $_SESSION['info'] = $info;
                $_SESSION['email'] = $email;
                header('location: https://djabbama-tech.com');
                exit();
            }else{
                echo "Failed while sending code!";
            }
        }else{
            $errors['db-error'] = "Failed while inserting data into database!";
        }
		}
		else{
			$_SESSION['error'] = $conn->error;
		}

	
	

	header('location: voters.php');
?>