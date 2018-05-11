<?php
/**
 * Change colors regarding user choices in customizer
 *
 * @package coup
 */


function coup_change_colors() {

/**
 * GENERAL THEME COLORS
 */

$body_bg_color             = get_theme_mod( 'coup_body_bg_color', '#fff' );
$main_color                = get_theme_mod( 'coup_main_color', '#eee' );
$heading_color             = get_theme_mod( 'coup_heading_color', '#000' );
$navigation_color          = get_theme_mod( 'coup_navigation_color', '#000' );
$fullwidth_navigation_color= get_theme_mod( 'coup_fullwidth_navigation_color', '#000' );
$paragraphs_color          = get_theme_mod( 'coup_paragraphs_color', '#000' );
$meta_link_color           = get_theme_mod( 'coup_meta_link_color', '#000' );
$selection_color           = get_theme_mod( 'coup_selection_color', '#f9ce4e' );

$change_colors_style = '

	/* Body BG color */

	body,
	.menu-open .sidebar-nav-holder,
	.menu-open .sidebar-nav-holder:before,
	.menu-open .sidebar-nav-holder:after,
	div.sharedaddy .sd-content {
		background-color:'. esc_attr( $body_bg_color ) .';
	}

	@media screen and (max-width: 1200px) {
		.main-navigation > div {
		    background:'. esc_attr( $body_bg_color ) .';
		}
	}

	.fullwidth-slider .featured-slider {
		background:'. esc_attr( $body_bg_color ) .';
	}

	/* Main color */

	pre,
	.blog article.no-featured-content .archive-background,
	.archive article.no-featured-content .archive-background,
	article.type-jetpack-portfolio.no-featured-content .archive-background,
	body #eu-cookie-law {
		background-color:'. esc_attr( $main_color ) .';
	}

	@media screen and (min-width: 1201px) {
		.main-navigation ul ul {
			background:'. esc_attr( $main_color ) .';
		}
	}

	@media screen and (max-width: 1200px) {
		.category-filter ul {
		    background:'. esc_attr( $main_color ) .';
		}
	}

	.big-text,
	.archive .page-title.big-text,
	body:not(.search) .woo-page .page-title {
		color:'. esc_attr( $main_color ) .';
	}


	/* Headings color */

	h1, h2, h3, h4, h5, h6,
	h1 a, h2 a, h3 a, h4 a, h5 a, h6 a,
	h1 a:visited, h2 a:visited, h3 a:visited, h4 a:visited, h5 a:visited, h6 a:visited,
	h2.widget-title,
	.entry-content h1,
	.entry-content h2,
	.entry-content h3,
	.entry-content h4,
	.entry-content h5,
	.entry-content h6,
	.row .format-quote blockquote,
	.comment-author b,
	.comment-author b a,
	.comment-author b a:visited,
	body.home .featured-slider-wrapper + .site-main .page-title {
		color: '. esc_attr( $heading_color ) .';
	}

	.comments-title {
		border-bottom-color: '. esc_attr( $heading_color ) .';
	}

	/* Headings hover color */

	h1 a:hover,
	h2 a:hover,
	h3 a:hover,
	h4 a:hover,
	h5 a:hover,
	h6 a:hover,
	h1 a:focus,
	h2 a:focus,
	h3 a:focus,
	h4 a:focus,
	h5 a:focus,
	h6 a:focus,
	h1 a:active,
	h2 a:active,
	h3 a:active,
	h4 a:active,
	h5 a:active,
	h6 a:active,
	.comment-author b a:hover,
	.comment-author b a:focus,
	.comment-author b a:active {
		color:'. coup_hex2rgba( $heading_color , 0.4 ) .';
	}

	/* Paragraph color */

	pre,
	mark, ins {
		background-color: '. coup_hex2rgba( $paragraphs_color , 0.08 ) .';
	}

	body,
	body:not(.single-jetpack-portfolio) .archive-meta,
	body #infinite-footer .blog-credits {
		color: '. esc_attr( $paragraphs_color ) .';
	}

	body .contact-form label {
		color: '. coup_hex2rgba( $paragraphs_color , 0.5 ) .';
	}

	body .contact-form label span {
		color: '. coup_hex2rgba( $paragraphs_color , 0.3 ) .';
	}

	.search article:not(:last-of-type) {
	    border-bottom-color: '. esc_attr( $paragraphs_color ) .';
	}

	.entry-content td, .entry-content th, .comment-content td, .comment-content th {
		border-color: '. esc_attr( $paragraphs_color ) .';
	}


	/* Meta color */

	a,
	a:visited,
	button,
	body #infinite-footer .blog-info a:hover,
	body #infinite-footer .blog-credits a:hover,
	.site-info a,
	.comment-notes,
	.comment-metadata a,
	.widget_wpcom_social_media_icons_widget a,
	.entry-meta,
	.single .entry-footer a:hover,
	.single .entry-footer a:focus,
	.single .entry-footer a:active,
	.single .entry-footer .meta-text,
	body #infinite-handle span button,
	body #infinite-handle span button:focus,
	body #infinite-handle span button:hover,
	.navigation-to-shop a,
	.navigation-to-shop a:focus,
	.navigation-to-shop a:hover,
	.paging-navigation,
	.paging-navigation a:hover,
	.post-format-type:focus, .post-format-type:hover, .post-format-type:active,
	.products .product .woocommerce-LoopProduct-link:hover {
		color:'. esc_attr( $meta_link_color ) .';
	}

	.spinner > div > div {
		background: '. esc_attr( $meta_link_color ) .' !important;
	}

	.posts-navigation a:active,
	.posts-navigation a:hover,
	.posts-navigation a:focus,
	.entry-meta a:hover,
	.entry-meta a:focus,
	.entry-meta a:active {
		color:'. coup_hex2rgba( $meta_link_color, 0.7 ) .';
	}

	.single .entry-footer,
	.single .entry-footer a,
	.more-link:hover,
	.more-link:focus,
	.more-link:active {
		color:'. coup_hex2rgba( $meta_link_color, 0.4 ) .';
	}

	a:hover, a:active, a:focus,
	#infinite-footer .blog-info a,
	body #infinite-footer .blog-credits a,
	.site-info a:hover,
	.site-info a:focus,
	.site-info a:active,
	.comment-metadata a:hover,
	.widget_wpcom_social_media_icons_widget a:focus,
	.widget_wpcom_social_media_icons_widget a:hover {
		color:'. coup_hex2rgba( $meta_link_color, 0.7 ) .';
	}

	.more-link:after,
	body #infinite-handle span:after,
	.navigation-to-shop a:after {
		background-color: '. esc_attr( $meta_link_color ) .';
	}

	.more-link:hover:after, .more-link:focus:after, .more-link:active:after {
		background-color: '. coup_hex2rgba( $meta_link_color, 0.4 ) .';
	}

	.slick-dots button,
	.slick-dots button:hover, .slick-dots button:focus, .slick-dots button:active,
	a.page-numbers, span.current, span.disabled, .paging-navigation > a {
		border-bottom-color: '. esc_attr( $meta_link_color ) .';
	}

			/* # forms */

	button,
	input[type="button"],
	input[type="reset"],
	input[type="submit"] {
		border-color: '. esc_attr( $meta_link_color ) .';
		color: '. esc_attr( $meta_link_color ) .';
	}

	button:hover,
	input[type="button"]:hover,
	input[type="reset"]:hover,
	input[type="submit"]:hover,
	button:focus,
	input[type="button"]:focus,
	input[type="reset"]:focus,
	input[type="submit"]:focus,
	button:active,
	input[type="button"]:active,
	input[type="reset"]:active,
	input[type="submit"]:active {
		background: '. esc_attr( $meta_link_color ) .';
		color: '. esc_attr( $body_bg_color ) .';
	}

	input[type="text"],
	input[type="email"],
	input[type="url"],
	input[type="password"],
	input[type="search"],
	input[type="number"],
	input[type="tel"],
	input[type="range"],
	input[type="date"],
	input[type="month"],
	input[type="week"],
	input[type="time"],
	input[type="datetime"],
	input[type="datetime-local"],
	input[type="color"],
	textarea {
		color: '. esc_attr( $meta_link_color ) .';
		border-bottom-color: '. esc_attr( $meta_link_color ) .';
	}

	select {
		border-color: '. esc_attr( $meta_link_color ) .';
	}

	input[type="text"]:focus,
	input[type="email"]:focus,
	input[type="url"]:focus,
	input[type="password"]:focus,
	input[type="search"]:focus,
	input[type="number"]:focus,
	input[type="tel"]:focus,
	input[type="range"]:focus,
	input[type="date"]:focus,
	input[type="month"]:focus,
	input[type="week"]:focus,
	input[type="time"]:focus,
	input[type="datetime"]:focus,
	input[type="datetime-local"]:focus,
	input[type="color"]:focus,
	textarea:focus {
		color: '. esc_attr( $meta_link_color ) .';
	}

	textarea {
		border-bottom-color: '. esc_attr( $meta_link_color ) .';
	}

	label.checkbox,
	input[type="checkbox"] + label,
	form.contact-form label.checkbox,
	form.contact-form input[type="checkbox"] + label,
	label.radio,
	input[type="radio"] + label,
	form.contact-form label.radio,
	form.contact-form input[type="radio"] + label {
		color: '. esc_attr( $meta_link_color ) .';
	}

	label.checkbox:before,
	input[type="checkbox"] + label:before,
	label.radio:before,
	input[type="radio"] + label:before {
		border-color: '. esc_attr( $meta_link_color ) .';
		color: '. esc_attr( $meta_link_color ) .';
	}

	label.checkbox:hover:before,
	input[type="checkbox"] + label:hover:before,
	label.radio:hover:before,
	input[type="radio"] + label:hover:before {
		background: '. esc_attr( $meta_link_color ) .';
	}

	.checkbox.checked:hover:before,
	input[type="checkbox"]:checked + label:hover:before,
	.radio.checked:hover:before,
	input[type="radio"]:checked + label:hover:before {
		color: '. esc_attr( $body_bg_color ) .';
	}

	div #respond #comment-form-comment,
	div #comment-form-share-text-padder {
		border-bottom-color: '. esc_attr( $meta_link_color ) .';
	}

	div #respond .comment-form-service {
		border-bottom-color: '. esc_attr( $meta_link_color ) .';
	}

	div #respond .form-submit input,
	div #respond .form-submit input#comment-submit,
	div #respond .comment-form-fields input[type=submit],
	div #respond p.form-submit input[type=submit],
	div #respond input[type=submit],
	div #commentform #submit {
		border-color: '. esc_attr( $meta_link_color ) .';
		color: '. esc_attr( $meta_link_color ) .' !important;
	}

	div #respond .form-submit input:hover,
	div #respond .form-submit input#comment-submit:hover,
	div #respond .comment-form-fields input[type=submit]:hover,
	div #respond p.form-submit input[type=submit]:hover,
	div #respond input[type=submit]:hover,
	div #commentform #submit:hover {
		background: '. esc_attr( $meta_link_color ) .' !important;
		color: '. esc_attr( $body_bg_color ) .' !important;
	}

	.woocommerce .cart .actions input.button,
	.woocommerce .checkout_coupon input.button,
	.woocommerce-edit-address input.button,
	.woocommerce table.my_account_orders .button:hover {
		color: '. esc_attr( $body_bg_color ) .';
	}

	.woocommerce div.product p.price,
	.woocommerce div.product span.price,
	.woocommerce .products a.button:hover,
	.woocommerce .cart .actions input.button:hover,
	.woocommerce .checkout_coupon input.button:hover,
	.woocommerce-message:before,
	.woocommerce-info:before,
	.woocommerce table.my_account_orders .order-actions .button:hover,
	.woocommerce .widget_shopping_cart .cart_list li a:hover,
	.woocommerce.widget_shopping_cart .cart_list li a:hover,
	.woocommerce .widget_shopping_cart .cart_list li a.remove:before:hover,
	.woocommerce.widget_shopping_cart .cart_list li a.remove:before:hover,
	.woocommerce-cart .woocommerce .shop_table a.remove:before:hover,
	.site-header .main-navigation ul .menu-item-type-woocommerce-cart p.buttons a.button:hover,
	.widget.woocommerce.widget_recent_reviews .product_list_widget li>a:hover,
	.widget.woocommerce .product_list_widget li>a:hover .product-title {
		color: '. esc_attr( $meta_link_color ) .';
	}

	.woocommerce div.product .out-of-stock,
	.woocommerce div.product .woocommerce-tabs ul.tabs li a:hover {
		color: '. esc_attr( $paragraphs_color ) .';
	}

	.woocommerce div.product .woocommerce-tabs ul.tabs li a {
		color:'. coup_hex2rgba( $paragraphs_color, 0.3 ) .';
	}

	.woocommerce a.remove:hover,
	.woocommerce a.remove:hover:before,
	.woocommerce .cart .actions input.button:hover,
	.woocommerce .checkout_coupon input.button:hover {
		color: '. esc_attr( $meta_link_color ) .' !important;
	}

	.woocommerce a.button:hover {
		color: '. esc_attr( $meta_link_color ) .';
	}

	.woocommerce #respond input#submit.alt:hover,
	.woocommerce a.button.alt:hover,
	.woocommerce button.button.alt:hover,
	.woocommerce input.button.alt:hover,
	.woocommerce-cart .wc-proceed-to-checkout a.checkout-button:hover,
	.woocommerce #respond input#submit:hover,
	.woocommerce button.button:hover,
	.woocommerce input.button:hover {
		color: '. esc_attr( $meta_link_color ) .';
	}

	.woocommerce .widget_price_filter .ui-slider .ui-slider-handle {
		border-color: '. esc_attr( $meta_link_color ) .';
	}


	.woocommerce #respond input#submit.alt.disabled,
	.woocommerce #respond input#submit.alt.disabled:hover,
	.woocommerce #respond input#submit.alt:disabled,
	.woocommerce #respond input#submit.alt:disabled:hover,
	.woocommerce #respond input#submit.alt:disabled[disabled],
	.woocommerce #respond input#submit.alt:disabled[disabled]:hover,
	.woocommerce a.button.alt.disabled,
	.woocommerce a.button.alt.disabled:hover,
	.woocommerce a.button.alt:disabled,
	.woocommerce a.button.alt:disabled:hover,
	.woocommerce a.button.alt:disabled[disabled],
	.woocommerce a.button.alt:disabled[disabled]:hover,
	.woocommerce button.button.alt.disabled,
	.woocommerce button.button.alt.disabled:hover,
	.woocommerce button.button.alt:disabled,
	.woocommerce button.button.alt:disabled:hover,
	.woocommerce button.button.alt:disabled[disabled],
	.woocommerce button.button.alt:disabled[disabled]:hover,
	.woocommerce input.button.alt.disabled,
	.woocommerce input.button.alt.disabled:hover,
	.woocommerce input.button.alt:disabled,
	.woocommerce input.button.alt:disabled:hover,
	.woocommerce input.button.alt:disabled[disabled],
	.woocommerce input.button.alt:disabled[disabled]:hover,
	.woocommerce #respond input#submit,
	.woocommerce button.button,
	.woocommerce input.button,
	.woocommerce #respond input#submit.alt,
	.woocommerce a.button.alt,
	.woocommerce button.button.alt,
	.woocommerce input.button.alt,
	.woocommerce-cart .wc-proceed-to-checkout a.checkout-button,
	.woocommerce table.my_account_orders .button:hover {
		background-color: '. esc_attr( $meta_link_color ) .';
		color: '. esc_attr( $body_bg_color ) .';
	}

	.woocommerce #review_form #respond .comment-form-rating label,
	.woocommerce table.my_account_orders .order-actions .button,
	.woocommerce a.button,
	#add_payment_method #payment div.payment_box,
	.woocommerce-cart #payment div.payment_box,
	.woocommerce-checkout #payment div.payment_box,
	.woocommerce.widget_recent_reviews span.reviewer,
	.widget.woocommerce input[type="submit"],
	.widget.woocommerce button[type="submit"],
	.woocommerce nav.woocommerce-pagination ul li span.current,
	.woocommerce div.product form.cart .single_variation_wrap span.price,
	.woocommerce nav.woocommerce-pagination ul li a:focus,
	.woocommerce nav.woocommerce-pagination ul li a:hover,
	.woocommerce table.shop_attributes th,
	.woocommerce .product_meta span a:hover,
	.woocommerce .lost_password a:hover,
	.woocommerce #reviews #comments ol.commentlist li .comment-text .meta strong,
	.woocommerce-account .woocommerce-MyAccount-navigation ul li.is-active a,
	.woocommerce-account .woocommerce-MyAccount-navigation ul li a:hover,
	.woocommerce .widget_shopping_cart .cart_list li a:not(.remove),
	.woocommerce.widget_shopping_cart .cart_list li a:not(.remove) {
		color: '. esc_attr( $meta_link_color ) .';
	}

	.woocommerce div.product form.cart .variations select {
		color: '. esc_attr( $meta_link_color ) .';
	}

	.woocommerce nav.woocommerce-pagination ul li a,
	.woocommerce nav.woocommerce-pagination ul li span {
		border-bottom-color: '. esc_attr( $meta_link_color ) .';
	}

	.woocommerce a.remove {
		color: '. esc_attr( $meta_link_color ) .' !important;
	}

	.woocommerce #respond input#submit,
	.woocommerce a.button,
	.woocommerce button.button,
	.woocommerce input.button {
		border-color: '. esc_attr( $meta_link_color ) .';
	}

	.woocommerce div.product form.cart div.quantity {
		border-color:'. coup_hex2rgba( $meta_link_color, 0.3 ) .';
	}

	.woocommerce .products .product .star-rating:before {
		color:'. coup_hex2rgba( $meta_link_color, 0.5 ) .';
	}

	.woocommerce .products .product .star-rating>span strong {
		color:'. coup_hex2rgba( $meta_link_color, 0.5 ) .';
	}

	.woocommerce div.product p.price,
	.woocommerce div.product span.price {
		color:'. coup_hex2rgba( $paragraphs_color, 0.7 ) .';
	}

	.woocommerce div.product div.summary .woocommerce-product-details__short-description {
		border-top-color: '. coup_hex2rgba( $paragraphs_color, 0.2 ) .';
	}

	.woocommerce .woocommerce-product-rating .star-rating,
	.woocommerce div.product div.summary .woocommerce-review-link {
		color:'. coup_hex2rgba( $paragraphs_color, 0.5 ) .';
	}

	.woocommerce div.product form.cart .variations td {
		border-color:'. coup_hex2rgba( $meta_link_color, 0.3 ) .';
	}

	.woocommerce button.button,
	.woocommerce input.button {
		color: '. esc_attr( $body_bg_color ) .';
	}

	.woocommerce #respond input#submit.alt,
	.woocommerce a.button.alt,
	.woocommerce button.button.alt,
	.woocommerce input.button.alt {
		border-color: '. esc_attr( $meta_link_color ) .';
	}

	.wc-tabs-wrapper {
		border-top-color:'. coup_hex2rgba( $paragraphs_color, 0.2 ) .';
	}

	.woocommerce div.product .woocommerce-tabs ul.tabs li.active {
		border-bottom-color:  '. esc_attr( $paragraphs_color ) .';
	}
	.woocommerce td,
	.woocommerce table.shop_attributes td,
	.woocommerce table.shop_attributes th,
	.woocommerce table.shop_table td,
	.woocommerce table.shop_table tbody:first-child tr:first-child td,
	#add_payment_method .cart-collaterals .cart_totals table tr:first-child td,
	.woocommerce-cart .cart-collaterals .cart_totals table tr:first-child td,
	.woocommerce-checkout .cart-collaterals .cart_totals table tr:first-child td,
	.woocommerce-cart .cart-collaterals .cart_totals tr td,
	.woocommerce table.shop_table tfoot td,
	.woocommerce table.shop_table tbody th,
	.woocommerce table.shop_table tfoot th {
		border-color:'. coup_hex2rgba( $paragraphs_color, 0.5 ) .';
	}

	.woocommerce p.stars a:before {
		color: '. esc_attr( $paragraphs_color ) .';
	}

	.woocommerce p.stars a:hover~a:before,
	.woocommerce p.stars.selected a.active~a:before {
		color:'. coup_hex2rgba( $paragraphs_color, 0.2 ) .';
	}

	.woocommerce #content table.cart td.actions .input-text:hover,
	.woocommerce table.cart td.actions .input-text:hover,
	.woocommerce-page #content table.cart td.actions .input-text:hover,
	.woocommerce-page table.cart td.actions .input-text:hover {
		border-color: '. esc_attr( $meta_link_color ) .';
	}

	.woocommerce .cart .actions input.input-text,
	.woocommerce .checkout_coupon input.input-text,
	.woocommerce input#coupon_code {
		border-bottom-color: '. esc_attr( $meta_link_color ) .';
	}
	#add_payment_method .cart-collaterals .cart_totals tr td,
	.woocommerce-checkout .cart-collaterals .cart_totals tr td {
		border-top-color:'. coup_hex2rgba( $paragraphs_color, 0.4 ) .';
	}
	#add_payment_method .cart-collaterals .cart_totals tr td,
	#add_payment_method .cart-collaterals .cart_totals tr th,
	.woocommerce-cart .cart-collaterals .cart_totals tr td,
	.woocommerce-cart .cart-collaterals .cart_totals tr th,
	.woocommerce-checkout .cart-collaterals .cart_totals tr td,
	.woocommerce-checkout .cart-collaterals .cart_totals tr th,
	.woocommerce table.shop_table tbody:first-child tr:first-child td,
	.woocommerce table.shop_table tbody:first-child tr:first-child th {
		border-color:'. coup_hex2rgba( $paragraphs_color, 0.4 ) .';
	}
	.woocommerce-cart .wc-proceed-to-checkout a.checkout-button {
		color: '. esc_attr( $body_bg_color ) .';
	}

	.woocommerce-cart .wc-proceed-to-checkout a.checkout-button:hover,
	.woocommerce #respond input#submit:hover,
	.woocommerce button.button:hover,
	.woocommerce input.button:hover,
	.woocommerce input.button.alt:hover {
		color: '. esc_attr( $meta_link_color ) .';
	}

	.select2-container--default .select2-selection--single {
		background-color:'. esc_attr( $body_bg_color ) .';
	}

	.select2-container--default .select2-selection--single .select2-selection__rendered {
		color: '. esc_attr( $meta_link_color ) .';
	}

	#add_payment_method #payment div.payment_box,
	.woocommerce-cart #payment div.payment_box,
	.woocommerce-checkout #payment div.payment_box {
		background-color:'. coup_hex2rgba( $paragraphs_color, 0.05 ) .';
		color: '. esc_attr( $paragraphs_color ) .';
	}
	.woocommerce-account .woocommerce-MyAccount-navigation a:after {
		background:'. esc_attr( $meta_link_color ) .';
	}
	.woocommerce fieldset {
		background:'. coup_hex2rgba( $paragraphs_color, 0.05 ) .';
	}

	.woocommerce form .form-row.woocommerce-validated .select2-container,
	.woocommerce form .form-row.woocommerce-validated input.input-text,
	.woocommerce form .form-row.woocommerce-validated select {
		border-color: '. esc_attr( $meta_link_color ) .';
	}


	body.woocommerce form .form-row .select2-container,
	body.woocommerce-page form .form-row .select2-container {
		border-bottom-color: '. esc_attr( $meta_link_color ) .';
	}
	.select2-results {
		background: '. esc_attr( $body_bg_color ) .';
	}

	.woocommerce a.remove:before,
	.woocommerce a.remove:after {
		background: '. esc_attr( $meta_link_color ) .';
	}

	.woocommerce .widget_shopping_cart .total,
	.woocommerce.widget_shopping_cart .total {
		border-bottom-color: '. coup_hex2rgba( $paragraphs_color, 0.2 ) .';
		color: '. esc_attr( $paragraphs_color ) .';
	}
	.woocommerce-page .widget_shopping_cart .buttons a,
	.woocommerce .widget_shopping_cart .buttons a,
	body .widget_shopping_cart .buttons a {
		color: '. esc_attr( $body_bg_color ) .';
		background: '. esc_attr( $meta_link_color ) .';
		border-color: '. esc_attr( $meta_link_color ) .';
	}

	.woocommerce-page .widget_shopping_cart .buttons a:hover,
	.woocommerce .widget_shopping_cart .buttons a:hover,
	body .widget_shopping_cart .buttons a:hover {
		color: '. esc_attr( $meta_link_color ) .';
	}

	.woocommerce .widget_price_filter .ui-slider .ui-slider-handle {
		background: '. esc_attr( $body_bg_color ) .';
		border-color: '. esc_attr( $meta_link_color ) .';
	}
	.woocommerce .widget_price_filter .price_slider_wrapper .ui-widget-content {
		background-color: '. coup_hex2rgba( $meta_link_color, 0.1 ) .';
	}

	.woocommerce .widget_price_filter .ui-slider .ui-slider-range {
		background-color: '. esc_attr( $meta_link_color ) .';
	}

	.woocommerce .widget_price_filter .price_slider_amount button.button {
		color: '. esc_attr( $meta_link_color ) .';
	}

	.woocommerce .shop-sidebar .widget_rating_filter ul li.chosen *,
	.woocommerce .shop-sidebar .widget_layered_nav ul li.chosen *,
	.woocommerce .shop-sidebar .widget_rating_filter ul li.chosen a:before,
	.woocommerce .shop-sidebar .widget_layered_nav ul li.chosen a:before,
	.woocommerce .shop-sidebar .widget_layered_nav_filters ul li a:before {
		color: '. esc_attr( $body_bg_color ) .';
	}

	.sidebar-hide-scroll,
	.mini-cart {
	 	background: '. esc_attr( $body_bg_color ) .';
	}

	.woocommerce .shop-sidebar.shop-page .widget_layered_nav_filters ul li a {
		border-color: '. coup_hex2rgba( $meta_link_color, 0.2 ) .';
		color: '. coup_hex2rgba( $meta_link_color, 0.5 ) .';
	}

	.woocommerce .shop-sidebar.shop-page .widget_layered_nav_filters ul li a:hover {
		border-color: '. coup_hex2rgba( $meta_link_color, 0.5 ) .';
		color: '. esc_attr( $body_bg_color ) .';
	}
	.woocommerce .widget_layered_nav ul li.chosen a::before,
	.woocommerce .widget_layered_nav_filters ul li a::before {
		color: '. coup_hex2rgba( $meta_link_color, 1 ) .';
	}

	.woocommerce .star-rating:before {
		color: '. coup_hex2rgba( $meta_link_color, 0.4 ) .';
	}

	.widget.woocommerce input#woocommerce-product-search-field:hover,
	.widget.woocommerce input#woocommerce-product-search-field:focus,
	.widget.woocommerce input#woocommerce-product-search-field:active {
		border-color: '. coup_hex2rgba( $meta_link_color, 0.6 ) .';
	}

	#content .woocommerce-error,
	#content .woocommerce-info,
	#content .woocommerce-message,
	#content .woocommerce-Message {
		border-color: '. coup_hex2rgba( $paragraphs_color, 0.2 ) .';
		color: '. esc_attr( $paragraphs_color ) .';
	}

	.woocommerce .woocommerce-Message a.button:hover,
	.woocommerce-page .woocommerce-Message a.button:hover,
	.woocommerce .woocommerce-message a.button:hover,
	.woocommerce a.button.download:hover,
	.woo-msg-close:hover {
		color: '. esc_attr( $meta_link_color ) .';
	}

	.woocommerce .woocommerce-Message a.button,
	.woocommerce-page .woocommerce-Message a.button,
	.woocommerce .woocommerce-message a.button,
	.woocommerce a.button.download {
		background: '. esc_attr( $meta_link_color ) .';
		color: '. esc_attr( $body_bg_color ) .';
	}

	.cart-header,
	.mini-cart .woocommerce.widget_shopping_cart .cart_list li:not(:last-child) {
		border-bottom-color: '. coup_hex2rgba( $paragraphs_color, 0.2 ) .';
	}

	.mini-cart p.buttons a.button:not(.checkout) {
		color: '. esc_attr( $meta_link_color ) .';
	}

	.mini-cart p.buttons a.button:not(.checkout):hover {
		background-color: '. esc_attr( $meta_link_color ) .';
		color: '. esc_attr( $body_bg_color ) .';
	}

	@media screen and (max-width: 768px) {
		.woocommerce table.shop_table_responsive tbody tr:first-child td:first-child,
		.woocommerce-page table.shop_table_responsive tbody tr:first-child td:first-child {
			border-top-color: '. coup_hex2rgba( $paragraphs_color, 0.4 ) .';
		}
	}

	.blockUI.blockOverlay {
		background: '. coup_hex2rgba( $body_bg_color, 0.4 ) .' !important;
	}

	/* Navigation color */

	.site-branding,
	.site-title,
	.site-title a,
	.site-title a:visited,
	.main-navigation,
	.main-navigation a,
	.main-navigation a:hover,
	.main-navigation a:focus,
	.main-navigation a:active,
	.dropdown-toggle,
	.dropdown-toggle:hover,
	.dropdown-toggle:focus,
	.dropdown-toggle:active,
	.category-dropdown,
	.category-dropdown:hover,
	.category-dropdown:focus,
	.category-dropdown:active,
	.sidebar-toggle,
	.sidebar-toggle:hover,
	.sidebar-toggle:focus,
	.sidebar-toggle:active,
	.side-nav,
	.side-nav a,
	.side-nav a:hover,
	.side-nav a:focus,
	.side-nav a:active,
	.category-filter a,
	.category-filter a:hover,
	.category-filter a:focus,
	.category-filter a:active,
	.search-wrap .search-form .search-field,
	.search-wrap .search-form .search-submit,
	.search-wrap form:hover .search-submit, .search-wrap.focus .search-submit, .search-wrap .search-submit:hover, .search-wrap .search-submit:focus, .search-wrap .search-submit:active,
	body div.sharedaddy div h3.sd-title,
	.back-to-top,
	.back-to-top:hover, .back-to-top:focus, .back-to-top:active,
	.menu-toggle:focus, .menu-toggle:active, .menu-toggle:hover
	 {
		color: '. esc_attr( $navigation_color ) .';
	}

	.search-wrap .search-field::-webkit-input-placeholder {
		color: '. esc_attr( $navigation_color ) .';
	}

	.search-wrap .search-field:-moz-placeholder {
		color: '. esc_attr( $navigation_color ) .';
	}

	.search-wrap .search-field::-moz-placeholder {
		color: '. esc_attr( $navigation_color ) .';
	}

	.search-wrap .search-field:-ms-input-placeholder {
		color: '. esc_attr( $navigation_color ) .';
	}

	.cart-touch i {
		color: '. esc_attr( $navigation_color ) .';
	}

	.mCSB_scrollTools .mCSB_dragger .mCSB_dragger_bar {
		background-color: '. esc_attr( $navigation_color ) .';
		background-color: '. coup_hex2rgba( $navigation_color , 0.8 ) .';
	}

	.mCSB_scrollTools.mCS-dark .mCSB_dragger:hover .mCSB_dragger_bar,
	.mCSB_scrollTools.mCS-dark .mCSB_dragger:active .mCSB_dragger_bar,
	.mCSB_scrollTools.mCS-dark .mCSB_dragger.mCSB_dragger_onDrag .mCSB_dragger_bar {
		background-color: '. esc_attr( $navigation_color ) .';
	}
	.mCSB_scrollTools .mCSB_draggerRail {
		background-color: '. esc_attr( $navigation_color ) .';
		background-color: '. coup_hex2rgba( $navigation_color , 0.2 ) .';
	}

	.main-navigation a:after,
	.category-filter a:after,
	.search-wrap form:after,
	.menu-toggle .hamburger span {
		background: ' . esc_attr( $navigation_color ) .';
	}

	.site-title a:hover,
	.site-title a:focus,
	.menu-toggle {
		color:'. coup_hex2rgba( $navigation_color , 0.4 ) .';
	}

	/* fullwidth slider navigation */

	@media screen and (min-width: 1201px ) {

		.fullwidth-slider:not(.menu-open) .site-branding,
		.fullwidth-slider:not(.menu-open) .site-title,
		.fullwidth-slider:not(.menu-open) .site-title a,
		.fullwidth-slider:not(.menu-open) .site-title a:visited,
		.fullwidth-slider:not(.menu-open) .main-navigation div > ul > li > a,
		.fullwidth-slider:not(.menu-open) .main-navigation div > ul > li > a:hover,
		.fullwidth-slider:not(.menu-open) .main-navigation div > ul > li > a:focus,
		.fullwidth-slider:not(.menu-open) .main-navigation div > ul > li > a:active,
		.fullwidth-slider:not(.menu-open) div > ul > li > .dropdown-toggle,
		.fullwidth-slider:not(.menu-open) div > ul > li > .dropdown-toggle:hover,
		.fullwidth-slider:not(.menu-open) div > ul > li > .dropdown-toggle:focus,
		.fullwidth-slider:not(.menu-open) div > ul > li > .dropdown-toggle:active,
		.fullwidth-slider:not(.menu-open) .sidebar-toggle,
		.fullwidth-slider:not(.menu-open) .sidebar-toggle:hover,
		.fullwidth-slider:not(.menu-open) .sidebar-toggle:focus,
		.fullwidth-slider:not(.menu-open) .sidebar-toggle:active {
			color: ' . esc_attr( $fullwidth_navigation_color ) .';
		}

		.fullwidth-slider:not(.menu-open) .main-navigation a:after {
			background: ' . esc_attr( $fullwidth_navigation_color ) .';
		}
	}

	.fullwidth-slider .slick-dots button,
	.fullwidth-slider .slick-dots button:hover,
	.fullwidth-slider .slick-dots button:focus,
	.fullwidth-slider .slick-dots button:active {
		border-bottom-color: ' . esc_attr( $fullwidth_navigation_color ) .';
	}

	.fullwidth-slider .featured-slider,
	.fullwidth-slider .featured-slider .entry-meta,
	.fullwidth-slider .featured-slider a,
	.fullwidth-slider .featured-slider a:visited,
	.fullwidth-slider .featured-slider h5 {
		color: ' . esc_attr( $fullwidth_navigation_color ) .';
	}

	.fullwidth-slider .featured-slider a:hover,
	.fullwidth-slider .featured-slider a:focus,
	.fullwidth-slider .featured-slider a:active,
	.fullwidth-slider .featured-slider .entry-meta a:hover,
	.fullwidth-slider .featured-slider .entry-meta a:focus,
	.fullwidth-slider .featured-slider .entry-meta a:active {
		color:'. coup_hex2rgba( $fullwidth_navigation_color , 0.7 ) .';
	}

	/* Selection */

	::-moz-selection { /* Gecko Browsers */
		background: '. esc_attr( $selection_color ) .';
		color: '. esc_attr( $body_bg_color ) .';
	}
	::selection {  /* WebKit/Blink Browsers */
		background: '. esc_attr( $selection_color ) .';
		color: '. esc_attr( $body_bg_color ) .';
	}



	.woocommerce div span.onsale {
		background: '. esc_attr( $paragraphs_color ) .';
		color: '. esc_attr( $body_bg_color ) .';
	}

	.woocommerce .wc-new-badge {
		color: '. esc_attr( $paragraphs_color ) .';
		background: '. esc_attr( $body_bg_color ) .';
	}


	';

	return $change_colors_style;

}

?>
