<!DOCTYPE html>
<html>
<head>
    <title>Krishaweb Mis</title>
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
                        <td>
                        	@if($type == 1)
                        		<p>Dear {{ $users->people->name }},</p>
                        		<p>You have missed filling your work, please fill your today's work.</p>
                        		<p>Notice: If you are on leave then don't consider it.</p>
                        	@else
                        		<p>This is the list of team members who didn't fill team work for this date : {{ $date }}</p>
                        		<ul class="list-group row">
                        		@foreach($users as $user)
                        			<li class="=list-group-item">{{ $user }}</li>
                        		@endforeach
                        		</ul>
                            @endif
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