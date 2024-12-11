<?php
include('header.php');
include('db_connect.php');

$query = "
SELECT 
    CASE 
        WHEN \"MONTH\" LIKE '%1개월%' THEN '1 Month'
        WHEN \"MONTH\" LIKE '%3개월%' THEN '3 Months'
        WHEN \"MONTH\" LIKE '%6개월%' THEN '6 Months'
        WHEN \"MONTH\" LIKE '%12개월%' THEN '12 Months'
    END AS Month,
    CASE 
        WHEN \"MONTH\" LIKE '%학생%' THEN 'Student(학생)'
        WHEN \"MONTH\" LIKE '%교직원%' THEN 'Faculty(교직원)'
        WHEN \"MONTH\" LIKE '%평교생%' THEN 'Regular Member(평교생)'
    END AS MembershipType,
    PRICE
FROM MEMBERSHIP
ORDER BY 
    CASE 
        WHEN \"MONTH\" LIKE '%1개월%' THEN 1
        WHEN \"MONTH\" LIKE '%3개월%' THEN 2
        WHEN \"MONTH\" LIKE '%6개월%' THEN 3
        WHEN \"MONTH\" LIKE '%12개월%' THEN 4
    END,
    MembershipType
";

$stmt = oci_parse($connect, $query);

if (!oci_execute($stmt)) {
    $e = oci_error($stmt);
    die("Query failed: " . htmlentities($e['message']));
}

$data = [];
while ($row = oci_fetch_assoc($stmt)) {
    $data[$row['MEMBERSHIPTYPE']][$row['MONTH']] = $row['PRICE'];
}

oci_free_statement($stmt);
oci_close($connect);
?>

<main>
    <section class="membership-banner">
        <div class="banner-content">
            <h1>Membership Options</h1>
            <p>Choose the best membership plan that fits your needs and enjoy exclusive benefits at Hongik Fitness Center.</p>
        </div>
    </section>

    <section class="membership-table">
        <table>
            <thead>
                <tr>
                    <th>Membership Type</th>
                    <th>1 Month</th>
                    <th>3 Months</th>
                    <th>6 Months</th>
                    <th>12 Months</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($data)): ?>
                    <?php foreach ($data as $membershipType => $plans): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($membershipType); ?></td>
                            <td><?php echo isset($plans['1 Month']) ? '₩' . number_format($plans['1 Month'], 0) : '-'; ?></td>
                            <td><?php echo isset($plans['3 Months']) ? '₩' . number_format($plans['3 Months'], 0) : '-'; ?></td>
                            <td><?php echo isset($plans['6 Months']) ? '₩' . number_format($plans['6 Months'], 0) : '-'; ?></td>
                            <td><?php echo isset($plans['12 Months']) ? '₩' . number_format($plans['12 Months'], 0) : '-'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No membership data found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
</main>

<?php include('footer.php'); ?>

<style>
    .membership-banner {
        background-color: #333;
        color: white;
        text-align: center;
        padding: 40px 20px;
        width: 80%;
        margin: 20px auto;
    }

    .banner-content {
        margin: 0 auto;
        max-width: auto;
    }

    .banner-content h1 {
        font-size: 2.5rem;
        margin-bottom: 10px;
    }

    .banner-content p {
        font-size: 1.2rem;
        color: #ddd;
    }

    .membership-table {
       
        padding: 40px 20px;
    }

    .membership-table table {
        width: 90%;
        margin: 0 auto;
        border-collapse: collapse;
    }

    .membership-table table th {
        background-color: black;
        color: white;
    }

    .membership-table table td,
    .membership-table table th {
        padding: 12px;
        text-align: center;
        border: 1px solid #ddd;
    }

    .membership-table table tr:nth-child(even) {
        background-color: #f9f9f9;
    }
</style>
