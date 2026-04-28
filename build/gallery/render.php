<?php

/**
 * Server-side rendering for ravera/gallery
 *
 * @param array $attributes Block attributes.
 * @return string
 */

$ids = isset($attributes['ids']) && is_array($attributes['ids']) ? array_map('intval', $attributes['ids']) : [];
if (empty($ids)) {
	return '';
}

$per_page       = isset($attributes['perPage']) ? max(1, (int) $attributes['perPage']) : 9;
$show_load_more = isset($attributes['showLoadMore']) ? (bool) $attributes['showLoadMore'] : true;
$columns        = isset($attributes['columns']) ? max(1, (int) $attributes['columns']) : 3;
$gap            = isset($attributes['gap']) ? max(0, (int) $attributes['gap']) : 12;
$size           = isset($attributes['imageSize']) ? sanitize_key($attributes['imageSize']) : 'large';
$is_masonry     = isset($attributes['isMasonry']) ? (bool) $attributes['isMasonry'] : true;

$total = count($ids);
$first_ids = $show_load_more ? array_slice($ids, 0, $per_page) : $ids;

$wrapper_attributes = get_block_wrapper_attributes([
	'style' => sprintf('--ravera-cols:%d;--ravera-gap:%dpx;', $columns, $gap),
	'data-ravera-gallery' => '1',
	'data-ids' => wp_json_encode($ids),
	'data-per-page' => (string) $per_page,
	'data-size' => $size,
	'data-masonry' => $is_masonry ? '1' : '0',
]);

?>

<div <?php echo $wrapper_attributes; ?>>

	<div class="ravera-gallery <?php echo $is_masonry ? 'is-masonry' : 'is-grid'; ?>" aria-live="polite">
		<?php foreach ($first_ids as $id): ?>
			<div class="ravera-gallery__item">
				<?php echo wp_get_attachment_image($id, $size); ?>
			</div>
		<?php endforeach; ?>
	</div>

	<?php if ($show_load_more && $total > $per_page): ?>
		<div class="wp-block-buttons ravera-gallery__actions">
			<div class="wp-block-button">
				<button
					type="button"
					class="wp-block-button__link wp-element-button ravera-gallery__more">
					<?php echo esc_html__('Voir plus', 'ravera-blocks'); ?>
				</button>
			</div>
		</div>
	<?php endif; ?>

</div>