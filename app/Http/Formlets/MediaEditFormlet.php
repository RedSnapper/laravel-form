<?php

namespace App\Http\Formlets;

use App\Http\Controllers\TreeController;
use App\Http\Formlets\Helpers\CategoryHelper;
use App\Http\Formlets\Helpers\Rating;
use App\Models\Category;
use App\Models\Media;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use RS\Form\Fields\AbstractField;
use RS\Form\Fields\Input;
use RS\Form\Fields\Select;
use RS\Form\Fields\TextArea;
use RS\Form\Formlet;

class MediaEditFormlet extends Formlet {
	public $formView = "media.edit";
	/**
	 * @var CategoryHelper
	 */
	private $categoryHelper;

	public function __construct(Media $media, CategoryHelper $categoryHelper) {
		$this->setModel($media);
		$this->categoryHelper = $categoryHelper;
	}

	/**
	 * Prepare the form with fields
	 *
	 * @return void
	 */
	public function prepareForm() : void {
		$field = new Input('text', 'name');
		$this->add($field->setLabel("Name")->setRequired(true));

		$this->categoryHelper->field($this,'MEDIA');
		$field = new Select('team_id', auth()->user()->teams()->pluck('name', 'id'));
		$this->add(
			$field->setLabel("Team")
				->setPlaceholder("Please select a team")
		);

		$field = new Input('file', 'media');
		$this->add($field->setLabel("Media"));

		$field = new Input('text', 'filename');
		$this->add($field->setLabel("Filename"));

		$field = new Rating('rating');
		$field->setLabel("Rating");
		$this->add($field);

		$field = new TextArea('license_ta');
		$this->add($field->setLabel("License Information"));

		//get my tags.
		$tags = $this->model->tags()->pluck('id')->all();
		//get all tags.. These are NOT subscribers..
		$base = Category::reference("Media","TAGS")->first();
		$groups = Tag::optGroup($base);
		foreach ($groups as $name => $group) {
			$field = new Select('tag[]',$group);
			$field->setMultiple(true)->setValueType(AbstractField::TYPE_ARRAY)->setLabel($name)->setValue($tags);
			$this->add($field);
		}
	}

	public function edit(): Model {
		$tags = $this->fields('tag');
		$this->model->tags()->sync($tags);
		return $this->model->saveMedia($this->fields(), $this->request->file('media'));
	}

	protected function undo(): bool {
		$this->model->undo();
		return false; //don't process as a form.
	}

	protected function redo(): bool {
		$this->model->redo();
		return false; //don't process as a form.
	}

	public function rules(): array {
		$key = $this->model->getKey();

		$rules = [
			'filename'    => 'required|max:255',
			'name'        => ['required', 'max:255', Rule::unique('media')->ignore($key)],
			'category_id' => 'required|category',
			'team_id'     => 'required|integer',
		];

		return $rules;
	}
}
