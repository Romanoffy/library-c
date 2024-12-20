<?php
if (!defined('SECURE_ACCESS')) {
    die('Direct access not permitterd');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link rel="stylesheet" href="views/style-home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <section>

        <nav>

            <div class="logo">
                <img src="image/logo.png">
            </div>

            <ul>
                <li><a href="#Home">Home</a></li>
                <li><a href="#About">About</a></li>
                <li><a href="#Featured">Featured</a></li>
                <li><a href="#Arrivals">Arrivals</a></li>
                <li><a href="#Reviews">Reviews</a></li>
                <li><a href="#Blog">Blog</a></li>
            </ul>

            <div class="social_icon">
            <button class="log"><a href="/login">Login</a></button>
            </div>

        </nav>

        <div class="main">

            <div class="main_tag">
                <h1>WELCOME<br><span></span></h1>

                <p>
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Assumenda molestias atque laborum 
                    non fuga ex deserunt. Exercitationem velit ducimus praesentium, obcaecati hic voluptate id 
                    tenetur fuga illum quidem omnis? Rerum?
                </p>
                <a href="/book" class="main_btn">Learn More</a>

            </div>

            <div class="main_img">
                <img src="image/table.png">
            </div>

        </div>

    </section>




    <!--Services-->

    <div class="services">

        <div class="services_box">

           >

            <div class="services_card">
                <i class="fa-solid fa-headset"></i>
                <h3>24 x 7 Services</h3>
                <p>
                    Lorem ipsum dolor, sit amet consectetur adipisicing elit. 
                </p>
            </div>


            <div class="services_card">
                <i class="fa-solid fa-lock"></i>
                <h3>Secure</h3>
                <p>
                    Lorem ipsum dolor, sit amet consectetur adipisicing elit. 
                </p>
            </div>

        </div>

    </div>




    <!--About-->

    <div class="about">

        <div class="about_image">
            <img src="image/about.png">
        </div>
        <div class="about_tag">
            <h1>About Us</h1>
            <p>
                Lorem ipsum, dolor sit amet consectetur adipisicing elit. Beatae cumque atque dolor corporis 
                architecto. Voluptate expedita molestias maxime officia natus consectetur dolor quisquam illo? 
                Quis illum nostrum perspiciatis laboriosam perferendis? Lorem ipsum dolor sit amet consectetur 
                adipisicing elit. Minus ad eius saepe architecto aperiam laboriosam voluptas nobis voluptates 
                id amet eos repellat corrupti harum consectetur, dolorum dolore blanditiis quam quo.
            </p>
            <a href="#" class="about_btn">Learn More</a>
        </div>

    </div>








</body>
</html>