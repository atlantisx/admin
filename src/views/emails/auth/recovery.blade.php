<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<div>
            <p>
            {{ $full_name }},<br><br>
			{{ trans('admin::user.recovery_password_email_text') }}<br>
            <a href="{{ $reset_link }}">{{ trans('admin::user.recovery_password_btn_reset') }}</a>
            </p>
            <br>
            {{ trans('admin::user.recovery_email_regards') }}
		</div>
	</body>
</html>