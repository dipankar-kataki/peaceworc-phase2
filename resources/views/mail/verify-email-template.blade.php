
<!doctype html>
<html lang="en-US">

<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <title>Peaceworc | Email Verification Mail</title>
    <meta name="description" content="Peaceworc Email Verification Mail.">
    <style type="text/css">
        a:hover {text-decoration: underline !important;}
        p{
            margin-bottom:2px;
        }
    </style>
</head>

<body marginheight="0" topmargin="0" marginwidth="0" style="margin: 0px; background-color: #f2f3f8;" leftmargin="0">

    <div class="content" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; max-width: 450px; display: block; margin: 0 auto; padding: 20px;">
        <table class="main" width="100%" cellpadding="0" cellspacing="0" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; border-radius: 3px; margin: 0; border: none;">
            <tbody>
                <tr style="font-family: 'Roboto', sans-serif; font-size: 14px; margin: 0;">
                    <td class="content-wrap" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; color: #495057; font-size: 14px; vertical-align: top; margin: 0;padding: 30px; box-shadow: 0 3px 15px rgba(30,32,37,.06); ;border-radius: 7px; background-color: #fff;" valign="top">
                        <meta itemprop="name" content="Confirm Email" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                        <table width="100%" cellpadding="0" cellspacing="0" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                            <tbody>
                                <tr style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                    <td class="content-block" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;" valign="top">
                                        <div style="text-align: left;margin-bottom: 5px;">
                                            <img src="{{asset('/assets/images/logo/logo.png')}}" alt="" height="60">
                                        </div>
                                    </td>
                                </tr>
                                <tr style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                    <td class="content-block" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;" valign="top">
                                        <div style="text-align: center;margin-bottom: 5px;">
                                            <img src="{{asset('/assets/images/otp.png')}}" alt="" height="200">
                                        </div>
                                    </td>
                                </tr>
                                <tr style="font-family: 'Roboto', sans-serif; box-sizing: border-box;  margin: 0;">
                                    <td class="content-block" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 18px; vertical-align: top; margin: 0;   text-align: center;" valign="top">
                                        <h5 style="font-family: 'Roboto', sans-serif; font-weight: 500; font-size: 16px;">Use the below OTP for your email verification. OTP valid for only 3 minutes.</h5>
                                    </td>
                                </tr>
                                <tr style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
                                    <td class="content-block" style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 22px; text-align: center;">
                                        <div style="font-family: 'Roboto', sans-serif; box-sizing: border-box; font-size: 1.5rem; color: #FFF; text-decoration: none; font-weight: 400; text-align: center; cursor: pointer; display: inline-block; border-radius: .25rem; text-transform: capitalize; background-color: #405189; margin: 0; border-color: #405189; border-style: solid; border-width: 1px; padding: .5rem .9rem;">
                                            {{$otp}}
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
        <div style="text-align: center; margin: 25px auto 0px auto;font-family: 'Roboto', sans-serif;">
            <h4 style="font-weight: 500; line-height: 1.5;font-family: 'Roboto', sans-serif;">Need Help ?</h4>
            <p style="color: #878a99; line-height: 1.5;">Please send and feedback or bug info to <a href="#" style="font-weight: 500;">info@peaceworc.com</a></p>
            <p style="font-family: 'Roboto', sans-serif; font-size: 14px;color: #98a6ad; margin: 0px;">
                {{date('Y')}} &copy; www.peaceworc.com. All rights reserved.
            </p>
        </div>
    </div>
</body>

</html>