<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?php $oCollection = $arResult['Items']; ?>

<div style="margin-bottom: 20px;">
	<a href="add">Добавить новость</a>
</div>

<?php if ($oCollection->count()):?>
	<div class="news-list">
		<?php foreach ($oCollection as $itm):?>
			<p class="news-item" id="<?php echo $this->GetEditAreaId($itm->getId());?>">
			
				<a href="/quotes/detail<?php echo $itm->getId();?>/"><b><?php echo $itm->getName();?></b></a><br />
				<?php echo $itm->getPreviewText();?>
				
				<br />
				<a href="/quotes/delete<?php echo $itm->getId();?>/" onclick="if(!confirm('Точно удалить!')) return false;">Удалить</a>
			</p>
		<?php endforeach;?>
	</div>
<?php endif;?>