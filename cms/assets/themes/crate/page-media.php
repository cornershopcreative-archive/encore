<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Crate
 */

get_header(); ?>

	<main id="main" class="site-main" role="main" style="margin-top:">

		<div class="hero" style="background-image: url('/cms/assets/images/David_Norris_and_Himanshu_Saini_MYM_2012_working1_1.png'); height: 400px; position: relative">
				
				<div class="title-box" style="background: #0147C2; width: 40%; height: 144px; position:absolute; bottom:-50px; left: 30%; right: 30%; line-height: 100px; ">
					<h1 class="page-title" style="color: white;  display: inline-block; vertical-align: middle; line-height: normal; padding-top: 25px"> News and Media</h1>
				</div>

		</div>

	<!-- content -->
    	<div id="content" class="clearfix" style="margin-top: 100px">

			<!-- loops-wrapper -->
			<div id="loops-wrapper" class="loops-wrapper" style="width: 70%; margin-left: auto; margin-right: auto">

				
				<article id="post-1">
	
            		<div class="news-post" style="height: 100px; padding-bottom: 150px; border-bottom: 1px solid #979797; margin-bottom: 50px">
	            		
	            		<div class="news-logo" style="float:left; width: 25%; margin-right: 5%;">
	            			<img src="/cms/assets/images/theconversation-logo-cc1.png" style="margin-top: 20%">
	            		</div>
			
						<div class="post-content" style="width: 70%; float: right;">

										<div class="post-date-wrap">
											<time class="post-date entry-date updated" datetime="2016-08-05">
												<span class="month">August</span>
												<span class="day">5</span>,
												<span class="year">2016</span>
											</time>
										</div>
									
															
										<h2 style="line-height: 32px;">
											<a href="post link here" title="Reimagining NSW: how the care economy could help unclog our cities" style="font-size: 21px; color: #2D2D2D; text-decoration: none;">Reimagining NSW: how the care economy could help unclog our cities</a>
										</h2>

						</div>
						<!-- /.post-content -->
				</div>
				<!-- /.news-post -->

			</article>
			<!-- /.post -->

			<article id="post-2">
	
            		<div class="news-post" style="height: 100px; padding-bottom: 150px; border-bottom: 1px solid #979797; margin-bottom: 50px">
	            		
	            		<div class="news-logo" style="float:left; width: 25%; margin-right: 5%;">
	            			<img src="/cms/assets/images/huffington-post-logo.png" style="margin-top: 10%">
	            		</div>
			
						<div class="post-content" style="width: 70%; float: right;">

										<div class="post-date-wrap">
											<time class="post-date entry-date updated" datetime="2016-08-05">
												<span class="month">August</span>
												<span class="day">3</span>,
												<span class="year">2016</span>
											</time>
										</div>
									
															
										<h2 style="line-height: 32px;">
											<a href="post link here" title="When You Don't Have Enough Gold For Your Golden Years" style="font-size: 21px; color: #2D2D2D; text-decoration: none;">When You Don't Have Enough Gold For Your Golden Years</a>
										</h2>

						</div>
						<!-- /.post-content -->
				</div>
				<!-- /.news-post -->

			</article>
			<!-- /.post -->

		</div> 
		<!-- /.loops-wrapper -->

	</div>

	</main>
	<!-- #main -->

<?php get_footer(); ?>