<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<div>
            <p>
            {{ $receiver['full_name'] }},<br><br>
			<br>
            <a href="{{ url('message/read') }}">{{ trans('message.title.read_message') }}</a>
            </p>
            <br>
            $sender['full_name'],
		</div>
	</body>
</html>