import { __ } from '@wordpress/i18n';
import { useSelect } from '@wordpress/data';
import {
	useBlockProps,
	InspectorControls,
	MediaUpload,
	MediaUploadCheck,
} from '@wordpress/block-editor';
import {
	PanelBody,
	RangeControl,
	Button,
	SelectControl,
	ToggleControl,
} from '@wordpress/components';
import './editor.scss';

export default function Edit({ attributes, setAttributes }) {
	const { ids, perPage, columns, gap, imageSize, isMasonry, showLoadMore } = attributes;

	const onSelectImages = (media) => {
		const mediaArray = Array.isArray(media) ? media : media ? [media] : [];
		const newIds = mediaArray.map((m) => m.id).filter(Boolean);
		setAttributes({ ids: newIds });
	};

	const blockProps = useBlockProps({
		style: {
			'--ravera-cols': columns,
			'--ravera-gap': `${gap}px`,
		},
	});

	const images = useSelect(
		(select) => ids.map((id) => select('core').getMedia(id)).filter(Boolean),
		[ids]
	);

	return (
		<>
			<InspectorControls>
				<PanelBody title={__('Réglages', 'ravera-blocks')} initialOpen>
					<ToggleControl
						label={__('Activer la maçonnerie', 'ravera-blocks')}
						checked={isMasonry}
						onChange={(value) => setAttributes({ isMasonry: value })}
					/>

					<ToggleControl
						label={__('Activer le bouton “Voir plus”', 'ravera-blocks')}
						help={
							showLoadMore
								? __('Les images se chargent progressivement.', 'ravera-blocks')
								: __('Toutes les images seront chargées dès le début.', 'ravera-blocks')
						}
						checked={showLoadMore}
						onChange={(value) => setAttributes({ showLoadMore: value })}
					/>

					{showLoadMore && (
						<RangeControl
							label={__('Images par chargement', 'ravera-blocks')}
							value={perPage}
							onChange={(v) => setAttributes({ perPage: v })}
							min={3}
							max={30}
						/>
					)}

					<RangeControl
						label={__('Colonnes', 'ravera-blocks')}
						value={columns}
						onChange={(v) => setAttributes({ columns: v })}
						min={1}
						max={6}
					/>

					<RangeControl
						label={__('Espacement (px)', 'ravera-blocks')}
						value={gap}
						onChange={(v) => setAttributes({ gap: v })}
						min={0}
						max={40}
					/>

					<SelectControl
						label={__('Taille image', 'ravera-blocks')}
						value={imageSize}
						onChange={(v) => setAttributes({ imageSize: v })}
						options={[
							{ label: 'thumbnail', value: 'thumbnail' },
							{ label: 'medium', value: 'medium' },
							{ label: 'large', value: 'large' },
							{ label: 'full', value: 'full' },
						]}
					/>
				</PanelBody>
			</InspectorControls>

			<div {...blockProps}>
				<div className="ravera-gallery__toolbar">
					<MediaUploadCheck>
						<MediaUpload
							onSelect={onSelectImages}
							allowedTypes={['image']}
							multiple
							gallery
							value={ids}
							render={({ open }) => (
								<Button variant="primary" onClick={open}>
									{ids?.length
										? __('Modifier la galerie', 'ravera-blocks')
										: __('Choisir des images', 'ravera-blocks')}
								</Button>
							)}
						/>
					</MediaUploadCheck>

					{ids?.length ? (
						<Button
							variant="secondary"
							onClick={() => setAttributes({ ids: [] })}
						>
							{__('Vider', 'ravera-blocks')}
						</Button>
					) : null}
				</div>

				{ids?.length ? (
					<p className="ravera-gallery__hint">
						{showLoadMore
							? __('Sur le site : chargement progressif avec bouton “Voir plus”.', 'ravera-blocks')
							: __('Sur le site : toutes les images seront chargées dès le début.', 'ravera-blocks')}
					</p>
				) : (
					<p className="ravera-gallery__hint">
						{__('Sélectionne des images pour créer une galerie.', 'ravera-blocks')}
					</p>
				)}

				{images?.length ? (
					<div className={`ravera-gallery ravera-gallery--editor ${isMasonry ? 'is-masonry' : 'is-grid'}`}>
						{images.map((img) => {
							const url =
								img?.media_details?.sizes?.medium?.source_url ||
								img?.media_details?.sizes?.thumbnail?.source_url ||
								img?.source_url;

							return (
								<div key={img.id} className="ravera-gallery__item">
									<img src={url} alt={img.alt_text || ''} loading="lazy" />
								</div>
							);
						})}
					</div>
				) : null}
			</div>
		</>
	);
}