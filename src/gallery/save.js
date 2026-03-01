import { useBlockProps, InnerBlocks } from "@wordpress/block-editor";

export default function save({ attributes }) {
    const { title } = attributes;

    return (
        <section { ...useBlockProps.save() }>
            <InnerBlocks.Content />
        </section>
    )
}