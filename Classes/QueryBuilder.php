<?php

class Tx_TerFrontend_QueryBuilder implements t3lib_Singleton {

	/**
	 * @var Tx_Extbase_Object_ObjectManagerInterface
	 */
	protected $objectManager;

	/**
	 * @var Tx_Extbase_Object_ObjectManagerInterface $objectManager
	 */
	public function injectObjectManager(Tx_Extbase_Object_ObjectManagerInterface $objectManager) {
		$this->objectManager = $objectManager;
	}

	public function createQuery() {
		$query = $this->objectManager->create('Tx_Semantic_Domain_Model_Sparql_Query');

		$endpoint = $this->objectManager->create('Tx_Semantic_Domain_Model_Sparql_Endpoint');
		$endpoint->setName('TER Endpoint');
		$endpoint->setIri('http://localhost:81/sparql');
		$query->setEndpoint($endpoint);

		$query->addNamespace($this->createNamespace('rdf', 'http://www.w3.org/1999/02/22-rdf-syntax-ns#'));
		$query->addNamespace($this->createNamespace('t3package', 'http://typo3.org/ns/2011/Package#'));
		$query->addNamespace($this->createNamespace('bd', 'http://www.bigdata.com/rdf/search#'));
		return $query;
	}

	protected function createNamespace($prefix, $iri) {
		$namespace = $this->objectManager->create('Tx_Semantic_Domain_Model_Rdf_Namespace');
		$namespace->setPrefix($prefix);
		$namespace->setIri($iri);
		return $namespace;
	}

}