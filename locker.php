<?php
include('header.php');
include('db_connect.php');
$queryMembers = "
    SELECT MEMBER_ID, LOCKER_NUMBER, REGISTER_DATE
    FROM MEMBER
    WHERE LOCKER_NUMBER IS NOT NULL
";
$stmtMembers = oci_parse($connect, $queryMembers);

if (!oci_execute($stmtMembers)) {
    $e = oci_error($stmtMembers);
    echo "<p>Error fetching members: " . htmlspecialchars($e['message']) . "</p>";
    exit;
}
while ($member = oci_fetch_assoc($stmtMembers)) {
    $memberId = $member['MEMBER_ID'];
    $lockerNumber = $member['LOCKER_NUMBER'];
    $registerDate = $member['REGISTER_DATE'];
    $formattedRegisterDate = DateTime::createFromFormat('d-M-y', strtoupper($registerDate))->format('Y-m-d');
    $updateLocker = "
        UPDATE LOCKER
        SET MEMBER_ID = :member_id,
            START_DATE = TO_DATE(:register_date, 'YYYY-MM-DD'),
            AVAILABILITY = 'Y'
        WHERE LOCKER_NUMBER = :locker_number
          AND AVAILABILITY = 'N'
    ";
    $stmtUpdate = oci_parse($connect, $updateLocker);

    oci_bind_by_name($stmtUpdate, ':member_id', $memberId);
    oci_bind_by_name($stmtUpdate, ':register_date', $formattedRegisterDate);
    oci_bind_by_name($stmtUpdate, ':locker_number', $lockerNumber);

    if (!oci_execute($stmtUpdate)) {
        $e = oci_error($stmtUpdate);
        echo "<p>Error updating locker: " . htmlspecialchars($e['message']) . "</p>";
        exit;
    }
    oci_free_statement($stmtUpdate);
}

$clearLockers = "
    UPDATE LOCKER
    SET MEMBER_ID = NULL,
        START_DATE = NULL,
        END_DATE = NULL,
        AVAILABILITY = 'N'
    WHERE MEMBER_ID IS NULL
      OR MEMBER_ID NOT IN (SELECT MEMBER_ID FROM MEMBER)
";
$stmtClear = oci_parse($connect, $clearLockers);

if (!oci_execute($stmtClear)) {
    $e = oci_error($stmtClear);
    echo "<p>Error clearing lockers: " . htmlspecialchars($e['message']) . "</p>";
    exit;
}
oci_free_statement($stmtClear);

oci_commit($connect);

$queryLockers = "
    SELECT 
        L.LOCKER_NUMBER, 
        L.AVAILABILITY, 
        M.NAME AS MEMBER_NAME, 
        MEMB.MONTH AS MEMBERSHIP_MONTH,
        TO_CHAR(L.START_DATE, 'YYYY-MM-DD') AS START_DATE
    FROM LOCKER L
    LEFT JOIN MEMBER M ON L.MEMBER_ID = M.MEMBER_ID
    LEFT JOIN MEMBERSHIP MEMB ON M.MEMBERSHIP_CODE = MEMB.MEMBERSHIP_CODE
    ORDER BY L.LOCKER_NUMBER
";

$stmtLockers = oci_parse($connect, $queryLockers);
if (!oci_execute($stmtLockers)) {
    $e = oci_error($stmtLockers);
    echo "<p>Error executing query: " . htmlspecialchars($e['message']) . "</p>";
    exit;
}

$lockers = [];
while ($row = oci_fetch_assoc($stmtLockers)) {
    $lockers[] = $row;
}

oci_free_statement($stmtLockers);
oci_close($connect);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Locker Management</title>
    <style>
        .locker-banner {
            text-align: center;
            padding: 10px;
            margin: 20px auto;
        }

        .locker-banner h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        .locker-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 20px;
            padding: 20px;
        }

        .locker-box {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            padding: 20px;
            font-size: 1.2rem;
        }

        .locker-box h2 {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .member-name {
            font-size: 1.5rem;
            font-weight: bold;
            margin: 5px 0;
        }

        .membership-info {
            font-size: 1rem;
            color: #555;
            margin-bottom: 10px;
        }

        .start-date {
            font-size: 0.9rem;
            color: #777;
            margin: 0;
        }

        .locker-on {
            background-color: #d4f4d2;
            border-color: #a3e4a0;
        }

        .locker-off {
            background-color: #f4f4f4;
            border-color: #ddd;
        }
    </style>
</head>
<body>
    <main>
        <section class="locker-banner">
            <h1>Locker</h1>
        </section>
        <section class="locker-grid">
            <?php if (!empty($lockers)): ?>
                <?php foreach ($lockers as $locker): ?>
                    <div class="locker-box <?php echo $locker['AVAILABILITY'] == 'Y' ? 'locker-on' : 'locker-off'; ?>">
                        <h2><?php echo htmlspecialchars($locker['LOCKER_NUMBER']); ?></h2>
                        <?php if ($locker['AVAILABILITY'] == 'Y'): ?>
                            <p class="member-name"><?php echo htmlspecialchars($locker['MEMBER_NAME']); ?></p>
                            <p class="membership-info"><?php echo htmlspecialchars($locker['MEMBERSHIP_MONTH']); ?></p>
                            <p class="start-date"><?php echo htmlspecialchars($locker['START_DATE']); ?> ~</p>
                        <?php else: ?>
                            <p>Available</p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No lockers found.</p>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>

