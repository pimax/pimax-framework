<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?php $itm = $arResult['Item']; ?>

<?php if ($itm):?>
	<div class="news-list">
		
		<p class="news-item" id="<?php echo $this->GetEditAreaId($itm->getId());?>">
		
			<h1><?php echo $itm->getName();?></h1>
			<?php echo $itm->getPreviewText();?>
		</p>
	</div>
<?php endif;?>