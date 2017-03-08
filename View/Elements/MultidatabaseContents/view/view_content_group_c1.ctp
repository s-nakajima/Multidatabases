<table class="table table-bordered">
	<?php foreach ($gMetadatas as $key => $metadata): ?>
			<tr>
				<th style="width:20%;"><?php echo $metadata['name']; ?></th>
				<td><?php echo $gContents['MultidatabaseContent']['value' . $metadata['col_no']]; ?></td>
			</tr>
	<?php endforeach; ?>
</table>
