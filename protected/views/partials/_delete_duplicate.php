<?php if (!$model->isNewRecord) { ?>
	<div class="subpanel-links form-block">
		<?php echo CHtml::ajaxLink(
			Yii::t("common", "Delete"),
			$this->createUrl(
				"ajax/index",
				array(
					"controller" => Yii::app()->controller->id,
					"action"     => "delete",
					"language"   => Yii::app()->language,
					"id"         => $model->id,
				)
			),
			array(
				"beforeSend" => 'function(){

					}',
				"success"    => 'function(data) {
						$("#panel").remove();
						$("#subpanel").remove();
						$("body").append(data);
					}',
			),
			array(
				"class"   => "delete dotted",
				"id"      => uniqid(),
				"live"    => false,
				"onclick" => "if (!confirm('Восстановить будет невозможно! \\r\\n Вы действительно хотите удалить безвозвратно?')){return;}",
			)
		);

		echo CHtml::ajaxLink(
			Yii::t("common", "Duplicate"),
			$this->createUrl(
				"ajax/index",
				array(
					"controller" => Yii::app()->controller->id,
					"action"     => "duplicate",
					"language"   => Yii::app()->language,
					"id"         => $model->id,
				)
			),
			array(
				"beforeSend" => 'function(){

					}',
				"success"    => 'function(data) {
						$("#panel").remove();
						$("#subpanel").remove();
						$("body").append(data);
					}',
			),
			array(
				"class" => "dotted",
				"id"    => uniqid(),
				"live"  => false,
			)
		); ?>
	</div>
<?php } ?>