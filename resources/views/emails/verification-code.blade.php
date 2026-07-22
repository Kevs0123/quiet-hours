<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>Verification Code</title>
	<style>
		body { font-family: Inter, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; color: #222; }
		.panel { background:#f7f4ee; padding:18px; border-radius:8px; display:inline-block; font-size:18px; font-weight:700; }
		.container { max-width:600px; margin:28px auto; padding:12px; }
		.muted { color:#6b6860; }
	</style>
</head>
<body>
	<div class="container">
		<h2>Verification Code</h2>
		<p>Hello {{ $name }},</p>
		<p>Use the following verification code to complete your action:</p>
		<div class="panel">{{ $code }}</div>
		<p class="muted" style="margin-top:12px;">This code will expire in 10 minutes.</p>
		<p class="muted">If you did not request this, you can safely ignore this email.</p>
		<p style="margin-top:18px;">Thanks,<br>Quiet Hours Hotel</p>
	</div>
</body>
</html>

