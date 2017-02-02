<div class="totallyhidden">
	<aside id="signup-modal" class="modal cta-huge">
		<header>
			<svg class="logo" viewBox="0 0 50 50">
				<use xlink:href="#icon-logo"></use>
			</svg>
		</header>

		<form name="signup" class="bsd-signup-2" action="https://generation.cp.bsd.net/page/signup/join-g2g" method="post" id="signup-generic" target="bsd-target">

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
			<input name="custom-24" type="hidden" value="G2G Site Header Button">
		</form>
		<iframe id="bsd-target" width="0" class="totallyhidden" border="0" name="bsd-target"></iframe>
	</aside>
</div>

<div class="totallyhidden">
	<aside id="signup-modal-thanks" class="modal">
		<header>
			<div class="icon-logo"></div>
			<h2>Thank you!</h2>
			<p>Invite your friends to help even more young people thrive.</p>
			<?php echo apply_filters( 'the_content', '[button-group][button style="facebook" href="https://www.facebook.com/sharer/sharer.php?u=http%3A//generationtogeneration.org/%3Futm_source=share%26utm_medium=facebook" target="_blank"]Share on Facebook[/button][button style="twitter" href="https://twitter.com/intent/tweet?text=I%20just%20joined%20%23Gen2Gen%20--%20mobilizing%20adults%2050%2B%20to%20help%20young%20people%20thrive%21%20Learn%20more%20and%20get%20involved%20at%20http://generationtogeneration.org/%3Futm_source=share%26utm_medium=twitter&source=webclient" target="_blank"]Share on Twitter[/button][/button-group][button-group][button style="email" color="gold" href="mailto:?&subject=Pass it on, from Generation to Generation&body=I%20just%20joined%20%23Gen2Gen%20-%20a%20new%20campaign%20to%20mobilize%20adults%2050%20and%20over%20to%20help%20young%20people%20thrive!%20%0A%0ALearn%20more%20and%20get%20involved%20at%20http://generationtogeneration.org/%3Futm_source=share%26utm_medium=email" target="_blank"]Email To a Friend[/button][/button-group]'); ?>
		</header>
	</aside>
</div>

<div class="totallyhidden">
	<aside id="share-modal-thanks" class="modal">
		<header>
			<div class="icon-logo"></div>
			<h2>Thank you for passing it on!</h2>
			<p>&hellip; from the team at #Gen2Gen</p>
			<div class="button-group"><a class="button button-gold button-close" href="#">Back to Generation to Generation</a></div>
		</header>
	</aside>
</div>
