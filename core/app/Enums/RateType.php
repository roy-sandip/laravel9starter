<?php
namespace App\Enums;

enum RateType:string {
	case DOX = 'DOX';
	case SPX = 'SPX';

	public static function list()
	{
		return [
			self::DOX,
			self::SPX
		];
	}

	public function isDocument():bool 
	{
		return $this === static::DOX;
	}

	public function isSample():bool 
	{
		return $this === static::SPX;
	}

	public function getName():string
	{
		return match ($this)
		{
			self::SPX => 'Sample',
			self::DOX => 'Document',
		};
	}
}