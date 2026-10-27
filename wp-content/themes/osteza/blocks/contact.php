<?php
/**
 * Contact section with the WhatsApp/Telegram form. $a: block attributes.
 */

$current = osteza_current_service();
get_template_part(
	'template-parts/contact',
	null,
	array(
		'kicker'   => $a['kicker'],
		'title'    => $a['title'],
		'lead'     => $a['lead'],
		'channels' => (bool) $a['channels'],
		'service'  => $a['service'] ?: ( $current ? $current['name'] : '' ),
		'town'     => $a['town'],
	)
);
