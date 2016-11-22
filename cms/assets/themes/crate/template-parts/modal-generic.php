<div class="totallyhidden">
	<aside id="signup-modal" class="modal">
		<header>
			<div class="icon-logo"></div>
			<h2>You are uniquely valuable.</h2>
			<p class="prose">Sign up below to learn more about how you can offer your knowledge and experience to younger generations this fall.</p>
		</header>

		<form name="signup" class="bsd-signup-2" action="https://generation.cp.bsd.net/page/signup/join-g2g" method="post" id="signup-generic" target="bsd-target">

			<div class="row">
				<div class="bsd-field-firstname half">
					<label class="field">First Name <span class="required">(required)</span></label>
					<input name="firstname" type="text" required placeholder="First name">
				</div>
				<div class="bsd-field-lastname half">
					<label class="field">Last Name <span class="required">(required)</span></label>
					<input name="lastname" type="text" required placeholder="Last name">
				</div>
			</div>

			<div class="row">
				<div class="bsd-field-firstname wide">
					<label class="field">Email address <span class="required">(required)</span></label>
					<input name="email" type="email" required placeholder="Email address">
				</div>
				<div class="bsd-field-lastname narrow">
					<label class="field">Zipcode <span class="required">(required)</span></label>
					<input name="zip" type="text" required placeholder="Zipcode">
				</div>
			</div>

			<div style="display: none"><label for="best-contact-time">Please leave this field blank:</label> <input id="best-contact-time" name="best-contact-time" type="text"></div>

		  <div class="row button-group" id="bsd-field-submit-btn">
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
			<h2>Thank you for pledging!</h2>
			<?php echo apply_filters( 'the_content', '[button-group][button style="facebook" href="https://www.facebook.com/sharer/sharer.php?u=http%3A//generationtogeneration.org/"]Share on Facebook[/button][button style="twitter" href="https://twitter.com/intent/tweet?text=I%20just%20joined%20%23Gen2Gen%20--%20mobilizing%20adults%2050%2B%20to%20help%20young%20people%20thrive%21%20Learn%20more%20and%20get%20involved%20at%20generationtogeneration.org&source=webclient"]Share on Twitter[/button][/button-group]'); ?>
		</header>
	</aside>
</div>
