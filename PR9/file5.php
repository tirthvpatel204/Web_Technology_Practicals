<?php

$conn = new mysqli("localhost", "root", "", "newcollege");

if ($conn->connect_error)
{
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['register']))
{
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Backend validation
    if ($username == "" || $email == "" || $password == "")
    {
        echo "All fields are required.";
    }
    else
    {
        // Check duplicate username/email
        $check = $conn->query(
            "SELECT * FROM users
             WHERE username='$username' OR email='$email'"
        );

        if ($check->num_rows > 0)
        {
            echo "Username or Email already exists.";
        }
        else
        {
            // Hash password
            $password = password_hash($password, PASSWORD_DEFAULT);

            // Insert user
            $sql = "INSERT INTO users(username, email, password)
                    VALUES('$username', '$email', '$password')";

            if ($conn->query($sql))
            {
                echo "Registration successful.";
            }
            else
            {
                echo "Registration failed.";
            }
        }
    }
}

?>

<form method="post">

    Username:<input type="text" name="username" required>
    <br><br>

    Email:<input type="email" name="email" required>
    <br><br>

    Password:<input type="password" name="password" required minlength="6">
    <br><br>

    <button type="submit" name="register">
     Register
    </button>

</form>