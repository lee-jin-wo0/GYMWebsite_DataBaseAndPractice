<?php
include('header.php');
include('db_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $trainer_id = $_POST['username'];
    $trainer_pwd = $_POST['password'];
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $phone_number = $_POST['phone_prefix'] . '-' . $_POST['phone_middle'] . '-' . $_POST['phone_last'];
    $register_date = date('Y-m-d');
    try {
        $sql = "INSERT INTO TRAINER (TRAINER_ID, TRAINER_PWD, NAME, GENDER, TRAINER_PHONE_NUMBER, REGISTER_DATE)
                VALUES (:trainer_id, :trainer_pwd, :name, :gender, :phone_number, TO_DATE(:register_date, 'YYYY-MM-DD'))";
        $stmt = oci_parse($connect, $sql);
        oci_bind_by_name($stmt, ':trainer_id', $trainer_id);
        oci_bind_by_name($stmt, ':trainer_pwd', $trainer_pwd);
        oci_bind_by_name($stmt, ':name', $name);
        oci_bind_by_name($stmt, ':gender', $gender);
        oci_bind_by_name($stmt, ':phone_number', $phone_number);
        oci_bind_by_name($stmt, ':register_date', $register_date);
        if (oci_execute($stmt)) {
            echo "<script>alert('회원가입이 완료되었습니다.'); window.location.href = 'index.php';</script>";
        } else {
            $e = oci_error($stmt);
            echo "<script>alert('회원가입에 실패했습니다: " . htmlentities($e['message']) . "');</script>";
        }
        oci_free_statement($stmt);
        oci_close($connect);
    } catch (Exception $e) {
        echo "<script>alert('오류가 발생했습니다: " . $e->getMessage() . "');</script>";
    }
} else {
?>
<main>
    <h2>회원가입</h2>
    <form action="" method="post" class="register-form">
        <div class="form-group">
            <label>아이디 *</label>
            <input type="text" name="username" required>
        </div>

        <div class="form-group">
            <label>비밀번호 *</label>
            <input type="password" name="password" required>
        </div>

        <div class="form-group">
            <label>이름 *</label>
            <input type="text" name="name" required>
        </div>

        <div class="form-group">
            <label>휴대전화 *</label>
            <div class="phone-group">
                <select name="phone_prefix">
                    <option value="010">010</option>
                </select>
                <input type="text" name="phone_middle" maxlength="4" required>
                <input type="text" name="phone_last" maxlength="4" required>
            </div>
        </div>

        <div class="form-group">
            <label>성별 *</label>
            <select name="gender">
                <option value="M">남</option>
                <option value="F">여</option>
            </select>
        </div>

        <button type="submit">가입하기</button>
    </form>
</main>
<?php
}
include('footer.php');
?>
<style>
    .register-form {
        width: 100%;
        max-width: 300px;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .register-form .form-group {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .register-form input,
    .register-form select {
        width: 280px;
        padding: 10px;
        border: none;
        border-bottom: 2px solid #333;
        font-size: 14px;
        background: transparent;
        color: #333;
        box-sizing: content-box;
    }

    .register-form input.short-underline,
    .register-form select.short-underline {
        max-width: 180px;
        margin: 0 auto;
    }

    .register-form input:focus,
    .register-form select:focus {
        outline: none;
        border-bottom: 2px solid #000;
    }

    .register-form .phone-group {
        display: flex;
        gap: 5px;
        width: 100%;
    }

    .register-form .phone-group select,
    .register-form .phone-group input {
        width: calc(33.33% - 4px);
        padding: 10px;
        border: none;
        border-bottom: 2px solid #333;
        font-size: 14px;
        background: transparent;
        color: #333;
    }

    .register-form .phone-group input:focus,
    .register-form .phone-group select:focus {
        border-bottom: 2px solid #000;
    }

    .register-form button {
        width: 100%;
        background-color: #000;
        color: white;
        padding: 12px;
        border: none;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        margin-top: 10px;
        transition: background-color 0.3s ease;
    }

    .register-form button:hover {
        background-color: #444;
    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
        font-size: 18px;
    }
</style>


