<?php
/**
 * Created by PhpStorm.
 * User: param
 * Date: 27/03/2017
 * Time: 16:02
 */

namespace App\Http\Fields;

use Illuminate\Support\Collection;

class Choice extends AbstractField {
	/**
	 * @var Collection
	 */
	protected $options;

	public function __construct(string $name, $list = [], $selected = null) {
		$this->name = $name;
		$this->default = $selected;
		$this->attributes = collect([]);
		$this->options = $this->setOptions($list);
	}

	//TODO:: This needs serious tidying up.
	//TODO:: Sorry, param. couldn't work it out.
	protected function setOptions($list): Collection {
		if (is_a($list, Collection::class)) {
			$orig = $list;
			$list = [];
			foreach($orig as $thing) {
				$list[$thing->id] = $thing->name;
			}
		}

		return collect($list)->map(function ($item, $key) {

			if (!is_array($item)) {
				return $this->option($key, $item);
			}
			return $this->optgroup($key, $item);
		})->values();
	}

	protected function option($value, $display, bool $disabled = false): \stdClass {
		$option = new \stdClass();
		$option->display = $display;
		$option->value = $value;
		$option->disabled = $disabled;
		return $option;
	}

	protected function optgroup($label, $options = []): \stdClass {

		$group = new \stdClass();
		$group->label = $label;

		$group->options = $this->setOptions($options);

		return $group;
	}

	public function getData() {
		$data = parent::getData();

		$data['options'] = $this->options;

		return $data;
	}
}