<?php
session_start();
include('header.php');
include('db_connect.php');

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}
$trainer_id = $_SESSION['username'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $phone_number = $_POST['phone_number'];
    $password = $_POST['password'];
    $update_query = "
        UPDATE TRAINER
        SET NAME = :name,
            GENDER = :gender,
            TRAINER_PHONE_NUMBER = :phone_number,
            TRAINER_PWD = :password
        WHERE TRAINER_ID = :trainer_id
    ";
    $update_stmt = oci_parse($connect, $update_query);
    oci_bind_by_name($update_stmt, ':name', $name);
    oci_bind_by_name($update_stmt, ':gender', $gender);
    oci_bind_by_name($update_stmt, ':phone_number', $phone_number);
    oci_bind_by_name($update_stmt, ':password', $password);
    oci_bind_by_name($update_stmt, ':trainer_id', $trainer_id);
    if (oci_execute($update_stmt, OCI_COMMIT_ON_SUCCESS)) {
        echo "<script>alert('프로필이 성공적으로 업데이트되었습니다.');</script>";
        header("Refresh:0");
    } else {
        $error = oci_error($update_stmt);
        echo "SQL Error: " . $error['message'];
    }
}
$query = "
    SELECT 
        TRAINER_ID, 
        NAME, 
        GENDER, 
        TRAINER_PHONE_NUMBER, 
        TO_CHAR(REGISTER_DATE, 'YYYY-MM-DD') AS REGISTER_DATE
    FROM TRAINER
    WHERE TRAINER_ID = :trainer_id
";
$stmt = oci_parse($connect, $query);
oci_bind_by_name($stmt, ":trainer_id", $trainer_id);
oci_execute($stmt);
$trainer = oci_fetch_assoc($stmt);
if (!$trainer) {
    echo "사용자 정보를 찾을 수 없습니다.";
    exit();
}
oci_free_statement($stmt);
oci_close($connect);
?>

<main>
    <section class="trainer-info">
        <h1>Welcome, <?php echo htmlspecialchars($trainer['NAME']); ?>!</h1>
        <p>Here is your profile information:</p>

        <form method="post">
            <table>
                <tr>
                    <th>Trainer ID</th>
                    <td><?php echo htmlspecialchars($trainer['TRAINER_ID']); ?></td>
                </tr>
                <tr>
                    <th>Name</th>
                    <td>
                        <input type="text" name="name" value="<?php echo htmlspecialchars($trainer['NAME']); ?>" required>
                    </td>
                </tr>
                <tr>
                    <th>Gender</th>
                    <td>
                        <select name="gender" required>
                            <option value="M" <?php echo $trainer['GENDER'] === 'M' ? 'selected' : ''; ?>>Male</option>
                            <option value="F" <?php echo $trainer['GENDER'] === 'F' ? 'selected' : ''; ?>>Female</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>Phone Number</th>
                    <td>
                        <input type="text" name="phone_number" value="<?php echo htmlspecialchars($trainer['TRAINER_PHONE_NUMBER']); ?>" required>
                    </td>
                </tr>
                <tr>
                    <th>Password</th>
                    <td>
                        <input type="password" name="password" placeholder="Enter new password" required>
                    </td>
                </tr>
                <tr>
                    <th>Register Date</th>
                    <td><?php echo htmlspecialchars($trainer['REGISTER_DATE']); ?></td>
                </tr>
            </table>
            <div class="buttons">
                <a href="logout.php" class="logout-button">Logout</a>
                <button type="submit" name="update_profile" class="update-button">Update Profile</button>
            </div>
        </form>
    </section>
</main>

<?php include('footer.php'); ?>
<style>
    .trainer-info {
    text-align: center;
    margin: 50px auto;
    padding: 20px;
    background-color: #f4f4f4;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    max-width: 500px;
}

.trainer-info h1 {
    font-size: 2rem;
    margin-bottom: 15px;
    color: #333;
}

.trainer-info p {
    font-size: 1rem;
    color: #555;
    margin-bottom: 20px;
}

.trainer-info table {
    width: 100%;
    margin: 0 auto;
    border-collapse: collapse;
}

.trainer-info table th,
.trainer-info table td {
    text-align: left;
    padding: 10px;
    border-bottom: 1px solid #ddd;
}

.trainer-info table th {
    color: #555;
    font-weight: bold;
}

.trainer-info table td input,
.trainer-info table td select {
    width: 100%;
    padding: 5px;
    font-size: 1rem;
}

.buttons {
    margin-top: 20px;
}

.logout-button,
.update-button {
    display: inline-block;
    padding: 10px 20px;
    background-color: #000;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    font-size: 1rem;
    margin: 0 10px;
    border: none;
    cursor: pointer;
}

.update-button:hover {
    background-color: #555;
}

.logout-button:hover {
    background-color: #555;
}
</style>
