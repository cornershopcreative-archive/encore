<div class="totallyhidden">
	<aside id="auto-lightbox-modal" class="modal cta-huge cta-has-background-image">
		<header>
			<svg class="logo" viewBox="0 0 50 50">
				<use xlink:href="#icon-logo"></use>
			</svg>
		</header>

		<form name="signup" class="bsd-signup-2" action="https://generation.cp.bsd.net/page/signup/join-g2g" method="post" id="signup-auto-lightbox" target="bsd-target">

			<p>
				I,
				<span class="field">
					<input name="firstname" type="text" required placeholder="First Name">
				</span><span class="field">
					<input name="lastname" type="text" required placeholder="Last Name">
				</span>
				pledge to create a <strong>better future</strong><br>
				for <strong>future generations</strong>
			</p>

			<p>
				<span class="field">
					<input name="email" type="email" required placeholder="Email Address">
				</span><span class="field">
					<input name="zip" type="text" required placeholder="Zip Code">
				</span>
			</p>

			<div style="display: none"><label for="best-contact-time">Please leave this field blank:</label> <input id="best-contact-time" name="best-contact-time" type="text"></div>

			<div class="button-group" id="bsd-field-submit-btn">
				<div class="input"><input name="submit-btn" value="Count Me In" type="submit" class="button button-solid button-gold aligncenter"></div>
		  </div>

			<input name="country" type="hidden" value="US">
			<input name="redirect_url" type="hidden" value="http:<?php echo acf_get_current_url() . "#thankyou" ?>">
			<input id="_guid" name="_guid" type="hidden" value="">
			<input name="custom-24" type="hidden" value="G2G Auto Lightbox">
		</form>
		<iframe id="bsd-target" width="0" class="totallyhidden" border="0" name="bsd-target"></iframe>
	</aside>
</div>
