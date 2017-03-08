<div class="col-xs-12">
	<table class="table table-bordered">
		<?php foreach ($gMetadatas as $key => $metadata): ?>
			<tr>
				<th class="col-xs-3 col-sm-2"><?php echo $metadata['name']; ?></th>
				<td><?php echo $gContents['MultidatabaseContent']['value' . $metadata['col_no']]; ?></td>
			</tr>
		<?php endforeach; ?>
	</table>
</div>
