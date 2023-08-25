<?php 

$name = $_POST['name'];
$email = $_POST['email'];
$subject = $_POST['subject'];
$message = $_POST['message'];

$mailheader = "From:" . $name . "<" . $email . ">\r\n";

$recipient = "niedola.liga@gmail.com";

mail($recipient, $subject, $message, $mailheader)
or die ("Error!");

echo'
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="assets/css/style.css"/>
        <title>Līga Niedola | Web Developer</title>
    </head>
    <body>
        <section class="contact" id="contact">
        <div>
            <h2>Thank you for contacting me!I will get back to you as soon as possible.</h2>
            <p>Go back to the <a href="https://liganiedola.000webhostapp.com">homepage</a></p>
        </div>
        </section>
    </body>
    </html>
'
?>