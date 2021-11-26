/**
 * Import packages
 */
import { useBlockProps, MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { TextControl, Placeholder, Button } from '@wordpress/components';
import { getBlockType } from '@wordpress/blocks';
import { Fragment } from '@wordpress/element';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */
export default function Edit(props) {
	const { attributes, setAttributes, name } = props;

	// Get the arguments from PHP register block array
	const blockType = getBlockType(name);


	const removeMedia = () => {
		setAttributes({
			buttonImageID: 0,
			buttonImage: paypal_donations_block_settings.defaultButtonUrl
		});
	}

	const onSelectMedia = (media) => {
		setAttributes({
			buttonImageID: media.id,
			buttonImage: media.url
		});
	}

	// Return the rendered element
	return (
		<div {...useBlockProps()}>
			<Placeholder
				icon="heart"
				label={blockType.title}
				instructions={blockType.description}
				className="donations-block-wrapper"
			>

				<TextControl
					label={paypal_donations_block_settings.i18n.donationAccountLabel}
					className="paypal-donation-donation-key"
					value={attributes.donationAccount}
					onChange={(val) => setAttributes({ donationAccount: val })}
				/>
				<TextControl
					label={paypal_donations_block_settings.i18n.donationButtonIDLabel}
					className="paypal-donation-donation-key"
					value={attributes.donationButtonID}
					onChange={(val) => setAttributes({ donationButtonID: val })}
					required
				/>

				<TextControl
					label={paypal_donations_block_settings.i18n.buttonAltLabel}
					className="paypal-donation-button-alt"
					value={attributes.buttonAlt}
					onChange={(val) => setAttributes({ buttonAlt: val })}
				/>
				<TextControl
					label={paypal_donations_block_settings.i18n.buttonTitleLabel}
					className="paypal-donation-button-alt"
					value={attributes.buttonTitle}
					onChange={(val) => setAttributes({ buttonTitle: val })}
				/>

				<Fragment>
					<div className="paypal-donation-button-image">
						<MediaUploadCheck>
							<MediaUpload
								onSelect={onSelectMedia}
								value={attributes.buttonImageID}
								allowedTypes={['image']}
								render={({ open }) => (
									<Fragment>
										<div>
											<img src={attributes.buttonImage ?? paypal_donations_block_settings.defaultButtonUrl} alt={attributes.buttonAlt} title={attributes.buttonTitle}/>
										</div>
										<Button className='paypal-donation-button-image__toggle' onClick={open}>
											Choose an image
										</Button>
									</Fragment>
								)}
							/>
						</MediaUploadCheck>
						{attributes.mediaId != 0 && 
							<MediaUploadCheck>
								<Button onClick={removeMedia} isLink isDestructive>{'Remove image'}</Button>
							</MediaUploadCheck>
						}
					</div>
				</Fragment>
			</Placeholder>
		</div>
	);
}
