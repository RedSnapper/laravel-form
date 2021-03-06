<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;

use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class ApiController extends Controller {

	/**
	 * @var TransformerAbstract|null
	 */
	protected $transformer;

	/**
	 * @var \League\Fractal\Manager
	 */
	protected $manager;

	/**
	 * @var int
	 */
	protected $statusCode = SymfonyResponse::HTTP_OK;

	/**
	 * @param Manager $manager
	 */
	public function setManager(Manager $manager) {
		$this->manager = $manager;
	}


	/**
	 * @return int
	 */
	public function getStatusCode(): int {
		return $this->statusCode;
	}

	/**
	 * @param int $statusCode
	 * @return $this
	 */
	public function setStatusCode(int $statusCode) {
		$this->statusCode = $statusCode;
		return $this;
	}

	/**
	 * @param string $message
	 * @return mixed
	 */
	public function respondNotFound($message = "Not Found") {
		return $this->setStatusCode(SymfonyResponse::HTTP_NOT_FOUND)->respondWithError($message);
	}

	/**
	 * @param string $message
	 * @return mixed
	 */
	public function respondWithNoContent() {
		$this->setStatusCode(SymfonyResponse::HTTP_NO_CONTENT);
		return response()->make('', $this->getStatusCode());
	}

	/**
	 * @param string $message
	 * @return mixed
	 */
	public function respondForbidden($message = "Forbidden") {
		return $this->setStatusCode(SymfonyResponse::HTTP_FORBIDDEN)->respondWithError($message);
	}

	public function respondDeleted($message = "Not Found") {
		return $this->setStatusCode(SymfonyResponse::HTTP_NOT_FOUND)->respondWithError($message);
	}

	/**
	 * @param       $data
	 * @param array $headers
	 * @return mixed
	 */
	public function respond($data, $headers = []) {
		return Response::json($data, $this->getStatusCode(), $headers);
	}

	/**
	 * @param $message
	 * @return mixed
	 */
	public function respondWithError($message) {
		return $this->respond([
		  'errors' => [
			'message' => $message
		  ]
		]);
	}

	/**
	 * @param                     $item
	 * @param TransformerAbstract $transformer
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function respondWithItem($item){

		$resource = new Item($item,$this->transformer);

		return $this->respondWithArray($this->createData($resource));
	}

	/**
	 * @param                     $item
	 * @param TransformerAbstract $transformer
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function respondWithCollection($item){

		$resource = new Collection($item,$this->transformer);

		return $this->respondWithArray($this->createData($resource));
	}

	/**
	 * @param                     $item
	 * @param TransformerAbstract $transformer
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function respondWithItemCreated($item) {
		$this->setStatusCode(SymfonyResponse::HTTP_CREATED);

		return $this->respondWithItem($item);
	}

	/**
	 * Returns a json response that contains the specified array,
	 * the current status code and optional headers.
	 *
	 * @param array $array
	 * @param array $headers
	 * @return \Illuminate\Http\JsonResponse
	 */
	protected function respondWithArray(array $array, array $headers = []) {
		return response()->json($array, $this->getStatusCode(), $headers);
	}

	private function createData($resource){
		return $this->manager->createData($resource)->toArray();
	}


}