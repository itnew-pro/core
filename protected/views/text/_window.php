<?php
if ($model->getEditorClass()) {
	Yii::app()->clientScript->registerScript(
		"textWindow",
		'
					tinymce.init({
						selector: ".tinymce",
						plugins: [
							"advlist autolink link image lists charmap print preview hr anchor pagebreak",
							"searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
							"table contextmenu directionality emoticons paste textcolor responsivefilemanager"
						],
						toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
						toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | print preview code ",
						image_advtab: true ,

						external_filemanager_path:"/include/filemanager/",
						filemanager_title:"Responsive Filemanager" ,
						relative_urls: false,
						external_plugins: { "filemanager" : "plugins/responsivefilemanager/plugin.min.js"}
					});

					$(".window .button").on("click", function(){
						tinyMCE.get("text-' . $model->id . '").save();
			});
		'
	);
}
?>

<?php
echo CHtml::activeTextArea(
	$model,
	"text",
	array(
		"rows"  => $model->rows,
		"class" => "textarea" . $model->getEditorClass(),
		"id"    => "text-" . $model->id,
		"name"  => !empty($name) ? "{$name}[text]" : get_class($model) . "[text]",
	)
);
?>