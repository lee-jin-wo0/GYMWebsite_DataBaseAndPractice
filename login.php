<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include('db_connect.php');

    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT TRAINER_ID, NAME FROM TRAINER WHERE TRAINER_ID = :username AND TRAINER_PWD = :password";
    $stmt = oci_parse($connect, $sql);

    oci_bind_by_name($stmt, ":username", $username);
    oci_bind_by_name($stmt, ":password", $password);

    oci_execute($stmt);

    $row = oci_fetch_assoc($stmt);
    if ($row) {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $row['TRAINER_ID'];
        $_SESSION['name'] = $row['NAME'];
        header("Location: index.php");
        exit();
    } else {
        $error_message = "로그인 실패: 아이디 또는 비밀번호를 확인하세요.";
    }

    oci_free_statement($stmt);
    oci_close($connect);
}
?>


<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>로그인 - 홍익대학교 헬스장</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include('header.php'); ?>
    <main>
        <?php if (!empty($error_message)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>
        <form action="login.php" method="post" class="login-form">
            <div class="input-field">
                <input type="text" id="username" name="username" placeholder="ID" required>
            </div>
            <div class="input-field">
                <input type="password" id="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit">로그인</button>
            <a href="register.php" class="register-button">회원가입</a>
        </form>
    </main>
    <?php include('footer.php'); ?>
</body>
</html>


<style>
    .announcement-bar, .sub-announcement-bar {
        background-color: #333;
        color: white;
        text-align: center;
        padding: 5px 0;
        font-size: 0.9rem;
    }

    .header-main {
        background-color: #000;
        color: white;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 20px;
        border-top: 1px solid white;
        border-bottom: 1px solid white;
    }

    .logo img {
        max-height: 80px;
    }

    .main-nav {
        flex: 1;
        display: flex;
        justify-content: center;
        gap: 30px;
    }

    .main-nav a {
        color: white;
        text-decoration: none;
        font-size: 1rem;
        text-transform: uppercase;
    }

    .main-nav a:hover {
        text-decoration: underline;
    }

    .user-actions {
        text-align: right;
    }

    .user-actions a {
        color: white;
        text-decoration: none;
        margin-left: 15px;
        font-size: 1rem;
    }

    .user-actions a:hover {
        text-decoration: underline;
    }

    .login-form {
        width: 100%;
        max-width: 300px;
        margin: 50px auto;
        display: flex;
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }

    .login-form input {
        width: 100%;
        padding: 10px;
        border: none;
        border-bottom: 1px solid #aaa;
        font-size: 14px;
        background: transparent;
        color: #333;
    }

    .login-form input::placeholder {
        color: #aaa;
        font-size: 14px;
    }

    .login-form input:focus {
        outline: none;
        border-bottom: 1px solid #000;
    }

    .login-form button {
        width: 107%;
        background-color: #000;
        color: white;
        padding: 10px 0;
        border: none;
        cursor: pointer;
        font-size: 14px;
        font-weight: bold;
        margin-top: 10px;
x    }

    .login-form button:hover {
        background-color: #555;
    }

    .register-button {
        display: inline-block;
        text-align: center;
        width: 107%;
        padding: 10px 0;
        border: 1px solid #000;
        color: #000;
        font-size: 14px;
        font-weight: bold;
        text-decoration: none;
        margin-top: 10px;
    }

    .register-button:hover {
        background-color: #f4f4f4;
    }
</style>
