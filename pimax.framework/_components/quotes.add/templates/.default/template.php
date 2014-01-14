<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?php if ($arResult['success']):?>
	<h1>Новость добавлена</h1>
	<a href="/quotes">Вернуться в список</a>
<?php else:?>
	<form method="post" action="">
		
		<div class="content-form feedback-form">
			<div class="fields">
				<label class="field-title">Название</label><br>
				<input type="text" value="" class="input_text_style" name="com[Name]"><br><br>
		
				<label class="field-title">Текст</label><br>
				<textarea style="width:500px; height:200px" cols="40" rows="5" name="com[PreviewText]"></textarea><br><br>
			
					
				<div class="field field-button">
				<input type="submit" value="Отправить" name="submit" class="bt3">
				</div>
			</div>
		</div>
		
	</form>
<?php endif;?>