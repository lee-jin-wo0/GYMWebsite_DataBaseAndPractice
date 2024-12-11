<?php
include('header.php');
include('db_connect.php');

$query = "
    SELECT 
        TRAINER_ID, 
        NAME, 
        GENDER, 
        TRAINER_PHONE_NUMBER
    FROM TRAINER
    WHERE TRAINER_ID != 'admin'
    ORDER BY NAME
";

$stmt = oci_parse($connect, $query);
if (!oci_execute($stmt)) {
    $e = oci_error($stmt);
    echo "<p>Query failed: " . htmlentities($e['message']) . "</p>";
    exit;
}

$trainers = [];
while ($row = oci_fetch_assoc($stmt)) {
    $trainers[] = $row;
}

oci_free_statement($stmt);
oci_close($connect);
?>

<main>
    <section class="trainer-banner">
        <h1>Meet Our Trainers</h1>
        <p>Our professional trainers are here to guide you every step of the way in your fitness journey.</p>
    </section>
    <section class="trainer-profiles">
        <?php if (!empty($trainers)): ?>
            <?php foreach ($trainers as $trainer): ?>
                <div class="trainer-card">
                    <h2><?php echo htmlspecialchars($trainer['NAME']); ?></h2>
                    <p>Gender: <?php echo htmlspecialchars($trainer['GENDER']); ?></p>
                    <p>Phone: <?php echo htmlspecialchars($trainer['TRAINER_PHONE_NUMBER']); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No trainers available at this moment.</p>
        <?php endif; ?>
    </section>
</main>

<?php include('footer.php'); ?>

<style>
.trainer-banner {
    background-color: #333;
    color: white;
    text-align: center;
    padding: 40px 20px;
    width: 80%;
    margin: 20px auto;
}

.trainer-banner h1 {
    font-size: 2.5rem;
    margin-bottom: 10px;
}

.trainer-banner p {
    font-size: 1.2rem;
    color: #ddd;
}
.trainer-profiles {
    display: flex;
    justify-content: center;
    gap: 20px;
    flex-wrap: wrap;
    padding: 20px;
}

.trainer-card {
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    padding: 20px;
    text-align: center;
    width: 250px;
}

.trainer-card h2 {
    font-size: 1.8rem;
    margin-bottom: 10px;
    color: #333;
}

.trainer-card p {
    font-size: 1.1rem;
    color: #555;
    margin: 5px 0;
}
</style>
