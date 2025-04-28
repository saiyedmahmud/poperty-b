<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Interview for {{$mailData['jobTitle']}}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: rgb(242, 242, 242);
            padding: 20px;
        }

        .container {
            text-align: center;
            margin: 0 auto;
            background-color: rgb(242, 242, 242);
            padding: 20px;
        }

        .content-body {
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
                <img style="width: 200px; height: 30px;" src="https://efvubao.stripocdn.email/content/guids/CABINET_71fd4e221d4d35bc7d7926a79506654a6c2e8ad36423b0adf2860b71f4e5a36b/images/image.png" alt="Company Logo">
            </div>

            <!-- Body Content -->
            <div class="content">
                <h3 style="color: #333333; text-align: center; font-size: 22px; margin: 35px">Hi {{$mailData['candidateName']}}!</h3>
                <p style="font-size: 16px; color: #666666; text-align: justify;">Thank you for applying to <strong style="color: #333333;">{{$mailData['companyName']}}</strong> for the <strong style="color: #333333;">{{$mailData['jobTitle']}}</strong> position.</p><br>
                <p style="font-size: 16px; color: #666666; text-align: justify;">We are pleased to inform you that you have been selected for an interview. Here are the details:</p><br>
                <p style="font-size: 16px; color: #666666; text-align: justify;">
                    <strong>Date:</strong> {{$mailData['scheduleDate']}}<br>
                    <strong>Time:</strong> {{$mailData['scheduleTime']}}<br>
                    <strong>Location:</strong> {{$mailData['interviewLocation']}}
                </p><br>
                <p style="font-size: 16px; color: #666666; text-align: justify;">Please let us know if the scheduled time works for you or if any adjustments are needed.</p><br>
                <p style="font-size: 16px; color: #666666; text-align: justify;">If you have any questions, feel free to contact me at {{$mailData['mailResponserEmail']}}.</p><br>
                <p style="font-size: 16px; color: #666666; text-align: justify;">Looking forward to meeting you.</p><br>
                <p style="font-size: 16px; color: #666666; text-align: justify;">Best regards,</p>
                <p style="font-size: 16px; color: #666666; text-align: justify;">{{$mailData['mailResponserName']}}</p>
                <p style="font-size: 16px; color: #666666; text-align: justify;">{{$mailData['mailResponserDesignation']}}</p>
                <p style="font-size: 16px; color: #666666; text-align: justify;">{{$mailData['companyName']}}</p>
            </div>

            <!-- Footer Section -->
            <div class="footer">
                <p>Contact Us:</p>
                <p class="social-links">
                    <a href="https://www.facebook.com/omegasolutionllc"><img src="https://efvubao.stripocdn.email/content/assets/img/social-icons/logo-black/facebook-logo-black.png" alt="Facebook"></a>
                    <a href="https://www.twitter.com/omegasolution1"><img src="https://efvubao.stripocdn.email/content/assets/img/social-icons/logo-black/x-logo-black.png" alt="Twitter"></a>
                    <a href="https://www.instagram.com/omegasolutionllc"><img src="https://efvubao.stripocdn.email/content/assets/img/social-icons/logo-black/instagram-logo-black.png" alt="Instagram"></a>
                    <a href="https://www.youtube.com/@omegasolutionllc"><img src="https://efvubao.stripocdn.email/content/assets/img/social-icons/logo-black/youtube-logo-black.png" alt="YouTube"></a>
                    <a href="https://www.linkedin.com/company/oneomegasolution/"><img src="https://efvubao.stripocdn.email/content/assets/img/social-icons/logo-black/linkedin-logo-black.png" alt="LinkedIn"></a>
                </p>
                <hr>
                <a href="https://solution.omega.ac/">Visit Us</a> |
                <a href="https://solution.omega.ac/privacy-policy/">Privacy Policy</a> |
                <a href="https://solution.omega.ac/services/">Services</a>
                <p><a href="https://maps.app.goo.gl/1HMGr3UaiPtthy8C8" target="_blank">
                    2201 Menaul Blvd NE Suite B,
                    Albuquerque, NM 87107, United States</a>
                </p>
                <p>&copy; 2021 Omega Solution LLC. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>

</html>
