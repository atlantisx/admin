<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<div>
            <p>
            {{ $name }},<br><br>
			{{ trans('admin::user.recovery_password_email_text') }}<br>
            <a href="{{ URL::to('user/recovery', array($email,$reset_code)) }}">{{ trans('admin::user.recovery_password_btn_reset') }}</a>
            </p>

            <br>
            System Administrator
		</div>
	</body>
</html>