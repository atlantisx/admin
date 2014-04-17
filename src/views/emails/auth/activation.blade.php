<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<div>
            <p>
            {{ $full_name }},<br><br>
			{{ trans('admin::user.activation_email_text') }}<br>
            </p>
            <br>
            {{ trans('admin::user.activation_email_regards') }}
		</div>
	</body>
</html>