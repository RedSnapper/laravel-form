<?php
/**
 * Part of form
 * User: ben ©2017 Red Snapper Ltd.
 * Date: 13/04/2017 10:29
 */

namespace App\Http\Formlets;

use App\Http\Controllers\CategoryController;
use App\Models\Role;
use RS\Form\Fields\Select;
use RS\Form\Formlet;

class TeamRolesFormlet extends Formlet {
	public $formletView = "team.role";
	/**
	 * @var CategoryController
	 */
	private $categoryController;

	public function __construct(CategoryController $categoryController) {
		$this->categoryController = $categoryController;
	}

	/**
	 * Prepare the form with fields
	 *
	 * @return void
	 */
	public function prepareForm() {
		$field = new Select('role[]', Role::options($this->categoryController->getIds("ROLES")));
		$this->add(
			$field->setMultiple(true)
				->setValue($this->model->userRoles->pluck('id')->all())
		);
	}
}