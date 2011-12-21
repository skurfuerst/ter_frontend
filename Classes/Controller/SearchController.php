<?php

class Tx_TerFrontend_Controller_SearchController extends Tx_Extbase_MVC_Controller_ActionController {

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
	public static $QUERYFRAGMENT_OVERVIEW = '
		?package ?packageName ?updatedAt
		WHERE {
			?package rdf:type t3package:Package.
			?package t3package:name ?packageName.
			?package t3package:updatedAt ?updatedAt.
	';

	public static $QUERY_LAST_UPDATED = '
		SELECT DISTINCT {{QUERYFRAGMENT_OVERVIEW}}
		}
		ORDER BY DESC(?updatedAt)
	';

	public static $QUERY_SEARCH = '
		SELECT DISTINCT ?score {{QUERYFRAGMENT_OVERVIEW}}
			?package ?p ?o.
			?o bd:search "{queryString}*".
			?o bd:rank ?rank.
			?o bd:relevance ?score.
		}
		ORDER BY DESC(?rank)
	';

	/**
	 * @param string $queryString
	 */
	public function indexAction($queryString = NULL) {
		$query = $this->queryBuilder->createQuery();

		if ($queryString === NULL) {
			$query->setBody($this->getQueryString('QUERY_LAST_UPDATED'));
		} else {
			$query->setBody(str_replace('{queryString}', $queryString, $this->getQueryString('QUERY_SEARCH')));
		}
		$this->view->assign('results', $query->execute());
	}
	public function getQueryString($name) {
		$that = $this;
		$currentQuery = self::$$name;
		while (preg_match('/{{.*?}}/', $currentQuery)) {
			$currentQuery = preg_replace_callback('/{{(.*?)}}/', function($matches) use ($that) {
				$subqueryName = $matches[1];
				return $that::$$subqueryName;
			}, $currentQuery);
		}
		return $currentQuery;
	}
}
?>