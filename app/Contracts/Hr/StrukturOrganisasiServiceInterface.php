<?php

namespace App\Contracts\Hr;

interface StrukturOrganisasiServiceInterface
{
    /**
     * Get flattened hierarchical list for dropdowns
     */
    public function getHierarchicalList($parentId = null, $prefix = '', $excludeId = null, array $types = []);
}
