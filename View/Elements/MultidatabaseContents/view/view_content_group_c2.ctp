<?php foreach ($gMetadatas as $key => $metadata): ?>
	<tr>
		<?php if (!isset($metadata[1])): ?>
			<th><?php echo $metadata[0]['name']; ?></th>
			<td><?php echo $gContents['MultidatabaseContent']['value' . $metadata[0]['col_no']]; ?></td>
			<td colspan="2">&nbsp;</td>
		<?php else: ?>
			<th><?php echo $metadata[0]['name']; ?></th>
			<td><?php echo $gContents['MultidatabaseContent']['value' . $metadata[0]['col_no']]; ?></td>
			<th><?php echo $metadata[1]['name']; ?></th>
			<td><?php echo $gContents['MultidatabaseContent']['value' . $metadata[1]['col_no']]; ?></td>
		<?php endif; ?>
	</tr>
<?php endforeach; ?>
