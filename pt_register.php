<?php
session_start();
include('db_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $member_id = $_POST['member_id'];
    $pt_code = $_POST['pt_code'];
    $pt_count = $_POST['pt_count'];

    $code_query = "SELECT PT_CODE FROM DB501_PROJ_G1.PT WHERE PT_CODE = :pt_code";
    $code_stmt = oci_parse($connect, $code_query);
    oci_bind_by_name($code_stmt, ':pt_code', $pt_code);
    oci_execute($code_stmt);
    if (oci_fetch($code_stmt)) {
        $query = "
            INSERT INTO DB501_PROJ_G1.MEMBER_PT (MEMBER_ID, PT_CODE, PT_COUNT, REGISTER_DATE)
            VALUES (:member_id, :pt_code, :pt_count, SYSDATE)
        ";
        $stmt = oci_parse($connect, $query);
        $pt_count = $pt_count ?: 10;
        $pt_count = ceil($pt_count / 10) * 10;
        oci_bind_by_name($stmt, ':member_id', $member_id);
        oci_bind_by_name($stmt, ':pt_code', $pt_code);
        oci_bind_by_name($stmt, ':pt_count', $pt_count);
        if (oci_execute($stmt)) {
        } else {
        }
        oci_free_statement($stmt);
    } else {
        $error_message = "존재하지 않는 PT 코드입니다.";
    }

    oci_free_statement($code_stmt);
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PT 등록</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include('header.php'); ?>

<main>
    <section class="pt-register">
        <h2>PT 등록</h2>
        <?php if (!empty($success_message)): ?>
            <p class="success-message"><?php echo htmlspecialchars($success_message); ?></p>
        <?php elseif (!empty($error_message)): ?>
            <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>
        <form method="post" action="pt_register.php">
            <label for="member_id">Member ID:</label>
            <input type="text" id="member_id" name="member_id" required>

            <label for="pt_code">PT Code:</label>
            <input type="number" id="pt_code" name="pt_code" required min="1">

            <label for="pt_count">PT Count:</label>
            <input type="number" id="pt_count" name="pt_count" placeholder="기본값: 10">

            <button type="submit">PT Register</button>
        </form>
    </section>
</main>

<?php include('footer.php'); ?>
</body>
</html>

<style>
    .pt-register {
        width: 100%;
        max-width: 500px;
        margin: 0 auto;
        padding: 20px;
        text-align: center;
    }

    .pt-register h2 {
        margin-bottom: 20px;
    }

    .pt-register label {
        display: block;
        margin: 10px 0 5px;
        font-weight: bold;
    }

    .pt-register input {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .pt-register button {
        padding: 10px 20px;
        background-color: #000;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
</style>
