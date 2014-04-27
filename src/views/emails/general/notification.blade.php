<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<div>
            <p>
            {{ $receiver['full_name'] }},<br><br>
            {{ trans('admin::message.text.notification_body') }}<br>
            <a href="{{ $message_link }}">{{ trans('admin::message.title.read_message') }}</a>
            </p>
            <br>
            {{ trans('admin::user.recovery_email_regards') }},
		</div>
	</body>
</html>