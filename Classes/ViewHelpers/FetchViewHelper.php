<?php

class Tx_TerFrontend_ViewHelpers_FetchViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

	/**
	 * @var Tx_TerFrontend_QueryBuilder
	 */
	protected $queryBuilder;

	/**
	 * @var Tx_TerFrontend_QueryBuilder $queryBuilder
	 */
	public function injectQueryBuilder(Tx_TerFrontend_QueryBuilder $queryBuilder) {
		$this->queryBuilder = $queryBuilder;
	}

	/**
	 * @param string $p
	 */
	public function render($p) {
		$element = $this->renderChildren();
		if ($element['type'] !== 'uri') {
			throw new \Exception('TODO: tf:fetch only works on URIs');
		}
		$uri = $element['value'];

		$query = $this->queryBuilder->createQuery();
		$query->setBody(sprintf('SELECT ?o WHERE { <%s> %s ?o. } ', $uri, $p));

		$firstResult = $query->execute()->getFirst();
		return $firstResult['o']['value'];
	}
}
?>