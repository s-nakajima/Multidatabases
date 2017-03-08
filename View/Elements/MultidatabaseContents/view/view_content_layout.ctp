<article>
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="container-fluid">
				<div class="row">
					<?php
						$viewContentGrp[0] = $this->MultidatabaseContentView->renderGroup($multidatabaseMetadataGroups,$content,0,1,$viewMode);
						$viewContentGrp[1] = $this->MultidatabaseContentView->renderGroup($multidatabaseMetadataGroups,$content,1,2,$viewMode);
						$viewContentGrp[2] = $this->MultidatabaseContentView->renderGroup($multidatabaseMetadataGroups,$content,2,2,$viewMode);
						$viewContentGrp[3] = $this->MultidatabaseContentView->renderGroup($multidatabaseMetadataGroups,$content,3,1,$viewMode);
					?>
					<?php if (!empty($viewContentGrp[0])): ?>
						<?php echo $viewContentGrp[0]; ?>
					<?php endif; ?>

					<?php if (empty($viewContentGrp[1]) && empty($viewContentGrp[2])): ?>
					<?php else: ?>
						<?php echo $viewContentGrp[1]; ?>
						<?php echo $viewContentGrp[2]; ?>
					<?php endif; ?>

					<?php if (!empty($viewContentGrp[3])): ?>
						<?php echo $viewContentGrp[3]; ?>
					<?php endif; ?>

					<?php echo $this->MultidatabaseContentView->renderContentFooter($content,true); ?>
				</div>
			</div>
		</div>
	</div>
</article>

