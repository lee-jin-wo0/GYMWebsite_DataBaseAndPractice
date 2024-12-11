<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>홍익대학교 헬스장</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="announcement-bar">
        <span>We're going to join us now and receive special benefits.</span>
    </div>
    <header class="header-main">
        <div class="logo">
            <a href="index.php"><img src="./img/logo.png" alt="홍익대학교 헬스장 로고"></a>
        </div>
        <nav class="main-nav">
            <a href="membership.php">Membership</a>
            <a href="pt.php">PT</a>
            <a href="trainer.php">Trainer</a>
            
            <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                <a href="member_management.php">Member_management</a>
                <a href="member_search.php">Member_search</a>
                <a href="pt_register.php">Pt_register</a>
                <a href="pt_management.php">pt_management</a>
                <a href="locker.php">Locker</a>
            <?php endif; ?>
        </nav>
        <div class="user-actions">
            <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                <div class="welcome-message"></span><span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>님!</span></div>
                <div class="links">
                    <a href="my_page.php">마이페이지</a>
                    <a href="logout.php">로그아웃</a>
                </div>
            <?php else: ?>
                <a href="login.php">로그인</a>
                <a href="register.php">회원가입</a>
            <?php endif; ?>
        </div>
    </header>
    <div class="sub-announcement-bar">
        <span>The best choice for your healthy lifestyle, Hong Ik University gym.</span>
    </div>
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
    display: flex;
    flex-direction: column;
    align-items: flex-end;
}

.user-actions a {
    color: white;
    text-decoration: none;
    font-size: 1rem;
    background-color: #000;
}

.user-actions a:hover {
    background-color: white;
    color: black;
}

</style>
