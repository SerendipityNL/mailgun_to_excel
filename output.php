<?php

if (_has_searched()) {
    $events = _get_events(_get_filters())->getItems();

    $messages = _prepare_messages($events);
    if (_download()) {
        _build_xslx($messages);
    }
}
?>
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
		<p class="text-white">Mocht er iets mis gaan met de isntellingen, verwijder dan config.php</p>


		<div class="container mx-auto mt-10 flex">
			<div class="w-1/4 bg-gray-300 px-4 rounded-l flex-1">
				<h2 class="text-l font-bold">Filters</h2>
				<form action="/" method="POST" class="mb-4">
					<div class="mb-4">
						<label for="from" class="block text-gray-700 text-sm font-bold mb-2">Van</label>
						<input type="text" id="from" name="from" class="shadow appearance border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo $_POST['from'] ?? ''; ?>">
					</div>
					<div class="mb-4">
						<label for="to" class="block text-gray-700 text-sm font-bold mb-2">Naar</label>
						<input type="text" id="to" name="to" class="shadow appearance border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo $_POST['to'] ?? ''; ?>">
					</div>
					<!-- <div class="mb-4">
						<label for="subject" class="block text-gray-700 text-sm font-bold mb-2">Onderwerp</label>
						<input type="text" id="subject" name="subject" class="shadow appearance border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo $_POST['subject'] ?? ''; ?>">
					</div> -->
					<div class="mb-4">
						<button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit" name="type" value="search">Zoek</button>
						<button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit" name="type" value="to_excel">Download</button>
					</div>
				</form>
			</div>
			<div class="w-3/4 flex-3 px-4 rounded-r bg-gray-100">
				<h2 class="text-l font-bold">Resultaten</h2>
				<?php if (_has_searched()): ?>
					<?php if (count($messages) > 0): ?>
						<table>
							<thead>
								<tr>
									<!-- <th>ID</th> -->
									<th>Onderwerp</th>
									<th>Van</th>
									<th>Naar</th>
									<th>Geaccepteerd</th>
									<th>Bezorgd</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($messages as $id => $message): ?>
									<tr>
										<!-- <td><?php // echo $id; ?></td> -->
										<td><?php echo $message['subject']; ?></td>
										<td><?php echo htmlentities(reset($message['mails'])['from']); ?></td>
										<td><?php echo htmlentities(reset($message['mails'])['to']); ?></td>
										<td><?php echo isset(reset($message['mails'])['events']['accepted']) ? \Carbon\Carbon::createFromTimestamp(reset($message['mails'])['events']['accepted']) : ''; ?></td>
										<td><?php echo isset(reset($message['mails'])['events']['delivered']) ? \Carbon\Carbon::createFromTimestamp(reset($message['mails'])['events']['delivered']) : ''; ?></td>
									</tr>
									<?php if (count($message['mails']) > 1): ?>
										<?php $keys = array_keys($message['mails']);?>
										<?php for ($i = 1; $i < count($message['mails']); $i++): ?>
											<tr>
												<!-- <td></td> -->
												<td></td>
												<td><?php echo htmlentities($message['mails'][$keys[$i]]['from']); ?></td>
												<td><?php echo htmlentities($message['mails'][$keys[$i]]['to']); ?></td>
												<td><?php echo isset($message['mails'][$keys[$i]]['events']['accepted']) ? \Carbon\Carbon::createFromTimestamp($message['mails'][$keys[$i]]['events']['accepted']) : ''; ?></td>
												<td><?php echo isset($message['mails'][$keys[$i]]['events']['delivered']) ? \Carbon\Carbon::createFromTimestamp($message['mails'][$keys[$i]]['events']['delivered']) : ''; ?></td>
											</tr>
										<?php endfor;?>
									<?php endif;?>
								<?php endforeach;?>
							</tbody>
						</table>
					<?php else: ?>
						<p>Geen resultaten gevonden</p>
					<?php endif;?>
				<?php else: ?>
					<p>Voer links een zoekopdracht in</p>
				<?php endif;?>
			</div>
		</div>

		<?php // $events = _get_events(['from' => 'gerrit']);?>
	</div>

</body>
</html>
