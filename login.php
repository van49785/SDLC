<?php
include "connect.php";

$username = $password = "";
$username_err = $password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter username.";
    } else {
        $username = trim($_POST["username"]);
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty($username_err) && empty($password_err)) {
        $sql = "SELECT id, username, password FROM users WHERE username = ?";

        $stmt = mysqli_prepare($conn, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            $param_username = $username;

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    mysqli_stmt_bind_result($stmt, $id, $username, $password_db);
                    if (mysqli_stmt_fetch($stmt)) {
                        if ($password == $password_db) {
                            header("location: main.php");
                            exit();
                        } else {
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else {
                    $username_err = "No account found with that username.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            mysqli_stmt_close($stmt);
        } else {
            echo "Failed to prepare the SQL statement.";
        }
    }
}

mysqli_close($conn);
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign In</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body,
        html {
            height: 100%;
            margin: 0;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #EDF1F7;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            background: rgba(255, 255, 255, 0.85);
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.25);
            width: 100%;
            max-width: 400px;
            padding: 30px;
        }

        .card h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }

        .form-floating>.form-control {
            height: 50px;
            border-radius: 10px;
            padding: 15px;
        }

        .form-floating>label {
            color: #555;
        }

        .text-danger {
            font-size: 0.875em;
            margin-top: 5px;
        }

        .remember-forgot-box {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
            margin-bottom: 25px;
        }

        .remember-me label {
            margin-left: 5px;
            color: #555;
        }

        .forgot-password {
            color: #2575fc;
            text-decoration: none;
            font-size: 0.875em;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }

        /* Button styling */
        .btn-primary {
            background-color: #4154F1;
            border: none;
            border-radius: 10px;
            padding: 10px;
            font-size: 1em;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #6a11cb;
        }

        /* Responsive adjustments */
        @media (max-width: 576px) {
            .card {
                padding: 20px;
                margin: 10px;
            }
        }
    </style>
</head>

<body>
    <div class="card">
        <h2>Sign In</h2>
        <form method="post" action="">
            <!-- Username Field -->
            <div class="form-floating mb-3">
                <input type="text" name="username" class="form-control" id="floatingInput"
                    placeholder="Username" value="<?php echo htmlspecialchars($username); ?>">
                <label for="floatingInput">Username</label>
                <span class="text-danger"><?php echo htmlspecialchars($username_err); ?></span>
            </div>

            <!-- Password Field -->
            <div class="form-floating mb-3">
                <input type="password" name="password" class="form-control" id="floatingPassword"
                    placeholder="Password">
                <label for="floatingPassword">Password</label>
                <span class="text-danger"><?php echo htmlspecialchars($password_err); ?></span>
            </div>

            <!-- Remember Me and Forgot Password -->
            <div class="remember-forgot-box">
                <div class="form-check remember-me">
                    <input class="form-check-input" type="checkbox" name="remember-me" id="remember-me">
                    <label class="form-check-label" for="remember-me">Remember me</label>
                </div>
                <a class="forgot-password" href="#">Forgot password?</a>
            </div>

            <!-- Submit Button -->
            <button class="btn btn-primary w-100" type="submit">Sign In</button>

        </form>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>