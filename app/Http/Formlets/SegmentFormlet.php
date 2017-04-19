<?php
/**
 * Part of form
 * User: ben ©2017 Red Snapper Ltd.
 * Date: 23/03/2017 11:52
 */

namespace App\Http\Formlets;

use App\Http\Controllers\CategoryController;
use RS\Form\Formlet;
use RS\Form\Fields\Input;
use RS\Form\Fields\Select;
use RS\Form\Fields\TextArea;
use App\Models\Segment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Validation\Rule;

class SegmentFormlet extends Formlet {
	public $formView = "segment.form";
	/**
	 * @var CategoryController
	 */
	private $categoryController;

	public function __construct(Segment $segment,CategoryController $categoryController) {

		$this->setModel($segment);
		$this->categoryController = $categoryController;
	}

	public function prepareForm() {
		$this->add((new Input('text', 'name'))->setLabel('Name')->setRequired());
		$this->add((new Input('text', 'syntax'))->setLabel('Syntax'));
		$this->add((new TextArea('docs'))->setLabel('Docs')->setRows(3));

		$field = new Select('category_id',$this->categoryController->options('SEGMENTS'));
		$this->add(
		  $field->setLabel("Category")
			->setPlaceholder("Please select a category")
		  	->setDefault($this->getData('category'))
		);

		$this->addSubscribers('layouts', SegmentLayoutFormlet::class, $this->model->layouts());

	}

	/**
	 * Add subscribers to this formlet
	 *
	 * @param string        $name
	 * @param string        $class
	 * @param BelongsToMany $builder
	 */
	public function addSubscribers(string $name, string $class, BelongsToMany $builder, $items = null) {

		$items = $builder->getRelated()->all();
		$models = $builder->get();

		foreach ($items as $item) {
			$formlet = app()->make($class);
			$formlet->with('segment', $this->model);
			$model = $this->getModelByKey($item->getKey(), $models);
			$this->addSubscriberFormlet($formlet, $name, $item, $model);
		}
	}

	public function rules(): array {
		$key = $this->model->getKey();
		return [
		  'name' => ['required', 'max:255', Rule::unique('segments')->ignore($key)],
		  'category_id' => 'required'
		];
	}

	public function edit(): Model {

		$segment = parent::edit();

		$segment->layouts()->sync($this->getSubscriberFields('layouts'));

		return $segment;
	}

	public function persist(): Model {
		return $this->edit();
	}

}