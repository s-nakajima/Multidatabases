	<?php foreach($metadatas as $metadata): ?>
		<div>
			<?php echo $this->MultidatabaseContentEdit->renderFormElement($metadata);
			?>
		</div>
	<?php endforeach; ?>
