<?php acf_form_head(); 
show_admin_bar(false);

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php print pll__('ACF FastEditor by WebTo.Pro'); ?></title>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>	
	<div class="app-acf-fasteditor-form">
		<div class="app-acf-fasteditor-form-container">
			<?php if(!isset($wp->query_vars['pid'])) exit(); ?>
 			<?php $pid = $wp->query_vars['pid']; ?>
			<?php $slugs = isset($wp->query_vars['slugs']) ? $wp->query_vars['slugs'] : ''; $fields = explode(',', $slugs); ?>
			<?php
			global $wp;
			acf_form([
				'post_id' => $pid,
				'new_post' => false,
				'form' => true,
				'fields' => trim($slugs) == 'all' ? [] : $fields 
			]);?>
		</div>
		<iframe class="wtp-acf-fasteditor-hfix" frameborder="0"></iframe>
		<style>
			body, html {
				padding: 0 !important;
				margin: 0 !important;
			}
			.app-acf-fasteditor-form {
				position: relative;
				padding: 20px;
			}
			.wtp-acf-fasteditor-hfix {
				height: 100%;
				position: absolute;
				top: 0;
				left: 0;
				width: 1px;
				opacity: 0;				
			}
			.updated {
				font-size: 18px;
				padding: 1em;
				color: #d9ffd7 !important;
				background:#278e22 !important;
			}
		</style>
		<script>
			(function() {
				let init;
				function onInit() {
					setHeight();
				}
				window.addEventListener("message", data => {
					try {
						data = data.data;
						data = JSON.parse(data);
						if(data.wtp && data.wtp.event == 'init') {
							init = data.wtp.id;
						}
						onInit();
					} catch(e) {

					}
				});
				let send = function(data) {
					if(init) {
						data.id = init;
					}
					let sendData = JSON.stringify({
						wtp: data
					});
					window.dispatchEvent(new CustomEvent('test'));
					document.dispatchEvent(new CustomEvent('test'));		
					if(window.parent == window.top && window.top != window) {											
						window.parent.postMessage(sendData, '*');
					}
				}
				let setHeight = () => {
					let height = document.querySelector('.wtp-acf-fasteditor-hfix').offsetHeight;
					send({height, event: 'height'});
				}
				document.querySelector('.wtp-acf-fasteditor-hfix').contentWindow.addEventListener('resize', function() {
					setHeight();
				});							
			})();
		</script>
	</div>
	<?php wp_footer(); ?>
</body>
</html>