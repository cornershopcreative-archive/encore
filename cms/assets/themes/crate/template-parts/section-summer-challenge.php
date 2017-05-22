<?php
/**
 * The template for displaying Summer Challenge sections.
 */

$challenges = array(
	'jobs' => array(
		'url' => 'https://generation.cp.bsd.net/page/s/gen2gen-summer-challenge-jobs',
		'statement' => 'am taking a stand for<br> youth to <strong>support<br> summer employment opportunities.</strong>',
	),
	'learning' => array(
		'url' => 'https://generation.cp.bsd.net/page/s/gen2gen-summer-challenge-learning',
		'statement' => 'am taking a stand for youth this summer<br> to <strong>combat learning loss.</strong>',
	),
	'play' => array(
		'url' => 'https://generation.cp.bsd.net/page/s/gen2gen-summer-challenge-play',
		'statement' => 'am taking a stand for youth this summer<br> to <strong>increase access to safe places to play.</strong>',
	),
	'meals' => array(
		'url' => 'https://generation.cp.bsd.net/page/s/gen2gen-summer-challenge-meals',
		'statement' => 'am taking a stand for youth this summer<br> to <strong>increase access to healthy meals.</strong>',
	),
);
?>

	<div class="content-section section-circle-grid section-summer-challenge"<?php crate_section_id_attr(); ?>>
		<div class="content-section-grid container">
			<?php while ( have_rows( 'items' ) ) : the_row();
				$challenge_name = get_sub_field( 'item_challenge' );
				$challenge = $challenges[ $challenge_name ];
				?>
				<div class="grid-item grid-item-4">

					<?php echo wp_get_attachment_image( get_sub_field( 'image' ), 'square-md', false, array( 'class' => 'grid-item-image' ) ); ?>
					<?php if ( $grid_item_heading = get_sub_field( 'item_heading' ) ) : ?>
						<h3 class="grid-item-heading">
							<?php echo wp_kses_post( wptexturize( $grid_item_heading ) ); ?></h3>
						</h3>
					<?php endif; ?>
					<div class="grid-item-blurb">
						<?php echo wp_kses_post( get_sub_field( 'item_blurb' ) ); ?>
					</div>
					<div clas="button-group">
						<a href="#" class="button button-solid button-gold button-challenge" data-challenge="<?php echo esc_attr( $challenge_name ); ?>">
							<?php the_sub_field( 'button_text' ); ?>
						</a>
					</div>
					<p>
						<a href="<?php the_sub_field( 'link_url' ); ?>" class="item-link">
							<?php the_sub_field( 'link_text' ); ?>
						</a>
					</p>

					<?php
					/**
					 * Modal content.
					 */
					?>
					<div class="totallyhidden">
						<aside class="modal cta-huge cta-summer-challenge">
							<header>
								<svg class="logo" viewBox="0 0 50 50">
									<use xlink:href="#icon-logo"></use>
								</svg>
							</header>

							<div class="cta-image">
								<?php echo wp_get_attachment_image( get_sub_field( 'image' ), 'square-md', false, array( 'class' => 'grid-item-image' ) ); ?>
							</div>

							<?php
							// Unlike other modals, these forms don't have a target attribute --
							// instead, the user will be sent to the BSD page, which will redirect
							// them to another page on this site.
							?>
							<form name="signup" class="signup-summer-challenge" action="<?php echo esc_url( $challenge['url'] ); ?>" method="post">

								<p>
									I,
									<span class="field">
										<input name="firstname" type="text" tabindex="0" required placeholder="First Name">
									</span><span class="field">
										<input name="lastname" type="text" tabindex="0" required placeholder="Last Name">
									</span>
									<?php echo $challenge['statement']; ?>
								</p>

								<p>
									<span class="field">
										<input name="email" type="email" tabindex="0" required placeholder="Email Address">
									</span><span class="field">
										<input name="zip" type="text" tabindex="0" required placeholder="Zip Code">
									</span>
								</p>

								<div style="display: none"><label for="best-contact-time">Please leave this field blank:</label> <input id="best-contact-time" name="best-contact-time" type="text"></div>

								<div class="button-group" id="bsd-field-submit-btn">
							    <div class="input"><input name="submit-btn" tabindex="0" value="Continue to Step 2: Show Up" type="submit" class="button button-solid button-gold aligncenter"></div>
							  </div>

								<p>
									<small>We respect your privacy and will never sell your contact information.</small>
								</p>

								<input name="country" type="hidden" value="US">
								<!-- <input name="redirect_url" type="hidden" value=""> -->
								<input id="_guid" name="_guid" type="hidden" value="">
								<?php
								// Note: I don't know what custom-258 does/is, but it's in the original
								// BSD forms.
								?>
								<input name="custom-258" type="hidden" value="">
							</form>
						</aside>
					</div>

				</div>
			<?php endwhile; ?>
		</div>
	</div>
