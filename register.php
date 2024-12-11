<?php
// Bắt đầu phiên làm việc
session_start();

require_once "connect.php";

// Khởi tạo các biến
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter your username.";
    } else {
        $username = trim($_POST["username"]);
    }
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have at least 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm your password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Passwords do not match.";
        }
    }

    // Kiểm tra lỗi trước khi chèn vào cơ sở dữ liệu
    if (empty($username_err) && empty($password_err) && empty($confirm_password_err)) {
        // Chuẩn bị câu lệnh SELECT để kiểm tra username đã tồn tại chưa
        $sql = "SELECT id FROM users WHERE username = ?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            $param_username = $username;

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $username_err = "This username is already in use.";
                }
            } else {
                echo "An error has occurred. Please try again later.";
            }

            mysqli_stmt_close($stmt);
        }

        // Nếu không có lỗi, tiến hành chèn vào cơ sở dữ liệu
        if (empty($username_err) && empty($password_err) && empty($confirm_password_err)) {
            $sql = "INSERT INTO users (username, password) VALUES (?, ?)";

            if ($stmt = mysqli_prepare($conn, $sql)) {
                mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
                $param_username = $username;
                $param_password = $password;

                if (mysqli_stmt_execute($stmt)) {
                    header("Location: login.php");
                    exit();
                } else {
                    echo "An error has occurred. Please try again later.";
                }
                mysqli_stmt_close($stmt);
            }
        }
    }

    // Đóng kết nối
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <style>
        /* Reset some default styles */
        body,
        html {
            height: 100%;
            margin: 0;
            font-family: 'Poppins', sans-serif;
        }

        /* Background styling */
        body {
            background: #EDF1F7;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Card styling */
        .card {
            background: rgba(255, 255, 255, 0.95);
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

        /* Input field styling */
        .form-group>input {
            height: 45px;
            border-radius: 10px;
            padding: 10px 15px;
            border: 1px solid #ccc;
            transition: border-color 0.3s;
        }

        .form-group>input:focus {
            border-color: #2575fc;
            outline: none;
        }

        /* Error message styling */
        .text-danger {
            font-size: 0.875em;
            margin-top: 5px;
        }

        /* Button styling */
        .btn-success {
            background-color: #4154F1;
            border: none;
            border-radius: 10px;
            padding: 10px;
            font-size: 1em;
            transition: background-color 0.3s ease;
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
        <h2>Register</h2>
        <form method="post" action="">
            <!-- Username Field -->
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" class="form-control" id="username" placeholder="Enter username"
                    value="<?php echo htmlspecialchars($username); ?>">
                <span class="text-danger"><?php echo htmlspecialchars($username_err); ?></span>
            </div>

            <!-- Password Field -->
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" class="form-control" id="password" placeholder="Enter password">
                <span class="text-danger"><?php echo htmlspecialchars($password_err); ?></span>
            </div>

            <!-- Confirm Password Field -->
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" name="confirm_password" class="form-control" id="confirm_password"
                    placeholder="Confirm password">
                <span class="text-danger"><?php echo htmlspecialchars($confirm_password_err); ?></span>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-success btn-block">Register</button>
        </form>
    </div>

    <!-- jQuery and Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</body>

</html>