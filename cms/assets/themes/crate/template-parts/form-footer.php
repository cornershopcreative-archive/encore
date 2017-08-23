<?php
/**
 * The template for the footer signup form.
 */
?>

	<aside class="footer-form">
		<div class="container">

			<div class="hide-after-submit">

				<h2 class="section-title"><?php esc_html_e( 'Learn more about how you can offer your knowledge and experience to younger generations.', 'crate' ); ?></h2>

				<form name="signup" class="bsd-signup-2" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post" id="signup-footer" target="footer-form-target">

					<div class="row">
						<div class="bsd-field-firstname">
							<label for="footer-form-firstname" class="field label-inside">First Name</label>
						<input name="firstname" id="footer-form-firstname" type="text" required>
						</div>
						<div class="bsd-field-lastname">
							<label for="footer-form-lastname" class="field label-inside">Last Name</label>
						<input name="lastname" id="footer-form-lastname" type="text" required>
						</div>
						<div class="bsd-field-firstname wide">
							<label for="footer-form-email" class="field label-inside">Email address</label>
						<input name="email" id="footer-form-email" type="email" required>
						</div>
						<div class="bsd-field-lastname">
							<label for="footer-form-zip" class="field label-inside">Zipcode</label>
						<input name="zip" id="footer-form-zip" type="text" required>
						</div>
						<div class="button-group">
							<button name="submit-btn" type="submit" class="button button-solid button-bright-blue"><?php esc_html_e( 'Count Me In', 'crate' ); ?></button>
						</div>
						<div class="powered-by narrow">
							<a href="http://encore.org/" target="_blank">
								<svg class="logo" viewBox="0 0 106 30">
									<use xlink:href="#icon-powered-by"></use>
								</svg>
								<span class="screen-reader-text"><?php esc_html_e( 'Powered by Encore.org', 'crate' ); ?></span>
							</a>
						</div>
					</div>

					<div style="display: none"><label for="best-contact-time">Please leave this field blank:</label> <input id="best-contact-time" name="best-contact-time" type="text"></div>

					<input name="country" type="hidden" value="US">
					<input name="redirect_url" type="hidden" value="http:<?php echo acf_get_current_url() . "#thankyou" ?>">
					<input name="custom-24" type="hidden" value="G2G Site Footer">
					<input type="hidden" name="action" value="gform_proxy">
					<input type="hidden" name="gform_id" value="1">
					<input type="hidden" name="crowdskout" value="1001">
				</form>

				<iframe id="footer-form-target" width="0" class="totallyhidden" border="0" name="footer-form-target"></iframe>

			</div>

			<div class="hide-until-submit">
				<p class="thank-you-message"><?php esc_html_e( 'Thank you!', 'crate' ); ?></p>
			</div>

			<a class="footer-form-dismiss">╳</a>

		</div>

	</aside>
