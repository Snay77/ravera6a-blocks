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
			'showLoadMore' => array(
				'type' => 'boolean',
				'default' => true
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
		'editorScript' => 'file:./index.js',
		'editorStyle' => 'file:./index.css',
		'style' => 'file:./style-index.css',
		'render' => 'file:./render.php',
		'viewScript' => 'file:./view.js'
	)
);
