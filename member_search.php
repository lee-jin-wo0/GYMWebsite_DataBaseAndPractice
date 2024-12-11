<?php
include('header.php');
include('db_connect.php');
$query = "
    SELECT 
        M.MEMBER_ID,
        M.NAME AS MEMBER_NAME,
        M.MEMBER_PHONE_NUMBER,
        M.GENDER,
        M.REGISTER_DATE,
        CASE 
            WHEN M.MEMBERSHIP_CODE IS NULL THEN 'No Membership'
            ELSE (SELECT MEMBERSHIP.MONTH FROM MEMBERSHIP WHERE MEMBERSHIP.MEMBERSHIP_CODE = M.MEMBERSHIP_CODE)
        END AS MEMBERSHIP_TYPE
    FROM MEMBER M
    WHERE 1=1
";
$search_keyword = '';
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_keyword = $_GET['search'];
    $query .= " AND (M.MEMBER_ID LIKE '%" . htmlspecialchars($search_keyword) . "%' 
                OR M.NAME LIKE '%" . htmlspecialchars($search_keyword) . "%')";
}

$query .= " ORDER BY M.REGISTER_DATE DESC";
$stmt = oci_parse($connect, $query);
oci_execute($stmt);
$members = [];
while ($row = oci_fetch_assoc($stmt)) {
    $members[] = $row;
}
oci_free_statement($stmt);
oci_close($connect);
?>

<main>
    <section class="search-section">
        <h1>Member Search</h1>
        <form action="member_search.php" method="GET">
            <input type="text" name="search" placeholder="Search by Member ID or Name" value="<?php echo htmlspecialchars($search_keyword); ?>">
            <button type="submit">Search</button>
        </form>
    </section>
    <section class="results-section">
        <table>
            <thead>
                <tr>
                    <th>Member ID</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Gender</th>
                    <th>Membership</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($members)): ?>
                    <?php foreach ($members as $member): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($member['MEMBER_ID']); ?></td>
                            <td><?php echo htmlspecialchars($member['MEMBER_NAME']); ?></td>
                            <td><?php echo htmlspecialchars($member['MEMBER_PHONE_NUMBER']); ?></td>
                            <td><?php echo htmlspecialchars($member['GENDER']); ?></td>
                            <td><?php echo htmlspecialchars($member['MEMBERSHIP_TYPE']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No results found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
</main>

<?php include('footer.php'); ?>


<style>
    .search-section {
        text-align: center;
        margin-bottom: 20px;
    }

    .search-section h1 {
        font-size: 2rem;
        margin-bottom: 10px;
    }

    .search-section form {
        display: inline-block;
        margin-top: 10px;
    }

    .search-section input[type="text"] {
        padding: 10px;
        font-size: 1rem;
        border: 1px solid #ccc;
        border-radius: 5px;
        width: 250px;
    }

    .search-section button {
        padding: 10px 20px;
        font-size: 1rem;
        color: white;
        background-color: black;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .search-section button:hover {
        background-color: #555;
    }

    .results-section table {
        width: 90%;
        margin: 0 auto;
        border-collapse: collapse;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .results-section table thead {
        background-color: black;
        color: white;
    }

    .results-section table th,
    .results-section table td {
        padding: 12px 15px;
        text-align: center;
        border: 1px solid #ddd;
    }

    .results-section table tbody tr:hover {
        background-color: #f1f1f1;
    }

    .results-section table tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .results-section table td[colspan="5"] {
        text-align: center;
        color: #555;
        font-style: italic;
    }
</style>
