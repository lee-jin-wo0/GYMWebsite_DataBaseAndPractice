<?php include('header.php'); ?>
<main>
    <section class="banner">
        <img src="./img/banner.png" alt="홍익대학교 헬스장">
    </section>
    <section class="welcome">
        <h2>Welcome to Hongik University's gym!</h2>
        <p>Hong Ik University Health Center provides the best exercise environment with the latest equipment and professional trainer.</p>
    </section>
    <section class="services">
        <h3>Our service</h3>
        <ul>
            <li>the latest cardio equipment</li>
            <li>professional training equipment</li>
            <li>a comfortable latte room</li>
            <li>a group exercise space</li>
        </ul>
    </section>
</main>
<?php include('footer.php'); ?>

<style>
    .banner img {
        width: 100%;
        height: 400px;
        margin-top: 30px;
        object-fit: cover;
    }

    .welcome p {
        text-align: center;
    }

    .welcome h2, .services h3 {
        text-align: center;
        margin: 20px 0;
    }

    .services ul {
        list-style-type: disc;
        padding-left: 20px;
        margin: 0 auto;
        max-width: 300px;
    }

    .services ul li {
        margin: 10px 0;
        text-align: center;
    }
</style>
