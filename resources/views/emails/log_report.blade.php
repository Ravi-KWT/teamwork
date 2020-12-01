{{-- <!DOCTYPE html>
<html>
<head>
    <title>Krishaweb Mis</title>
    <style type="text/css">
        .log-report td {
          border: 1px solid black;
          border-collapse: collapse;
      }
    </style>
</head>
<body>
    <table align="center" width="600" cellpadding="0" cellspacing="0" style="font-size: 14px; font-family: arial; background-color: #d6d6d6;">
        <tr>
            <td align="left" style="background-color: #d6d6d6; padding-bottom: 20px; padding-left: 20px; padding-top:20px; padding-right: 20px;">
                <img src="http://teamwork.krishaweb.com/img/mail_logo.png" alt="logo">
            </td>
        </tr>
        <tr>
            <td valign="top" align="left" style="background: #d6d6d6 url('http://teamwork.krishaweb.com/img/arrow.png') no-repeat 20px bottom; padding-left: 20px; height: 28px;">
            </td>
        </tr>
        <tr>
            <td valign="top" style="background-color: #d6d6d6; padding-bottom: 20px; padding-left: 20px; padding-top:0px; padding-right: 20px;">
                <table width="100%" cellpadding="10" cellspacing="0" style="background-color: #ffffff; color: #000000">
                    <tr>
                        <td class="log-report">
                        		<p>Hello {{ $team_lead['name'] }},</p>
                        		<p>This is your team's weekly MIS Log Report</p>
                                <table style="border: 1px solid">
                                    <tr>
                                        <td><p>{{ $team_lead['team_billable_hours'] + $team_lead['team_non_billable_hours'] }}</p><p>Total Logged Hours</p></td>
                                        <td><p>{{ $team_lead['total_days'] * $team_lead['total_users'] * 8 }}</p><p>Targeted Hours</p></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><p>Team Hours Starts from {{ date("dS",strtotime($start_date))." to ". date("dS",strtotime($end_date)) . " of " . date('F',strtotime($end_date)) .", ". date('Y',strtotime($end_date)) }}</p></td>
                                    </tr>
                                </table>
                        		

                        		

                                <h4>Hours Logged by each member</h4>
                                <table class="table" style="border: 1px solid;">
                                    <thead>
                                        <th>Name</th>
                                        <th>Worked Hours</th>
                                        <th>Non Billable Hours</th>
                                    </thead>
                                    <tbody>
                                        @foreach($team_lead['users'] as $key => $user)
                                            @php
                                                $billable = explode(':', date('H:i', mktime(0, ($user['billable_hours']*3600) / 60)));
                                                ; 
                                                $non_billable =  explode(':', date('H:i', mktime(0, ($user['non_billable_hours']*3600) / 60))); 
                                            @endphp
                                            <tr>
                                                <td><a href="{{ url('/everything') }}">{{ $user['name'] }}</a></td>
                                                <td>{{ $user['billable_hours'] == null ? '00h:00m' : $billable[0].'h:'.$billable[1].'m' }}</td>
                                                <td>{{ $user['non_billable_hours'] == null ? '00h:00m' : $non_billable[0].'h:'.$non_billable[1].'m' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>

                                </table>
                        		<p>Notice: You can able to view their project wise log by clicking on theri name</p>
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
</html> --}}


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" 
  xmlns:v="urn:schemas-microsoft-com:vml"
  xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0 " />
<meta name="format-detection" content="telephone=no"/>
<title>Krishaweb - Create | Communicate | Connect</title>
<style type="text/css">
body {
    margin: 0 !important;
    padding: 0 !important;
    -webkit-text-size-adjust: 100% !important;
    -ms-text-size-adjust: 100% !important;
    -webkit-font-smoothing: antialiased !important;
}
img {
    border: 0 !important;
    outline: none !important;
}
table {
    border-collapse: collapse;
    mso-table-lspace: 0px;
    mso-table-rspace: 0px;
}
td, a, span {
    border-collapse: collapse;
    mso-line-height-rule: exactly;
}
p {
    Margin: 0px !important;
    Padding: 0px !important;
}
.em_defaultlink a {
    color: inherit !important;
    text-decoration: none !important;
}
.ExternalClass * {
    line-height: 100%;
}
span.MsoHyperlink {
    mso-style-priority: 99;
    color: inherit;
}
span.MsoHyperlinkFollowed {
    mso-style-priority: 99;
    color: inherit;
}
@media only screen and (max-width:600px) {
  .main_table{ overflow: hidden; }
  .table_responsive{ width: 100% !important; }
  .res_hide{ display: none !important; }
  .right_box{ border-left: 1px solid #E6E6E6 !important; border-top: 1px solid #E6E6E6; }
  .footer_text{ text-align: center !important; padding-top: 0 !important; padding-bottom: 15px !important; }
  .last_cell{ width: 95px !important; }
}
</style>
<!--[if gte mso 9]>
  <xml>
    <o:OfficeDocumentSettings>
      <o:AllowPNG/>
      <o:PixelsPerInch>96</o:PixelsPerInch>
    </o:OfficeDocumentSettings>
  </xml>
  <![endif]-->
</head>

<body style="margin:0px; padding:0px;" bgcolor="#F7F7F7">

<!--Full width table start-->
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#F7F7F7" class="main_table">
  <!--Template Body Start-->
  <tr>
    <td align="left" valign="top" bgcolor="#ffffff" style="padding: 12px 0; border-bottom: 1px solid #E4E3E4;">
      <table width="600" border="0" cellspacing="0" cellpadding="0" align="center" class="table_responsive">
        <tr>
          <td align="left" valign="top">
            <table width="236" border="0" cellspacing="0" cellpadding="0" align="left" class="table_responsive">
              <tr>
                <td align="center" valign="top" style="font-size: 0px; line-height: 0px;">
                  <a href="https://www.krishaweb.com/" style="text-decoration: none; color: #000000;" target="_blank">
                    <img src="{!! asset('img/logo.png') !!}" alt=" " style="display: block; border: 0; max-width: 100%;" border="0" width="236" />
                  </a>
                </td>
              </tr>
            </table>
            <table border="0" cellspacing="0" cellpadding="0" align="right" class="table_responsive">
              <tr>
                <td align="center" valign="top" style="font-family: Verdana, Arial, sans-serif; font-size: 12px; line-height: 15px; color: #666666; text-align: center; padding-top: 23px;">{{ date('jS F, Y') }}</td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top" style="padding: 30px 0px 20px;">
      <table width="600" border="0" cellspacing="0" cellpadding="0" align="center" style="table-layout:fixed;width:600px;" class="table_responsive">
        <tr>
          <td align="center" valign="top" bgcolor="#ffffff" style="padding: 23px 20px 30px;">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
              <tr>
                <td align="left" valign="top" style="font-family: Verdana, Arial, sans-serif; font-size: 14px; line-height: 24px; color: #2D2D2D; text-align: left; padding-bottom: 10px;">Hello {{ $team_lead['name'] }},</td>
              </tr>
              <tr>
                <td align="left" valign="top" style="font-family: Verdana, Arial, sans-serif; font-size: 14px; line-height: 24px; color: #2D2D2D; text-align: left; padding-bottom: 20px;">This is your team’s weekly MIS Log report</td>
              </tr>
              <tr>
                <td height="1" bgcolor="#E6E6E6" style="font-size: 0px; line-height: 0px;"> </td>
              </tr>
              <tr>
                <td align="left" valign="top">
                  <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                    <tr>
                      <td align="left" valign="top">
                        <table width="281" border="0" cellspacing="0" cellpadding="0" align="left" class="table_responsive">
                          <tr>
                            <td width="1" bgcolor="#E6E6E6" style="font-size: 0px; line-height: 0px;"> </td>
                            <td align="center" valign="middle" height="95" style="padding: 16px 10px;" bgcolor="#F5F4F5">
                              <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                                <tr>
                                  <td align="center" valign="top" style="font-family: Verdana, Arial, sans-serif; font-size: 36px; line-height: 44px; font-weight: bold; color: #0F4C82; text-align: center; padding-bottom: 2px;">{{ $team_lead['team_billable_hours'] + $team_lead['team_non_billable_hours'] }}</td>
                                </tr>
                                <tr>
                                  <td align="center" valign="top" style="font-family: Verdana, Arial, sans-serif; font-size: 11px; line-height: 13px; font-weight: bold; color: #999999; text-transform: uppercase; letter-spacing: 0.08em; text-align: center;">Total logged Hours</td>
                                </tr>
                              </table>
                            </td>
                            <td width="1" bgcolor="#E6E6E6" style="font-size: 0px; line-height: 0px;"> </td>
                          </tr>
                        </table>
                        <!--[if mso gte 9]>
                          </td>
                          <td align="left" valign="top">     
                        <![endif]-->
                        <table width="279" border="0" cellspacing="0" cellpadding="0" align="right" class="table_responsive">
                          <tr>
                            <td align="center" valign="middle" class="right_box" height="95" style="padding: 16px 10px;" bgcolor="#F5F4F5">
                              <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                                <tr>
                                  <td align="center" valign="top" style="font-family: Verdana, Arial, sans-serif; font-size: 36px; line-height: 44px; font-weight: bold; color: #0F4C82; text-align: center; padding-bottom: 2px;">{{ $team_lead['total_days'] * $team_lead['total_users'] * 8.5 }}</td>
                                </tr>
                                <tr>
                                  <td align="center" valign="top" style="font-family: Verdana, Arial, sans-serif; font-size: 11px; line-height: 13px; font-weight: bold; color: #999999; text-transform: uppercase; letter-spacing: 0.08em; text-align: center;">Targeted Hours</td>
                                </tr>
                              </table>
                            </td>
                            <td width="1" bgcolor="#E6E6E6" style="font-size: 0px; line-height: 0px;"> </td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td height="1" bgcolor="#E6E6E6" style="font-size: 0px; line-height: 0px;"> </td>
              </tr>
              <tr>
                <td align="center" valign="top">
                  <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                    <tr>
                      <td width="1" bgcolor="#E6E6E6" style="font-size: 0px; line-height: 0px;"> </td>
                      <td align="center" valign="top" bgcolor="#F5F4F5" style="font-family: Verdana, Arial, sans-serif; font-size: 14px; line-height: 17px; color: #666666; text-align: center; padding: 12px 10px;">Team Hours Stats from <span style="font-weight: bold;">{{ date("dS",strtotime($start_date))." to ". date("dS",strtotime($end_date)) . " of " . date('F',strtotime($end_date)) .", ". date('Y',strtotime($end_date)) }}</span></td>
                      <td width="1" bgcolor="#E6E6E6" style="font-size: 0px; line-height: 0px;"> </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td height="1" bgcolor="#E6E6E6" style="font-size: 0px; line-height: 0px;"> </td>
              </tr>
              <tr>
                <td align="left" valign="top" style="font-family: Verdana, Arial, sans-serif; font-size: 16px; line-height: 19px; color: #2D2D2D; font-weight: bold; text-align: left; padding-top: 29px; padding-bottom: 16px;">Hours logged by each member</td>
              </tr>
              <tr>
                <td height="1" bgcolor="#E6E6E6" style="font-size: 0px; line-height: 0px;"> </td>
              </tr>
              <tr>
                <td align="center" valign="top">
                  <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                    <tr>
                      <td width="1" bgcolor="#E6E6E6" style="font-size: 0px; line-height: 0px;"> </td>
                      <td align="center" valign="top">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                          <tr>
                            <td align="left" valign="middle" bgcolor="#E6E6E6" style="font-family: Verdana, Arial, sans-serif; font-size: 11px; line-height: 14px; color: #666666; text-transform: uppercase; text-align: left; letter-spacing: 0.08em; padding: 14px;">Name</td>
                            <td align="right" valign="middle" bgcolor="#E6E6E6" style="font-family: Verdana, Arial, sans-serif; font-size: 11px; line-height: 14px; color: #666666; text-transform: uppercase; text-align: right; letter-spacing: 0.08em; padding: 14px;">Worked Hours</td>
                            <td align="right" valign="middle" class="last_cell" width="102" bgcolor="#E6E6E6" style="font-family: Verdana, Arial, sans-serif; font-size: 11px; line-height: 14px; color: #666666; text-transform: uppercase; text-align: right; letter-spacing: 0.08em; padding: 14px;">Non Billable</td>
                          </tr>
                            @foreach($team_lead['users'] as $key => $user)
                                @php
                                    $billable = explode(':', date('H:i', mktime(0, ($user['billable_hours']*3600) / 60)));
                                    ; 
                                    $non_billable =  explode(':', date('H:i', mktime(0, ($user['non_billable_hours']*3600) / 60))); 
                                @endphp
                                <tr>
                                  <td align="left" valign="middle" style="font-family: Verdana, Arial, sans-serif; font-size: 13px; line-height: 16px; color: #116AB9; text-align: left; padding: 11px 14px; border-bottom: 1px solid #E6E6E6;"><a href="{{ url('/everything') }}" style="text-decoration: none; color: #116AB9;" target="_blank">{{ $user['name'] }}</a></td>
                                  <td align="right" valign="middle" style="font-family: Verdana, Arial, sans-serif; font-size: 13px; line-height: 16px; color: #444444; font-weight: bold; text-align: right; padding: 11px 14px; border-bottom: 1px solid #E6E6E6;">{{ $user['billable_hours'] == null ? '00h:00m' : $billable[0].'h:'.$billable[1].'m' }}</td>
                                  <td align="right" valign="middle" class="last_cell" width="102" style="font-family: Verdana, Arial, sans-serif; font-size: 13px; line-height: 16px; color: #E66262; text-align: right; padding: 11px 14px; border-bottom: 1px solid #E6E6E6;">{{ $user['non_billable_hours'] == null ? '00h:00m' : $non_billable[0].'h:'.$non_billable[1].'m' }}</td>
                                </tr>
                            @endforeach
                          
                        </table>
                      </td>
                      <td width="1" bgcolor="#E6E6E6" style="font-size: 0px; line-height: 0px;"> </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td align="left" valign="top" style="font-family: Verdana, Arial, sans-serif; font-size: 11px; line-height: 14px; color: #666666; text-align: left; padding-top: 11px;">Note: You can able to view their project wise log by clicking on their name</td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td align="left" valign="top" style="padding-top: 20px;">
            <table width="600" border="0" cellspacing="0" cellpadding="0" align="center" class="table_responsive">
              <tr>
                <td align="left" valign="top">
                  <table border="0" cellspacing="0" cellpadding="0" align="left" class="table_responsive">
                    <tr>
                      <td align="left" valign="top" class="footer_text" style="font-family: Verdana, Arial, sans-serif; font-size: 12px; line-height: 15px; color: #878787; text-align: left; letter-spacing: 0.01em; padding-top: 7px;">&copy; 2020 KrishaWeb. All rights reserved.</td>
                    </tr>
                  </table>
                  <table width="180" border="0" cellspacing="0" cellpadding="0" align="right" class="table_responsive">
                    <tr>
                      <td align="center" valign="top">
                        <table width="180" border="0" cellspacing="0" cellpadding="0" align="center">
                          <tr>
                            <td align="center" valign="top" width="30" style="font-size: 0px; line-height: 0px;">
                              <a href="https://www.facebook.com/KrishaWeb/" style="text-decoration: none; color: #BCD3E3;" target="_blank">
                                <img src="{!! asset('img/fb_logo.png') !!}" alt="FB" style="font-family: Verdana, Arial, sans-serif; font-size: 12px; line-height: 30px; color: #BCD3E3; display: block; border: 0; max-width: 100%;" border="0" width="30" height="30" />
                              </a>
                            </td>
                            <td width="20" style="font-size: 0px; line-height: 0px;"> </td>
                            <td align="center" valign="top" width="30" style="font-size: 0px; line-height: 0px;">
                              <a href="https://twitter.com/krishaweb" style="text-decoration: none; color: #BCD3E3;" target="_blank">
                                <img src="{!! asset('img/twitter_logo.png') !!}" alt="TW" style="font-family: Verdana, Arial, sans-serif; font-size: 12px; line-height: 30px; color: #BCD3E3; display: block; border: 0; max-width: 100%;" border="0" width="30" height="30" />
                              </a>
                            </td>
                            <td width="20" style="font-size: 0px; line-height: 0px;"> </td>
                            <td align="center" valign="top" width="30" style="font-size: 0px; line-height: 0px;">
                              <a href="https://www.linkedin.com/company/krishaweb-technology" style="text-decoration: none; color: #BCD3E3;" target="_blank">
                                <img src="{!! asset('img/linkedin_logo.png') !!}" alt="IN" style="font-family: Verdana, Arial, sans-serif; font-size: 12px; line-height: 30px; color: #BCD3E3; display: block; border: 0; max-width: 100%;" border="0" width="30" height="30" />
                              </a>
                            </td>
                            <td width="20" style="font-size: 0px; line-height: 0px;"> </td>
                            <td align="center" valign="top" width="30" style="font-size: 0px; line-height: 0px;">
                              <a href="https://www.instagram.com/krishaweb/" style="text-decoration: none; color: #BCD3E3;" target="_blank">
                                <img src="{!! asset('img/insta_logo.png') !!}" alt="Insta" style="font-family: Verdana, Arial, sans-serif; font-size: 12px; line-height: 30px; color: #BCD3E3; display: block; border: 0; max-width: 100%;" border="0" width="30" height="30" />
                              </a>
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <!--Template Body End-->
</table>
<!--Full width table End--> 

<div style="white-space:nowrap;font:20px courier;color:#ffffff;"><span class="res_hide">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</span></div>
</body>
</html>
