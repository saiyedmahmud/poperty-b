<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Application Status</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: rgb(242,242,242);
            padding: 20px;
        }

        .container {
            text-align: center;
            margin: 0 auto;
            background-color: rgb(242,242,242);
            padding: 20px;
        }
        .content-body{
            text-align: center;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
        }

        .logo img {
            max-width: none;
            width: 300px;
            height: 50px;
            margin-top: 20px;
        }

        .content {
            text-align: center;
            margin: 0 auto;
            font-size: 12px;
            color: #888888;
            padding: 20px;
            font-weight: bold;
        }

        .login-section {
            text-align: center;
            margin-top: 40px;
            padding: 20px;
        }

        .login-button {
            display: inline-block;
            padding: 10px 30px;
            font-size: 16px;
            background-color: #169B7D;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease-in-out;
        }

        .login-button:hover {
            background-color: #000000;
        }

        .social-links {
            font-size: 14px;
            color: #888888;
            margin-top: 20px;
        }

        .social-links a {
            text-decoration: none;
            color: #0077B5;
            margin: 0 10px;
        }
                hr {
            width: 75%;
            margin: 20px auto;
            border: none;
            height: 1px;
            background-color: #c3c1c1;
        }
        .footer img {
            width: 40px;
            height: auto;
            margin: 0 3px;
        }

        .footer a {
            text-decoration: none;
            color: #888888;
            margin: 0 10px;
        }

        .footer {
            margin: 0 auto;
            text-align: center;
            font-size: 12px;
            color: #888888;
            padding: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
       <div class="content-body">
           <!-- Header Section -->
           <div class="login-section">
               <img style="width: 200px; height: 30px;" src="https://efvubao.stripocdn.email/content/guids/CABINET_71fd4e221d4d35bc7d7926a79506654a6c2e8ad36423b0adf2860b71f4e5a36b/images/image.png" alt="">
           </div>

           <!-- Body Content -->
           <div class="content">
               <h3 style="color: #333333; text-align: center; font-size:22px; margin:35px">Hi {{$mailData['candidateName']}}!</h3>
               <p style="font-size: 16px; color: #666666; text-align: justify; ">Thank you for applying to <strong style="color: #333333;">{{$mailData['companyName']}}</strong> for the <strong style="color: #333333;">{{$mailData['jobTitle']}}</strong> position and appreciate your interest in joining our team.<br /><br />
                I am writing to inform you that your application status is currently: <strong style="color: #333333;">{{$mailData['status']}}</strong>.</p><br /><br />

                @if($mailData['status'] == 'SELECTED FOR INTERVIEW') 
                    <p style="font-size: 16px; color: #666666; text-align: justify; "> We are pleased to inform you that you have been selected for an interview. We will be in touch shortly to schedule it.</p><br><br>
                @elseif($mailData['status'] == 'APPLIED')
                    <p style="font-size: 16px; color: #666666; text-align: justify; "> We have received your application and our team is currently reviewing it.</p><br><br>
                @elseif($mailData['status'] == 'REVIEWING')
                    <p style="font-size: 16px; color: #666666; text-align: justify; "> Your application is under review. We appreciate your patience during this process.</p><br><br>
                @elseif($mailData['status'] == 'CANCELLED')
                    <p style="font-size: 16px; color: #666666; text-align: justify; "> Unfortunately, we have had to cancel the recruitment for this position.</p><br><br>
                @elseif($mailData['status'] == 'REJECTED')
                    <p style="font-size: 16px; color: #666666; text-align: justify; "> After careful consideration, we have decided not to move forward with your application.</p><br><br>
                @elseif($mailData['status'] == 'HIRED')
                    <p style="font-size: 16px; color: #666666; text-align: justify; "> Congratulations! We are excited to welcome you to our team. Further details will be shared soon.</p><br><br>
                @elseif($mailData['status'] == 'INTERVIEWING')
                    <p style="font-size: 16px; color: #666666; text-align: justify; "> Your interview process is ongoing. We will keep you updated on the next steps.</p><br><br>
                @endif
                
                <p style="font-size: 16px; color: #666666; text-align: justify; "> Thank you for considering {{$mailData['companyName']}}.</p>
               <p style="font-size: 16px; color: #000000; text-align: center;">Thank You</p>
               <!-- Add personalized content here -->
           </div>

           <!-- Footer Section -->
           <div class="footer">
               <p>Contract Us:</p>
               <p class="social-links">
                   <a href="https://www.facebook.com/omegasolutionllc"
                   ><img
                           src="https://efvubao.stripocdn.email/content/assets/img/social-icons/logo-black/facebook-logo-black.png"
                           alt="Facebook"
                       /></a>
                   <a href="https://www.twitter.com/omegasolution1"
                   ><img
                           src="https://efvubao.stripocdn.email/content/assets/img/social-icons/logo-black/x-logo-black.png"
                           alt="Twitter"
                       /></a>
                   <a href="https://www.instagram.com/omegasolutionllc"
                   ><img
                           src="https://efvubao.stripocdn.email/content/assets/img/social-icons/logo-black/instagram-logo-black.png"
                           alt="Instagram"
                       /></a>
                   <a href="https://www.youtube.com/@omegasolutionllc"
                   ><img
                           src="https://efvubao.stripocdn.email/content/assets/img/social-icons/logo-black/youtube-logo-black.png"
                           alt="YouTube"
                       /></a>
                   <a href="https://www.linkedin.com/company/oneomegasolution/"
                   ><img
                           src="https://efvubao.stripocdn.email/content/assets/img/social-icons/logo-black/linkedin-logo-black.png"
                           alt="LinkedIn"
                       /></a>
               <hr>
               <a href="https://solution.omega.ac/">Visit Us</a> |
               <a  href="https://solution.omega.ac/privacy-policy/">Privacy Policy</a> |
               <a href="https://solution.omega.ac/services/">Services</a>
               <p><a href="https://maps.app.goo.gl/1HMGr3UaiPtthy8C8"
                     target="_blank"
                   >
                       2201 Menaul Blvd NE Suite B,
                       Albuquerque, NM 87107, United States</a
                   ></p>
               <p>&copy; 2021 Omega Solution LLC. All rights reserved.</p>
           </div>
       </div>
    </div>
</body>

</html>
