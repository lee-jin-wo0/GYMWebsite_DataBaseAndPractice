<?php
include('header.php');
include('db_connect.php');

$query = "
    SELECT 
        PT_CODE, 
        PRICE, 
        PT_TYPE, 
        T.NAME AS TRAINER_NAME
    FROM PT
    JOIN TRAINER T
    ON PT.TRAINER_ID = T.TRAINER_ID
    ORDER BY PT_CODE
";

$stmt = oci_parse($connect, $query);

if (!$stmt) {
    $e = oci_error($connect);
    echo "Query Parse Error: " . htmlentities($e['message']);
    exit;
}

if (!oci_execute($stmt)) {
    $e = oci_error($stmt);
    echo "Query Execution Error: " . htmlentities($e['message']);
    exit;
}

$pt_data = [];
while ($row = oci_fetch_assoc($stmt)) {
    $pt_data[] = $row;
}

oci_free_statement($stmt);
oci_close($connect);
?>

<main>
    <section class="pt-banner">
        <h1>Personal Training (PT)</h1>
        <p>Achieve your fitness goals with our professional trainers. Select from 1:1 or 2:1 personalized training programs.</p>
        <p>Our PT is based on 10 sessions.</p>
    </section>
    <section class="pt-options">
        <?php if (!empty($pt_data)): ?>
            <?php foreach ($pt_data as $pt): ?>
                <div class="pt-card">
                    <h2><?php echo htmlspecialchars($pt['PT_TYPE']); ?> PT</h2>
                    <p>10 Sessions: ₩<?php echo number_format($pt['PRICE'], 0); ?></p>
                    <p>Trainer: <?php echo htmlspecialchars($pt['TRAINER_NAME']); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No PT data available at this moment.</p>
        <?php endif; ?>
    </section>
</main>

<?php include('footer.php'); ?>

<style>
    .pt-banner {
        background-color: #333;
        color: white;
        text-align: center;
        padding: 40px 20px;
        width: 80%;
        margin: 20px auto;
    }
    .pt-banner h1 {
        font-size: 2.5rem;
        margin-bottom: 10px;
    }
    .pt-banner p {
        font-size: 1.2rem;
        color: #ddd;
    }
    .pt-options {
        display: flex;
        justify-content: center;
        gap: 20px;
        padding: 20px;
        flex-wrap: wrap;
    }
    .pt-card {
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 20px;
        text-align: center;
        width: 250px;
    }
    .pt-card h2 {
        font-size: 1.8rem;
        margin-top: auto;
        margin-bottom: 10px;
        color: #333;
    }
    .pt-card p {
        font-size: 1.1rem;
        color: #555;
        margin: 5px 0;
    }
</style>
<style>
    .pt-banner {
        background-color: #333;
        color: white;
        text-align: center;
        padding: 40px 20px;
        margin-bottom: 30px;
    }

    .pt-options {
        display: flex;
        justify-content: center;
        gap: 20px;
        padding: 20px;
        flex-wrap: wrap;
    }

    .pt-card {
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        width: 250px;
    }
</style>
