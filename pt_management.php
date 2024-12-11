<?php
session_start();
include('db_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['decrement'])) {
    $member_id = $_POST['member_id'];
    $pt_code = $_POST['pt_code'];

    $decrement_query = "
        UPDATE DB501_PROJ_G1.MEMBER_PT
        SET PT_COUNT = PT_COUNT - 1
        WHERE MEMBER_ID = :member_id AND PT_CODE = :pt_code AND PT_COUNT > 0
    ";

    $stmt = oci_parse($connect, $decrement_query);
    oci_bind_by_name($stmt, ':member_id', $member_id);
    oci_bind_by_name($stmt, ':pt_code', $pt_code);

    if (oci_execute($stmt)) {
    } else {
    }

    oci_free_statement($stmt);
}

$search = $_GET['search'] ?? '';
$search_query = '';

if (!empty($search)) {
    $search_query = "AND (
        MP.MEMBER_ID LIKE '%' || :search || '%' OR
        M.NAME LIKE '%' || :search || '%' OR
        TO_CHAR(MP.PT_CODE) LIKE '%' || :search || '%'
    )";
}

$query = "
    SELECT 
        MP.MEMBER_ID, 
        M.NAME AS MEMBER_NAME, 
        MP.PT_CODE, 
        PT.PT_TYPE AS PT_NAME, 
        MP.PT_COUNT, 
        TO_CHAR(MP.REGISTER_DATE, 'YYYY-MM-DD') AS REGISTER_DATE
    FROM DB501_PROJ_G1.MEMBER_PT MP
    JOIN DB501_PROJ_G1.MEMBER M ON MP.MEMBER_ID = M.MEMBER_ID
    JOIN DB501_PROJ_G1.PT PT ON MP.PT_CODE = PT.PT_CODE
    WHERE 1=1 $search_query
    ORDER BY MP.REGISTER_DATE DESC
";

$stmt = oci_parse($connect, $query);

if (!empty($search)) {
    oci_bind_by_name($stmt, ':search', $search);
}

oci_execute($stmt);

$pt_records = [];
while ($row = oci_fetch_assoc($stmt)) {
    $pt_records[] = $row;
}

oci_free_statement($stmt);
oci_close($connect);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PT 관리</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php include('header.php'); ?>

<main>
    <section class="pt-records">
        <h2>PT 관리</h2>
        <?php if (!empty($success_message)): ?>
            <p class="success-message"><?php echo htmlspecialchars($success_message); ?></p>
        <?php elseif (!empty($error_message)): ?>
            <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>

        <form method="get" class="search-form">
            <input
                type="text"
                name="search"
                placeholder="Search by Member ID, Name"
                value="<?php echo htmlspecialchars($search); ?>"
            />
            <button type="submit">Search</button>
        </form>
        <?php if (!empty($pt_records)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Member ID</th>
                        <th>Member Name</th>
                        <th>PT Code</th>
                        <th>PT Name</th>
                        <th>PT Count</th>
                        <th>Register Date</th>
                        <th>Work</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pt_records as $record): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($record['MEMBER_ID']); ?></td>
                            <td><?php echo htmlspecialchars($record['MEMBER_NAME']); ?></td>
                            <td><?php echo htmlspecialchars($record['PT_CODE']); ?></td>
                            <td><?php echo htmlspecialchars($record['PT_NAME']); ?></td>
                            <td><?php echo htmlspecialchars($record['PT_COUNT']); ?></td>
                            <td><?php echo htmlspecialchars($record['REGISTER_DATE']); ?></td>
                            <td>
                                <?php if ($record['PT_COUNT'] > 0): ?>
                                    <form method="post" style="display: inline-block;">
                                        <input type="hidden" name="member_id" value="<?php echo htmlspecialchars($record['MEMBER_ID']); ?>">
                                        <input type="hidden" name="pt_code" value="<?php echo htmlspecialchars($record['PT_CODE']); ?>">
                                        <button type="submit" name="decrement" class="decrement-button">exercise</button>
                                    </form>
                                <?php else: ?>
                                    <span>횟수 없음</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>검색 결과가 없습니다.</p>
        <?php endif; ?>
    </section>
</main>

<?php include('footer.php'); ?>
</body>
</html>

<style>
    .pt-records {
        width: 100%;
        max-width: 800px;
        margin: 40px auto;
        padding: 20px;
    }

    .pt-records h2 {
        text-align: center;
        margin-bottom: 20px;
    }

    .search-form {
        text-align: center;
        margin-bottom: 20px;
    }

    .search-form input {
        width: 60%;
        max-width: 400px;
        padding: 10px;
        font-size: 1rem;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .search-form button {
        padding: 10px 20px;
        background-color: #000;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .pt-records table {
        width: 100%;
        border-collapse: collapse;
        text-align: center;
    }

    .pt-records th, .pt-records td {
        border: 1px solid #ddd;
        padding: 10px;
    }

    .pt-records th {
        background-color: #333;
        color: white;
    }

    .decrement-button {
        padding: 5px 10px;
        background-color: red;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
</style>
