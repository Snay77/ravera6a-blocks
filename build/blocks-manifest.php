<?php
// This file is generated. Do not modify it manually.
return array(
	'gallery' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'ravera/gallery',
		'title' => 'Galerie maçonnerie',
		'category' => 'media',
		'icon' => 'format-gallery',
		'description' => 'Galerie d\'images en maçonnerie avec chargement progressif.',
		'textdomain' => 'ravera-blocks',
		'attributes' => array(
			'ids' => array(
				'type' => 'array',
				'items' => array(
					'type' => 'number'
				),
				'default' => array(
					
				)
			),
			'perPage' => array(
				'type' => 'number',
				'default' => 9
			),
			'columns' => array(
				'type' => 'number',
				'default' => 3
			),
			'gap' => array(
				'type' => 'number',
				'default' => 12
			),
			'imageSize' => array(
				'type' => 'string',
				'default' => 'large'
			),
			'isMasonry' => array(
				'type' => 'boolean',
				'default' => true
			)
		),
		'supports' => array(
			'html' => false
		),
		'editorScript' => 'ravera-gallery-editor',
		'editorStyle' => 'ravera-gallery-editor',
		'style' => 'ravera-gallery-front',
		'render' => 'file:./render.php',
		'viewScript' => 'ravera-gallery-view'
	)
);
