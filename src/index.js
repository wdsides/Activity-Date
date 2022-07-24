import classnames from 'classnames';
import { registerBlockType } from '@wordpress/blocks';
import { useSelect } from '@wordpress/data';
import { useEntityProp } from '@wordpress/core-data';
import {
	AlignmentControl,
	BlockControls,
	useBlockProps,
} from '@wordpress/block-editor';

registerBlockType( 'willsides/activity-date', {

	edit( { attributes: { textAlign }, setAttributes } ) {
        const blockProps = useBlockProps( {
            className: classnames( {
                [ `has-text-align-${ textAlign }` ]: textAlign,
            } ),
        } );
        const postType = useSelect(
            ( select ) => select( 'core/editor' ).getCurrentPostType(),
            []
        );
 
        const [ meta ] = useEntityProp( 'postType', postType, 'meta' );
        const month = ["January","February","March","April","May","June","July","August","September","October","November","December"];

        const activity_date = new Date(meta[ 'activity_date' ]);
        const activity_end_date = new Date(meta[ 'activity_end_date' ]);
		let activity_date_tags = ( activity_date.toString()=='Invalid Date' ) ? (
			<span>Invalid Date.</span>
		) : (
			<time datetime={ activity_date }>
                { month[activity_date.getUTCMonth()] } { activity_date.getUTCDate() }, { activity_date.getUTCFullYear() }
            </time>
		)
        let end_date_tags = ( activity_end_date.toString()=='Invalid Date' ) ? (
			''
		) : (
            <span> - <time datetime={ activity_end_date }>
                    { month[activity_date.getUTCMonth()] } { activity_end_date.getUTCDate() }, { activity_end_date.getUTCFullYear() }
            </time></span>
		)
		console.log(activity_date)

        return (
            <>
                <BlockControls group="block">
                    <AlignmentControl
                        value={ textAlign }
                        onChange={ ( nextAlign ) => {
                            setAttributes( { textAlign: nextAlign } );
                        } }
                    />
                </BlockControls>
                <div { ...blockProps }>
                    { activity_date_tags }
                    { end_date_tags}
                </div>
            </>
        );
    },

	save() {
        return null;
    },
} );
