import { InnerBlocks, useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import './editor.scss';

export default function Edit({ attributes, setAttributes }) {
    const { title } = attributes;

    return (
        <section { ...useBlockProps() }>
            <InnerBlocks allowedBlocks={[ 'core/image' ]} />
        </section>
    )
}