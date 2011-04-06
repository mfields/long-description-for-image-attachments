<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<title><?php the_title(); ?></title>
<link rel="stylesheet" type="text/css" href="<?php print get_stylesheet_uri(); ?>">
<style> #longdesc { width:50em; padding:1em; margin:1em auto; background:#fff; color:#333; border:1px solid #333; } </style>
</head>
<body>
	<div id="longdesc">
	<?php
		the_content();
		if( isset( $_GET['referrer'] ) ) {
			$uri = get_permalink( (int) $_GET['referrer'] );
			if( !empty( $uri ) ) {
				$uri.= '#' . longdesc_return_anchor( get_the_ID() );
				print '<p><a href="' . esc_url( $uri ) . '">' .esc_html__( 'Return to article.', 'longdesc' ) . '</a></p>';
			}
		}
	?>
	</div>
</body>
</html>