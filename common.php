<?php

use Carbon\Carbon;
use Mailgun\Mailgun;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$mailgun = false;

function _get_mailgun()
{
    global $mailgun, $config;

    if ($mailgun) {
        return $mailgun;
    }

    if ($config['europe']) {
        return $mailgun = Mailgun::create($config['api_key'], 'https://api.eu.mailgun.net');
    }

    return $mailgun = Mailgun::create($config['api_key']);
}

function _get_filters()
{
    $filters = [];

    $fields = ['from', 'to'];

    foreach ($fields as $field) {
        if ( !  ! $_POST[$field]) {
            $filters[$field] = $_POST[$field];
        }
    }

    return $filters;
}

function _get_events($filters)
{
    global $config;

    $params = [];

    if (isset($filters['from'])) {
        $params['from'] = $filters['from'];
    }

    if (isset($filters['to'])) {
        $params['to'] = $filters['to'];
    }

    return _get_mailgun()->events()->get($config['domain'], $params);
}

function _has_searched()
{
    return (isset($_POST) && count($_POST) > 0 && isset($_POST['type']) && (isset($_POST['from']) || isset($_POST['to'])));
}

function _download()
{
    return isset($_POST['type']) && $_POST['type'] === 'to_excel';
}

function _build_xslx($messages)
{
    $spreadsheet = new Spreadsheet();

    $output = [
        ['id', 'onderwerp', 'van', 'naar', 'geaccepteerd', 'afgeleverd'],
    ];

    foreach ($messages as $id => $message) {

        $accepted = (isset(reset($message['mails'])['events']['accepted'])) ? Carbon::createFromTimestamp(reset($message['mails'])['events']['accepted']) : '';
        $deliverd = (isset(reset($message['mails'])['events']['delivered'])) ? Carbon::createFromTimestamp(reset($message['mails'])['events']['delivered']) : '';

        $output[] = [
            $id,
            $message['subject'],
            reset($message['mails'])['from'],
            reset($message['mails'])['to'],
            $accepted,
            $deliverd,
        ];

        if (count($message['mails']) > 1):
            $keys = array_keys($message['mails']);
            for ($i = 1; $i < count($message['mails']); $i++):
                $output[] = [
                    '',
                    '',
                    $message['mails'][$keys[$i]]['from'],
                    $message['mails'][$keys[$i]]['to'],
                    Carbon::createFromTimestamp($message['mails'][$keys[$i]]['events']['accepted']),
                    Carbon::createFromTimestamp($message['mails'][$keys[$i]]['events']['delivered']),
                ];

            endfor;
        endif;
    }

    $spreadsheet->getActiveSheet()
                ->fromArray(
                    $output,
                    null,
                    'A1'

                );

    $spreadsheet->getActiveSheet()->calculateColumnWidths();

    $writer = new Xlsx($spreadsheet);

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="MailExport.xlsx"');
    header('Cache-Control: max-age=0');

    $writer->save('php://output');

    exit();
}

function _prepare_messages($events)
{
    $messages = [];

    foreach ($events as $event) {
        $message_id = '';
        $headers    = $event->getMessage()['headers'];
        $message_id = $headers['message-id'];

        if ( ! isset($messages[$message_id])) {
            $messages[$message_id] = [
                'subject' => $headers['subject'],
            ];
        }

        $message_sub_id = base64_encode($headers['from'].$event->getRecipient());

        if ( ! isset($messages[$message_id]['mails'][$message_sub_id])) {
            $messages[$message_id]['mails'][$message_sub_id] = [
                'from'   => $headers['from'],
                'to'     => $event->getRecipient(),
                'events' => [],
            ];
        }

        $messages[$message_id]['mails'][$message_sub_id]['events'][$event->getEvent()] = $event->getTimestamp();

    }

    return $messages;
}
