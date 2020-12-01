<!DOCTYPE html>
<html>
<head>
    <title>Krishaweb Mis</title>
</head>
<body>
    <table align="center" width="600" cellpadding="0" cellspacing="0" style="font-size: 14px; font-family: arial; background-color: #d6d6d6;">
        <tr>
            <td align="left" style="background-color: #d6d6d6; padding-bottom: 20px; padding-left: 20px; padding-top:20px; padding-right: 20px;">
                <img src="{!! asset('img/mail_logo.png')!!}" alt="logo">
            </td>
        </tr>
        <tr>
            <td valign="top" align="left" style="background: #d6d6d6 url({!! asset('img/arrow.png')!!}) no-repeat 20px bottom; padding-left: 20px; height: 28px;">
            </td>
        </tr>
        <tr>
            <td valign="top" style="background-color: #d6d6d6; padding-bottom: 20px; padding-left: 20px; padding-top:0px; padding-right: 20px;">
                <table width="100%" cellpadding="10" cellspacing="0" style="background-color: #ffffff; color: #000000">
                    <tr>
                        <td>
                            <p style="color: #000000; padding: 10px; margin: 0px; display: block;">
                                Hello, Welcome to KrishaWeb MIS
                            </p>
                            <p style="color: #000000; padding: 10px; margin: 0px; display: block;">
                               <a href="{!! url('/login') !!}" style="color:#fff; text-decoration: none;background: #E36F45; padding: 6px 10px; border-radius: 5px;font-size: 12px;">Login</a>
                            </p>
                            <p style="color: #000000; padding: 10px; display: block; margin: 0px;">
                                Your Username is <span style="font-weight:bold; color: #e36f45">{{$user_info[0]}}</span>
                            </p>
                            <p style="color: #000000; padding: 10px; margin: 0px"> 
                                Your password is <span style="font-weight:bold;">{!! $user_info[1] !!}</span>
                            </p>
                        </td>
                    </tr>       
                </table>
            </td>
        </tr>
        <tr>
            <td style="background-color: #d6d6d6; padding: 20px;"></td>
        </tr>
        <tr>
            <td valign="top" style="padding: 20px; color: #ffffff; background-color: #e36f45">
                Regards,
                <a style="color: #ffffff;" href="http://www.krishaweb.com">Krishaweb.com</a>
            </td>
        </tr>
    </table>
</body>
</html>