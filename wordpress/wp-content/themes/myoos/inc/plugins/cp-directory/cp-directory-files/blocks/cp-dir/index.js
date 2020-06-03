( function( wp ) {
	/**
	 * Registers a new block provided a unique name and an object defining its behavior.
	 * @see https://wordpress.org/gutenberg/handbook/designers-developers/developers/block-api/#registering-a-block
	 */
	var registerBlockType = wp.blocks.registerBlockType;
	/**
	 * Returns a new element of given type. Element is an abstraction layer atop React.
	 * @see https://wordpress.org/gutenberg/handbook/designers-developers/developers/packages/packages-element/
	 */
	var el = wp.element.createElement;
	/**
	 * Retrieves the translation of text.
	 * @see https://wordpress.org/gutenberg/handbook/designers-developers/developers/packages/packages-i18n/
	 */
	var __ = wp.i18n.__;

	var InspectorControls = wp.blockEditor.InspectorControls;
	var serverSideRender = wp.serverSideRender;
	var withSelect = window.wp.data.withSelect;
	var { ToggleControl, CheckboxControl, SelectControl, TreeSelect, PanelBody, Text } = wp.components;
	var { groupBy, cloneDeep } = lodash;

	/**
	 * Every block starts by registering a new block type definition.
	 * @see https://wordpress.org/gutenberg/handbook/designers-developers/developers/block-api/#registering-a-block
	 */
	registerBlockType( 'cp-dir/cp-dir', {
		/**
		 * This is the display title for your block, which can be translated with `i18n` functions.
		 * The block inserter will show this name.
		 */
		title: __( 'Directory', 'cp-dir' ),

		/**
		 * An icon property should be specified to make it easier to identify a block.
		 * These can be any of WordPress’ Dashicons, or a custom svg element.
		 */
		icon: 'index-card',

		/**
		 * Blocks are grouped into categories to help users browse and discover them.
		 * The categories provided by core are `common`, `embed`, `formatting`, `layout` and `widgets`.
		 */
		category: 'widgets',

		/**
		 * Optional block extended support features.
		 */
		supports: {
			// Removes support for an HTML mode.
			html: false,
		},

		attributes: {
			source: {
				type: 'string',
				default: CPDir[0].name
			},
			categories: {
				type: 'object',
				default: {}
			},
			filters: {
				type: 'object',
				default: {}
			},
			fields: {
				type: 'object',
				default: {}
			},
		},

		/**
		 * The edit function describes the structure of your block in the context of the editor.
		 * This represents what the editor will render when the block is used.
		 * @see https://wordpress.org/gutenberg/handbook/designers-developers/developers/block-api/block-edit-save/#edit
		 *
		 * @param {Object} [props] Properties passed from the editor.
		 * @return {Element}       Element to render.
		 */
		edit: withSelect( function( select ) {
			categories = {};

			CPDir.forEach((postTypeDetails) => {
				postTypeDetails.taxonomies.forEach((taxDetails) => {
					categories[taxDetails.name] = select('core').getEntityRecords('taxonomy', taxDetails.name);
				});
			});

			return { categories };
		} )( function( props ) {
			var sourceOptions = [];
			var categoriesOptions = {};
			var taxAddedMap = {};

			var settings = [];
			var settingsCategories = [];
			var settingsFilters = [];
			var settingsFields = [];

			CPDir.forEach((postTypeDetails) => {
				sourceOptions.push({value:postTypeDetails.name, label:postTypeDetails.label});
				
				// Sets up Categories to choose.
				postTypeDetails.taxonomies.forEach((taxDetails) => {
					categoriesOptions[taxDetails.name] = buildTermsTree( props.categories[taxDetails.name] );
					
					if( categoriesOptions[taxDetails.name].length > 1 ) {
						if( !( taxDetails.name in taxAddedMap ) ) {
							taxAddedMap[taxDetails.name] = [ postTypeDetails.name ];

							settingsCategories.push(
								el(TreeSelect, {
									selectedId: props.attributes.categories[taxDetails.name],
									noOptionLabel: __( 'All', 'cp-dir' ),
									label: taxDetails.label,
									className: taxAddedMap[taxDetails.name].indexOf(props.attributes.source) === -1 ? 'hidden' : '',
									onChange: function(value){
										var categories_updated = cloneDeep(props.attributes.categories);
										categories_updated[taxDetails.name] = value;
										props.setAttributes( { categories: categories_updated } );
									},
									tree: categoriesOptions[taxDetails.name]
								})
							);
						}
						else {
							taxAddedMap[taxDetails.name].push( postTypeDetails.name );
						}
					}
				});

				// Sets up filters
				Object.keys(postTypeDetails.filters).forEach(function (filter_key) {
					settingsFilters.push(
						el( CheckboxControl, {
							value: filter_key,
							label: postTypeDetails.filters[filter_key]['label'],
							checked: ( postTypeDetails.name in props.attributes.filters && props.attributes.filters[postTypeDetails.name].indexOf(filter_key) !== -1 ) ? true : false,
							className: props.attributes.source != postTypeDetails.name ? 'hidden' : '',
							onChange: function( value ) {
								var filters_updated = cloneDeep(props.attributes.filters);
								if( ! ( postTypeDetails.name in filters_updated ) ) {
									filters_updated[postTypeDetails.name] = [];
								}

								index = filters_updated[postTypeDetails.name].indexOf(filter_key);
								if (index !== -1) {
									filters_updated[postTypeDetails.name].splice(index, 1);
								}
								else {
									filters_updated[postTypeDetails.name].push(filter_key);
								}
								
								props.setAttributes( { filters: filters_updated } );
							},
						} )
					);
				});

				// Sets up fields for filtering
				Object.keys(postTypeDetails.fields).forEach(function (field_key) {
					if( !postTypeDetails.fields[field_key]['default'] ) {
						settingsFields.push(
							el( CheckboxControl, {
								value: field_key,
								label: postTypeDetails.fields[field_key]['label'],
								checked: ( postTypeDetails.name in props.attributes.fields && props.attributes.fields[postTypeDetails.name].indexOf(field_key) !== -1 ) ? true : false,
								className: props.attributes.source != postTypeDetails.name ? 'hidden' : '',
								onChange: function( value ) {
									var fields_updated = cloneDeep(props.attributes.fields);
									if( ! ( postTypeDetails.name in fields_updated ) ) {
										fields_updated[postTypeDetails.name] = [];
									}

									index = fields_updated[postTypeDetails.name].indexOf(field_key);
									if (index !== -1) {
										fields_updated[postTypeDetails.name].splice(index, 1);
									}
									else {
										fields_updated[postTypeDetails.name].push(field_key);
									}
									
									props.setAttributes( { fields: fields_updated } );
								},
							} )
						);
					}
				});
			});

			if( sourceOptions.length > 1 ) {
				settings.push(
					el( SelectControl, {
						value: props.attributes.source,
						label: __( 'Source' ),
						onChange: function(value){
							props.setAttributes( { source: value } );
						},
						options: sourceOptions
					} )
				);
			}

			settings.push( settingsCategories );
			
			if( settingsFilters.length ) {
				settings.push(
					el( 'div', {
							class: "cp-dir-checkbox-list"
						}, 
						[
							el( 'h3', {},
								__( 'Choose filters to display:', 'cp-school' )
							),
							settingsFilters,
						] 
					),
				);
			}

			if( settingsFields.length ) {
				settings.push(
					el( 'div', {
							class: "cp-dir-checkbox-list"
						}, 
						[
							el( 'h3', {},
								__( 'Choose fields to display:', 'cp-school' )
							),
							settingsFields,
						] 
					),
				);
			}

			settings.push(
				el( 'style', {},
					'.cp-dir-checkbox-list:not(:last-child) { margin-bottom: 2em; } .cp-dir-checkbox-list .components-base-control { margin-bottom: 0px !important; }'
				),
			);

			/*
			settings.push(
				el( SelectControl, {
					value: props.attributes.sort_by,
					label: __( 'Sort By', 'cp-dir' ),
					onChange: function( value ){
						props.setAttributes( { sort_by: value } );
					},
					className: 'hidden',
					options: []
				} )
			);*/

			/**
			 * Returns terms in a tree form.
			 *
			 * @param {Array} flatTerms  Array of terms in flat format.
			 *
			 * @return {Array} Array of terms in tree format.
			 */
			function buildTermsTree( flatTerms ) {
				const termsByParent = groupBy( flatTerms, 'parent' );
				const fillWithChildren = ( terms ) => {
					return terms.map( ( term ) => {
						const children = termsByParent[ term.id ];
						return {
							...term,
							children: children && children.length ?
								fillWithChildren( children ) :
								[],
						};
					} );
				};

				return fillWithChildren( termsByParent[ '0' ] || [] );
			}

			return el('div', {}, [
				el( 'div', {}, el( serverSideRender, {
					block: "cp-dir/cp-dir",
					attributes: props.attributes,
				} ) ),
				el( InspectorControls, {},
					el( PanelBody, { title: __( 'Directory Settings', 'cp-dir' ), initialOpen: true }, settings )
				)
			] )
		} ),

		/**
		 * The save function defines the way in which the different attributes should be combined
		 * into the final markup, which is then serialized by Gutenberg into `post_content`.
		 * @see https://wordpress.org/gutenberg/handbook/designers-developers/developers/block-api/block-edit-save/#save
		 *
		 * @return {Element}       Element to render.
		 */
		save: function() {
			return el(
				'p',
				{},
				__( 'Hello from the saved content!', 'cp-school' )
			);
		}
	} );
} )(
	window.wp
);
