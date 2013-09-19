<ul class="social-network">

	<?php if (get_theme_mod( 'myoos_facebook_url' )) : ?>
	<li><a href="<?php echo esc_url(get_theme_mod( 'myoos_facebook_url' )); ?>" data-toggle="tooltip" data-placement="bottom" title="Facebook"><i class="icon-facebook icon-square"></i></a></li>
	<?php endif; ?>

 	<?php if (get_theme_mod( 'myoos_twitter_url' )) : ?>
	<li><a href="<?php echo esc_url(get_theme_mod( 'myoos_twitter_url' )); ?>" data-toggle="tooltip" data-placement="bottom" title="Twitter"><i class="icon-twitter icon-square"></i></a></li>
	<?php endif; ?>	

	<?php if (get_theme_mod( 'myoos_linkedin_url' )) : ?>
	<li><a href="<?php echo esc_url(get_theme_mod( 'myoos_linkedin_url' )); ?>" data-toggle="tooltip" data-placement="bottom" title="Linkedin"><i class="icon-linkedin icon-square"></i></a></li>
	<?php endif; ?>	
	
    <?php if (get_theme_mod( 'myoos_pinterest_url' )) : ?>	
 	<li><a href="<?php echo esc_url(get_theme_mod( 'myoos_pinterest_url' )); ?>" data-toggle="tooltip" data-placement="bottom" title="Pinterest"><i class="icon-pinterest icon-square"></i></a></li>
	<?php endif ; ?>	
	
	<?php if (get_theme_mod( 'myoos_gplus_url' )) : ?>
	<li><a href="<?php echo esc_url(get_theme_mod( 'myoos_gplus_url' )); ?>" data-toggle="tooltip" data-placement="bottom" title="Google plus"><i class="icon-google-plus icon-square"></i></a></li>
	<?php endif ; ?>

</ul>