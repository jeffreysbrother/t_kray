<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = trim($_POST["name"]);
  $email = trim($_POST["email"]);
  $phone = trim($_POST["phone"]);
  $message = trim($_POST["message"]);

  if ($name == "" OR $email == "" OR $message == ""){
  $error_message = "You must first specify a value for the NAME, EMAIL, and MESSAGE fields.";
  }

  if (isset($error_message)) {
    foreach( $_POST as $value ){
      if(stripos($value, 'Content-Type:') !== FALSE ){
        $error_message = "There was a problem with the information submitted.";
      }
    }
  }

  if (!isset($error_message) && $_POST["address"] != "") {
  $error_message = "Your form submission has an error.";
  }

  require_once("inc/class.phpmailer.php");
  $mail = new PHPMailer();

  if (!isset($error_message) && !$mail->ValidateAddress($email)){
  $error_message = "You must specify a valid email address.";
  }

  if (!isset($error_message)) {
      $email_body = "";
      $email_body = $email_body . "Name: " . $name . "<br />";
      $email_body = $email_body . "Email: " . $email . "<br />";
      $email_body = $email_body . "Message: " . $message;

      $mail->SetFrom($email, $name);
      $address = "tkray@planarform.com";
      $mail->AddAddress($address, "Tim Kray");
      $mail->Subject = "Tim Kray Contact Form Submission | " . $name;
      $mail->MsgHTML($email_body);

      if($mail->Send()){
          header("Location: contact.php?status=thanks");
          exit;
      } else {
        $error_message = "There was a problem sending the email: " . $mail->ErrorInfo;
      }
    }
}
?>


<?php
$pageTitle = "contact.php";
include '../inc/head.php';
?>
    <!-- <div id="top_spacer"></div> -->
    <div id="title">
      <h2 class="first">CONTACT</h2>
    </div>
    <div class="bottom_spacer"></div>


    <?php
if (isset($_GET["status"]) AND $_GET["status"] == "thanks") { ?>
        <h1 class="thanks">Thanks for the email!</h1>
<?php } else { ?>

            <?php
              if (isset($error_message)) {
                echo '<p class="message">' . $error_message . '</p>';
              }
            ?>



    <div class="content1">
      <form class="contact_form" method="post" action="contact.php">
                        <p>
                            <input class="form1" type="text" name="name" id="name" placeholder="name" value="<?php if (isset($name)) { echo htmlspecialchars($name); }?>">
                        </p>

                        <p>
                            <input class="form1" type="text" name="email" id="email" placeholder="email" value="<?php if (isset($email)) { echo htmlspecialchars($email); }?>">
                        </p>

                        <p>
                            <textarea name="message" id="message" placeholder="message me!"><?php if (isset($message)) { echo htmlspecialchars($message); }?></textarea>
                        </p>

                        <p style="display: none;">
                            <input type="text" name="address" id="address">
                            <p style="display: none;">Humans: please leave this field blank.</p>
                        </p>
                <input class="button1" type="submit" value="Send">
            </form>
    </div>

    <?php } ?>

<?php
  include '../inc/footer.php';
?>
