<?php
include('header.php');
include('db_connect.php');

$alert_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['register'])) {
        $member_id = $_POST['member_id'];
        $membership_code = $_POST['membership_code'];
        $locker_number = $_POST['locker_number'];
        $member_pwd = $_POST['member_pwd'];
        $name = $_POST['name'];
        $gender = $_POST['gender'];
        $member_phone_number = $_POST['member_phone_number'];
        $query = "
            INSERT INTO MEMBER (MEMBER_ID, MEMBERSHIP_CODE, LOCKER_NUMBER, MEMBER_PWD, NAME, GENDER, MEMBER_PHONE_NUMBER)
            VALUES (:member_id, :membership_code, :locker_number, :member_pwd, :name, :gender, :member_phone_number)
        ";
        $stmt = oci_parse($connect, $query);
        oci_bind_by_name($stmt, ':member_id', $member_id);
        oci_bind_by_name($stmt, ':membership_code', $membership_code);
        oci_bind_by_name($stmt, ':locker_number', $locker_number);
        oci_bind_by_name($stmt, ':member_pwd', $member_pwd);
        oci_bind_by_name($stmt, ':name', $name);
        oci_bind_by_name($stmt, ':gender', $gender);
        oci_bind_by_name($stmt, ':member_phone_number', $member_phone_number);
        if (oci_execute($stmt)) {
            $alert_message = '회원 등록이 성공적으로 완료되었습니다.';
        } else {
            $alert_message = '회원 등록 중 오류가 발생했습니다.';
        }
        oci_free_statement($stmt);
    }

    if (isset($_POST['delete'])) {
        $member_id = $_POST['delete_member_id'];
        $member_pwd = $_POST['delete_member_pwd'];

        $query = "
            DELETE FROM MEMBER
            WHERE MEMBER_ID = :member_id AND MEMBER_PWD = :member_pwd
        ";

        $stmt = oci_parse($connect, $query);

        oci_bind_by_name($stmt, ':member_id', $member_id);
        oci_bind_by_name($stmt, ':member_pwd', $member_pwd);

        if (oci_execute($stmt) && oci_num_rows($stmt) > 0) {
            $alert_message = '회원 탈퇴가 성공적으로 완료되었습니다.';
        } else {
            $alert_message = '회원 탈퇴 중 오류가 발생하거나 회원 정보가 잘못되었습니다.';
        }

        oci_free_statement($stmt);
    }
}

oci_close($connect);
?>

<?php if (!empty($alert_message)) : ?>
    <script>
        alert("<?php echo $alert_message; ?>");
    </script>
<?php endif; ?>

<main>
    <section class="member-management-banner">
        <h1>Member Management</h1>
        <p>Register new members or remove existing members from the system.</p>
    </section>

    <section class="member-registration">
    <h2>회원 등록</h2>
    <form method="post">
        <label for="member_id">아이디:</label>
        <input type="text" name="member_id" id="member_id" required>
        
        <label for="membership_code">멤버십 코드:</label>
        <input type="number" name="membership_code" id="membership_code" required min="0">
        
        <label for="locker_number">락커 번호:</label>
        <input type="number" name="locker_number" id="locker_number" min="0">
        
        <label for="member_pwd">비밀번호:</label>
        <input type="password" name="member_pwd" id="member_pwd" required>
        
        <label for="name">이름:</label>
        <input type="text" name="name" id="name" required>
        
        <label for="gender">성별:</label>
        <select name="gender" id="gender" required>
            <option value="M">남</option>
            <option value="F">여</option>
        </select>
        
        <label for="member_phone_number">전화번호:</label>
        <input type="text" name="member_phone_number" id="member_phone_number" required>

        <button type="submit" name="register">회원 등록</button>
    </form>
</section>


    <section class="member-deletion">
        <h2>회원 탈퇴</h2>
        <form method="post">
            <label for="delete_member_id">아이디:</label>
            <input type="text" name="delete_member_id" id="delete_member_id" required>
            
            <label for="delete_member_pwd">비밀번호:</label>
            <input type="password" name="delete_member_pwd" id="delete_member_pwd" required>

            <button type="submit" name="delete">회원 탈퇴</button>
        </form>
    </section>
</main>

<?php include('footer.php'); ?>

<style>
.member-management-banner {
    background-color: #333;
    color: white;
    text-align: center;
    padding: 40px 20px;
    width: 80%;
    margin: 20px auto;
}

.member-management-banner h1 {
    font-size: 2.5rem;
    margin-bottom: 10px;
}

.member-management-banner p {
    font-size: 1.2rem;
    color: #ddd;
}

form {
    width: 100%;
    max-width: 400px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 15px;
    background-color: #f9f9f9;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 20px;
}

label {
    font-weight: bold;
    color: #333;
    margin-bottom: 5px;
}

input, select {
    width: 100%;
    padding: 15px;
    border: none;
    border-bottom: 2px solid #333;
    font-size: 16px;
    background: transparent;
    color: #333;
    box-sizing: border-box;
}

input:focus, select:focus {
    outline: none;
    border-bottom: 2px solid #000;
}

button {
    width: 100%;
    padding: 15px;
    background-color: #000;
    color: white;
    border: none;
    cursor: pointer;
    font-size: 16px;
    font-weight: 500;
    margin-top: 5px;
}

button:hover {
    background-color: #555;
}

section {
    margin-bottom: 30px;
}

.member-registration h2, .member-deletion h2 {
    font-size: 1.8rem;
    text-align: center;
    color: #333;
    margin-bottom: 20px;
}

.member-registration form, .member-deletion form {
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

</style>
