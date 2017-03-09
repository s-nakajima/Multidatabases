<div class="col-xs-12 col-sm-6">
	<table class="table table-bordered">
		<?php foreach ($gMetadatas as $key => $metadata): ?>
			<tr>
				<th class="col-xs-3 col-sm-4"><?php echo $metadata['name']; ?></th>
				<td>
					<?php
					switch ($metadata['type']) {
						case 'created':
							echo date("Y/m/d",strtotime($gContents['MultidatabaseContent']['created']));
							break;
						case 'updated':
							echo date("Y/m/d",strtotime($gContents['MultidatabaseContent']['modified']));
							break;
						default:
							echo $gContents['MultidatabaseContent']['value' . $metadata['col_no']];
							break;
					}
					?>
					<?php  ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</table>
</div>
