<?php

/**
 * This is the model class for table "grid".
 *
 * The followings are the available columns in table 'grid':
 * @property integer $id
 * @property integer $structure_id
 * @property integer $line
 * @property integer $left
 * @property integer $top
 * @property integer $width
 * @property integer $block_id
 *
 * The followings are the available model relations:
 * @property Block $block
 * @property Structure $structure
 */
class Grid extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'grid';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('structure_id, line, left, top, width, block_id', 'required'),
			array('structure_id, line, left, top, width, block_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, structure_id, line, left, top, width, block_id', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'block' => array(self::BELONGS_TO, 'Block', 'block_id'),
			'structure' => array(self::BELONGS_TO, 'Structure', 'structure_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'structure_id' => 'Structure',
			'line' => 'Line',
			'left' => 'Left',
			'top' => 'Top',
			'width' => 'Width',
			'block_id' => 'Block',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('structure_id',$this->structure_id);
		$criteria->compare('line',$this->line);
		$criteria->compare('left',$this->left);
		$criteria->compare('top',$this->top);
		$criteria->compare('width',$this->width);
		$criteria->compare('block_id',$this->block_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Grid the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Get HTML grid for structure
	 *
	 * @param Structure $structure page structure model
	 *
	 * @return string
	 */
	public function getHtml(Structure $structure = null)
	{
		if ($structure) {
			$structureWidth = "100%";
			$structureMargin = "0";
			if ($structure->width) {
				$structureWidth = "{$structure->width}px";
				$structureMargin = "0 auto";
			}
			$html = "<div class=\"wrapper structure-{$structure->id}\" style=\"width: {$structureWidth}; margin: {$structureMargin}\">";

			if ($structure->grid) {
				
				$lineArray = array();
		
				foreach ($structure->grid as $grid) {
					$lineArray[$grid->line][] = $grid;
				}

				if ($lineArray) {
					foreach ($lineArray as $line => $array) {
						$html .= "<div class=\"line-{$line}\">";
						$html .= $this->getLineHtml($array, $structure->size);
						$html .= "</div>";
					}
				}					
			}

			$html .= "</div>";

			return $html;
		}

		return;
	}

	/**
	 * Get HTML of grid's line
	 *
	 * @param array $lineArray array of grid models in this line
	 * @param int $size grid's size
	 *
	 * @return string
	 */
	public function getLineHtml($lineArray, $size = Structure::DEFAULT_SIZE)
	{
		$html = "";
		$lineFlags = array();

		for ($i = 0; $i < $size; $i++) { 
			$lineFlags[$i] = 0;
		}
		
		foreach ($lineArray as $grid) {
			for ($i = $grid->left; $i < ($grid->left + $grid->width); $i++) { 
				$lineFlags[$i] = 1;
			}
		}

		$flag = 0;
		$borders = array();
		foreach ($lineFlags as $key => $value) {
			if ($flag != $value) {
				$flag = $value;
				$borders[] = $key;
			}
		}
		if (count($borders) % 2) {
			$borders[] = $size;
		}

		if (count($borders) > 1) {
			for ($i = 0; $i < count($borders); $i = $i + 2) {
				$containerWidth = ($borders[$i+1] - $borders[$i]) / $size * 100;

				if ($i == 0) {
					$marginLeft = $borders[$i] / $size * 100;
				} else {
					$marginLeft = ($borders[$i] - $borders[$i - 1]) / $size * 100;
				}

				$html .= "<div
					class=\"grid-container\"
					style=\"
						width: {$containerWidth}%;
						margin-left: {$marginLeft}%;
					\"
				>";

				$findIn = array();
				foreach ($lineArray as $grid) {
					if (
						($grid->left >= $borders[$i])
						&& (
							empty($borders[$i+1])
							|| (($grid->left + $grid->width) <= $borders[$i+1])
						) 
					) {
						$findIn[] = $grid->id;
					}
				}

				if ($findIn) {
					$criteria = new CDbCriteria();
					$criteria->addInCondition("id", $findIn);
					$criteria->order = "top";
					$gridArray = $this->findAll($criteria);
					if ($gridArray) {
						$top = 0;
						foreach ($gridArray as $grid) {
							$blockWidth = $grid->width / ($borders[$i+1] - $borders[$i]) * 100;
							$marginLeft = ($grid->left - $borders[$i]) * 100 / ($borders[$i+1] - $borders[$i]);
							if ($top < $grid->top) {
								$top = $grid->top;
								$html .= "<div class=\"clear\"></div>";
							}
							$html .= "<div class=\"block-container\" style=\"
								width: {$blockWidth}%;
								margin-right: -{$blockWidth}%;
								margin-left: {$marginLeft}%
								\">{$grid->block->getHtml()}</div>";
						}
						if (!$top) {
							$html .= "<div class=\"clear\"></div>";
						}
					}
				}

				$html .= "</div>";
			}
		}

		return "<div>{$html}<div class=\"clear\"></div></div>";
	}
}
