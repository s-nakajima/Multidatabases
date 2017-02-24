<?php
foreach ($multidatabaseMetadata as $metadataKey => $metadataVal) {
	echo $this->NetCommonsForm->hidden('MultidatabaseMetadataSetting.' . $multidatabaseMetadata['tmp_id'] . '.' . $metadataKey,array('value' => $metadataVal));
}

