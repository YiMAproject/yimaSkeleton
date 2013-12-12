<?php
namespace Application\Db\TableGateway\Provider;

interface PrimaryKeyProviderInterface
{
	/**
	 * Barkhi az TableGateway Feature haa maanande Translation
	 * va yaa mavaarede digar ehtiaaj be daashtane primarykey baraaie table
	 * daarand,
	 * estefaade az metadata sor'at e ejraa raa kam mikonad baraaie sarfe jooii
	 * dar amaliaat e system az in interface estefaade mikonim
	 */
    public function getPrimaryKey();
}