<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package myoos
 */
?>

        </div>
    </div><!--/container-->     
    <!-- End Content Part -->

    <!--=== Footer ===-->
    <div class="footer">
        <div class="container">
            <div class="row">
			
						<?php
							$i = 1;
							while ($i <= 3) { ?>
							<div class="col-md-4">
								<?php dynamic_sidebar("footer-".$i)?>
							</div><!--/col-md-4-->
						<?php $i++;} ?>
			
            </div>
        </div> 
    </div><!--/footer-->
    <!--=== End Footer ===-->	
 
    <!--=== Copyright ===-->
    <div class="copyright">
        <div class="container">
            <div class="row">
                <div class="col-md-6">                     
                    <p>
						<a href="<?php echo esc_url( __( 'http://wordpress.org/', 'myoos' ) ); ?>"><?php printf( __( 'Proudly powered by %s', 'myoos' ), 'WordPress' ); ?></a>
						<span class="sep"> | </span>
						<?php printf( __( 'Theme: %1$s by %2$s.', 'myoos' ), 'myoos', '<a href="http://automattic.com/" rel="designer">Automattic</a>' ); ?>
                    </p>
                </div>
                <div class="col-md-6"> 
					<?php
						if (is_front_page()) {
					?><h1><?php } ?>
                    <a href="<?php echo get_home_url(); ?>">
                        <img class="pull-right" id="logo-footer" src="/img/logo2-default.png" alt="<?php bloginfo('name'); ?>">
                    </a>
					<?php 
						if (is_front_page()) {
					?></h1><?php } ?>
                </div>
            </div>
        </div> 
    </div><!--/copyright--> 
    <!--=== End Copyright ===-->

</div><!--/wrapper-->

<?php wp_footer(); ?>

<!-- JS Global Compulsory -->           
<script type="text/javascript" src="<?php echo PARENT_URL; ?>/js/bootstrap.min.js"></script> 

</body>
</html>
		







