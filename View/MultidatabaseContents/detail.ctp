<?php
echo $this->NetCommonsHtml->css([
	'/multidatabases/css/style.css',
	'/likes/css/style.css',
]);
//echo $this->NetCommonsHtml->script([
//	'/multidatabases/js/multidatabases.js',
//	'/likes/js/likes.js',
//]);
?>

<article class="multidatabaseContents">
	<style>
		.multidatabaseContents td,th {
			padding:5px;
		}

		.multidatabaseContents th {
			text-align:center;
			background-color:#F5F5F5;
		}
	</style>
	<header class="clearfix">
		<div class="pull-left">
			<?php echo $this->LinkButton->toList(); ?>
		</div>
		<div class="pull-right">
			<?php echo $this->element('MultidatabaseContents/view/view_content_edit_link', array('status' => $multidatabaseContent['MultidatabaseContent']['status'])); ?>
		</div>

	</header>
	<?php echo $this->NetCommonsHtml->blockTitle($multidatabase['Multidatabase']['name']); ?>
		<article>
			<?php echo $this->MultidatabaseContentView->renderContentsDetail(); ?>
		</article>
</article>
