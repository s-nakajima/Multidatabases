<?php foreach ($gMetadatas as $key => $metadata): ?>
	<tr>
		<th colspan="1"><?php echo $metadata['name']; ?></th>
		<td colspan="3"><?php echo $gContents['MultidatabaseContent']['value' . $metadata['col_no']]; ?></td>
	</tr>
<?php endforeach; ?>
