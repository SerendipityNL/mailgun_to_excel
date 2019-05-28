<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Mailgun to Excel</title>
	<link rel="stylesheet" href="/assets/tailwind.css">
</head>
<body class="min-h-screen bg-teal-900">

	<div class="container mx-auto mt10">
		<h1 class="text-2xl text-white font-bold">Douwe's Mailgun Tool</h1>


		<div class="container mx-auto mt-10 flex">
			<div class="w-1/4 bg-gray-300 px-4 rounded-l flex-1">
				<h2 class="text-l font-bold">Parameters</h2>
				<form action="/" method="POST" class="mb-4">
					<div class="mb-4">
						<label for="API_KEY" class="block text-gray-700 text-sm font-bold mb-2">API-Key</label>
						<input type="text" id="API_KEY" name="API_KEY" class="shadow appearance border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo $_POST['API_KEY'] ?? ''; ?>">
					</div>
					<div class="mb-4">
						<label for="DOMAIN" class="block text-gray-700 text-sm font-bold mb-2">Domain</label>
						<input type="text" id="DOMAIN" name="DOMAIN" class="shadow appearance border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo $_POST['DOMAIN'] ?? ''; ?>">
					</div>
					<div class="mb-4">
						<button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit" name="type" value="activate">Activeer</button>
					</div>
				</form>
			</div>
			<div class="w-3/4 flex-3 px-4 rounded-r bg-gray-100">
				<p>Ga naar mailgun en haal je domain op (bijv. trafficsupply.nl) en je API-key</p>
			</div>
		</div>
	</div>
</body>
</html>
