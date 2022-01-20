<?php
session_start();
include("mydb.php");

if(isset($_POST['verify'])){
    
        #code...
    $AcctNumb = mysqli_real_escape_string($con, $_POST['account_number']);
    $BankCode = mysqli_real_escape_string($con, $_POST['bank_code']);
    $Amount = mysqli_real_escape_string($con, $_POST['amount']);
    $sql = mysqli_query($con, "SELECT * FROM transfer_recipient WHERE account_number = '$AcctNumb'") or die(mysqli_error($con));

    // if($sql->num_rows > 0){
    //     $data = mysqli_fetch_array($sql);
    //     $recipient_code = $data['recipient_code'];
    //     $name = $data['name'];
    //     echo "<script> alert('Account Number is already verified with correct bank code generating a recipient code, Click on INITIATE button to transfer fund');</script>";
    // }

    // else{
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.paystack.co/bank/resolve?account_number=".rawurlencode($AcctNumb)."&bank_code=".rawurlencode($BankCode),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer sk_test_68b70687fcaedb7b17cbdc15c7ff482aefa8b3eb",
        "Cache-Control: no-cache",
        ),
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        //echo $response;
         $result = json_decode($response);
         $verify = $result->status;
    }

     if($verify){
        
          $fname = $result->data->account_name;
          $_SESSION['amount'] = $Amount;
          $_SESSION['fullname'] = $fname;
          

        //   if(!empty($_SESSION['fullname'])){
        //       echo "yessssssss";
        //   }
    //      #code...
          echo "<script> alert('Account Number is resolved/verified with the bank code');</script>";
          header("location:verify_end.php");
          //echo "<script> alert('name='.$fname.'&account_number='.$AcctNumb.'&bank_code='.$BankCode.'& IS NOW VERIFIED');</script>";
         //header('Location: recipient.php?name='.$name.'&account_number='.$AcctNumb.'&bank_code='.$BankCode);
        
      }
    
    // else{
    //     echo "<script> alert('Invalid Account Number or Bank Code, its NOT resolved/verified'); </script>";
    // }
   // }
}

?>







<!doctype html>
<html lang="en">
  <head>
    <title>Random Finance</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
    <link rel="stylesheet" href="fonts/icomoon/style.css">

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/jquery-ui.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">

    <link rel="stylesheet" href="css/jquery.fancybox.min.css">

    <link rel="stylesheet" href="css/bootstrap-datepicker.css">

    <link rel="stylesheet" href="fonts/flaticon/font/flaticon.css">

    <link rel="stylesheet" href="css/aos.css">

    <link rel="stylesheet" href="css/style.css">

    <link rel="stylesheet" href="css/style2.css"/>
    
  </head>
  <body data-spy="scroll" data-target=".site-navbar-target" data-offset="300">
  

  <div id="overlayer"></div>
  <div class="loader">
    <div class="spinner-border text-primary" role="status">
      <span class="sr-only">Loading...</span>
    </div>
  </div>


  <div class="site-wrap">

    <div class="site-mobile-menu site-navbar-target">
      <div class="site-mobile-menu-header">
        <div class="site-mobile-menu-close mt-3">
          <span class="icon-close2 js-menu-toggle"></span>
        </div>
      </div>
      <div class="site-mobile-menu-body"></div>
    </div>
   
    
    <header class="site-navbar js-sticky-header site-navbar-target" role="banner">

      <div class="container">
        <div class="row align-items-center">
          
          <div class="col-6 col-xl-2">
            <h1 class="mb-0 site-logo"><a href="index.php" class="h2 mb-0">Random<span class="text-primary">.</span> </a></h1>
          </div>

          <div class="col-12 col-md-10 d-none d-xl-block">
            <nav class="site-navigation position-relative text-right" role="navigation">

              <ul class="site-menu main-menu js-clone-nav mr-auto d-none d-lg-block">
                <li><a href="index.php" class="nav-link">Home</a></li>
                <li><a href="logout.php" class="nav-link">Logout</a></li>
              </ul>
            </nav>
          </div>


          <div class="col-6 d-inline-block d-xl-none ml-md-0 py-3" style="position: relative; top: 3px;"><a href="#" class="site-menu-toggle js-menu-toggle float-right"><span class="icon-menu h3"></span></a></div>

        </div>
      </div>
      
    </header>

    

    <section class="site-section border-bottom bg-light" id="services-section">
        <div class="container">
            <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="section-title mb-3">Verify Transfer</h2>
            </div>
            </div>
        </div>
        <form class="form" action="" method="POST">

            <label>Account Number</label>
            <input type="number" class="login-input" name="account_number" placeholder="recipient account number" value="<?php if(isset($_POST['account_number'])){
                echo htmlentities($_POST['account_number']);}?>" required/>


            <label>Bank Code</label>
            <input id="codes" type="text" class="login-input" name="bank_code" placeholder="e.g '058' for GTBank" value="<?php if(isset($_POST['bank_code'])){
                echo htmlentities($_POST['bank_code']);}?>" />
                <center>
                <a href="https://www.cbn.gov.ng/OUT/2011/CIRCULARS/BSPD/NUBAN%20PROPOSALS%20V%200%204-%2003%2009%202010.PDF">Get Bank Code</a>
              </center>
           


            <label>Amount(NGN)</label>
            <input type="text" class="login-input" name="amount" placeholder="amount" value="<?php if(isset($_POST['bank_code'])){
                echo htmlentities($_POST['amount']);}?>" />
           

            <input type="submit" name="verify" value="VERIFY" class="login-button">
        </form>
        <?php
            if(!empty($_POST['account_number']) && !empty($recipient_code)){
                echo "
                <label>Recipient Code: ".$recipient_code." </label>
                <a href='initiate.php?recipient_code=".$recipient_code."'>
                    <button>INITIATE</button>
                </a>
                ";
            }
        ?>
    </section>

    
    <footer class="site-footer">
      <div class="container">
        <div class="row">
          <div class="col-md-9">
            <div class="row">
             
              <div class="col-md-5">
                <h2 class="footer-heading mb-4">Quick Links</h2>
                <ul class="list-unstyled">
                  <li><a href="#about-section" class="smoothscroll">Terms</a></li>
                  <li><a href="#about-section" class="smoothscroll">Policy</a></li>
                </ul>
              </div>
              <div class="col-md-3 footer-social">
                <h2 class="footer-heading mb-4">Follow Us</h2>
                <a href="#" class="pl-0 pr-3"><span class="icon-facebook"></span></a>
                <a href="#" class="pl-3 pr-3"><span class="icon-twitter"></span></a>
                <a href="#" class="pl-3 pr-3"><span class="icon-instagram"></span></a>
                <a href="#" class="pl-3 pr-3"><span class="icon-linkedin"></span></a>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <h2 class="footer-heading mb-4">Subscribe Newsletter</h2>
            <form action="#" method="post" class="footer-subscribe">
              <div class="input-group mb-3">
                <input type="text" class="form-control border-secondary text-white bg-transparent" placeholder="Enter Email" aria-label="Enter Email" aria-describedby="button-addon2">
                <div class="input-group-append">
                  <button class="btn btn-primary text-black" type="button" id="button-addon2">Send</button>
                </div>
              </div>
            </form>
          </div>
        </div>
        <div class="row pt-5 mt-5 text-center">
          <div class="col-md-12">
            <div class="border-top pt-5">
              <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
              <p>Copyright &copy;
                <script>document.write(new Date().getFullYear());</script> All rights reserved 
              </p>
              <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
        
            </div>
          </div>
          
        </div>
      </div>
    </footer>

  </div> <!-- .site-wrap -->
    
 
  <script src="js/jquery-3.3.1.min.js"></script>
  <script src="js/jquery-ui.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/owl.carousel.min.js"></script>
  <script src="js/jquery.countdown.min.js"></script>
  <script src="js/jquery.easing.1.3.js"></script>
  <script src="js/aos.js"></script>
  <script src="js/jquery.fancybox.min.js"></script>
  <script src="js/jquery.sticky.js"></script>
  <script src="js/isotope.pkgd.min.js"></script>

  
  <script src="js/main.js"></script>

  
  </body>
</html>











