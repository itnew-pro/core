<?php if ($blocks) { foreach ($blocks as $block) { ?>
	<div class="content-menu-block-item content-menu-block-<?php echo $block->id; ?>">
		<?php
			echo CHtml::ajaxLink(
				"<i class='c c-" . Yii::app()->controller->id . "'></i>{$block->name}",
				$this->createUrl(
					"ajax/index",
					array(
						"controller" => Yii::app()->controller->id,
						"action" => "window",
						"language" => Yii::app()->language,
						"id" => $block->content_id,
						"name" => $block->name,
					)
				), 
				array(
					"beforeSend" => 'function(){
						$(".content-menu-block-' . $block->id . ' i.c").hide();
						$(".content-menu-block-' . $block->id . ' .loader-window").show();
					}',
					"success" => 'function(data) {
						$(".content-menu-block-' . $block->id . ' .loader-window").hide();
						$(".content-menu-block-' . $block->id . ' i.c").show();
						$("body").append(data);
						showWindow("' . Yii::app()->controller->id . '");
					}',
				),
				array(
					"class" => "link",
					"id" => uniqid(),
					"live" => false,
				)
			);

			echo Html::loader("window");

			echo CHtml::ajaxButton(
				null,
				$this->createUrl(
					"ajax/index",
					array(
						"controller" => Yii::app()->controller->id,
						"action" => "settings",
						"language" => Yii::app()->language,
						"id" => $block->content_id,
					)
				), 
				array(
					"beforeSend" => 'function(){
						$(".content-menu-block-' . $block->id . ' .loader-settings").show();
					}',
					"success" => 'function(data) {
						$(".content-menu-block-' . $block->id . ' .loader-settings").hide();
						$("#subpanel").remove();
						$("body").append(data);
					}',
				),
				array(
					"class" => "settings",
					"id" => uniqid(),
					"live" => false,
				)
			);

			echo Html::loader("settings");
		?>
	</div>
<?php } } ?>