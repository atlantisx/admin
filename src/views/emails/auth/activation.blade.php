<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<div>
            <p>
            {{ $name }},<br><br>

			{{ trans('admin::user.activation_email_text') }}<br>
            <a href="{{ URL::to('user/activation', array($activation_code)) }}">{{ trans('admin::user.activation_btn_activate') }}</a>
            </p>

            <br>
            System Administrator
		</div>
	</body>
</html>