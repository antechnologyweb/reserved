<?php


namespace App\Domain\Contracts;


class OrganizationTablesContract extends MainContract
{
    const TABLE =   'organization_tables';

    const FILLABLE  =   [
        self::ORGANIZATION_ID,
        self::KEY,
        self::NAME,
        self::LIMIT,
        self::STATUS,
        self::PARENT_ID,
        self::LFT,
        self::RGT,
        self::DEPTH
    ];
}
