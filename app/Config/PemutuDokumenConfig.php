<?php

namespace App\Config;

/**
 * Configuration class for Pemutu Document Types
 *
 * Centralized configuration for all document types in SPMI system.
 * This replaces scattered if-else logic and helper functions.
 *
 * Usage:
 *   $config = PemutuDokumenConfig::for('standar');
 *   $config->hasPoin();           // true
 *   $config->canGenerateIndikator(); // true
 *   $config->getDefaultPoin();    // ['Rasional', 'Ruang Lingkup', ...]
 */
class PemutuDokumenConfig
{
    /**
     * Complete configuration for all document types
     */
    private static array $config = [
        // ───────────────────────────────────────────────────────
        // POLICY CHAIN (Kebijakan → Standar → Manual → Formulir)
        // ───────────────────────────────────────────────────────

        'kebijakan' => [
            'label' => 'Kebijakan',
            'label_full' => 'Kebijakan Mutu',
            'category' => 'kebijakan', // Tab category
            'tree_based' => true,
            'has_poin' => true,
            'has_default_poin' => true,
            'default_poin' => [
                'Rasional / Tujuan',
                'Ruang Lingkup',
                'Istilah / Definisi',
                'Pernyataan Kebijakan',
                'Pernyataan Isi Kebijakan',
            ],
            'can_generate_indikator' => false,
            'mappable_to' => ['standar'],
            'parent_types' => [], // No parent (top-level)
            'child_types' => ['standar'],
            'show_approval' => true,
            'icon' => 'ti ti-file-text',
        ],

        'standar' => [
            'label' => 'Standar',
            'label_full' => 'Standar Mutu',
            'category' => 'standar',
            'tree_based' => true,
            'has_poin' => true,
            'has_default_poin' => true,
            'default_poin' => [
                'Rasional / Tujuan',
                'Ruang Lingkup',
                'Istilah / Definisi',
                'Pernyataan Kebijakan / Deskripsi Standar',
                'Pernyataan Isi Standar / Indikator Capaian', // ← Generates Indikator
            ],
            'can_generate_indikator' => true,
            'indikator_poin_index' => 4, // 0-based index (5th poin)
            'mappable_to' => ['kebijakan'],
            'parent_types' => ['kebijakan'],
            'child_types' => ['manual_prosedur'],
            'show_approval' => true,
            'icon' => 'ti ti-book',
        ],

        'manual_prosedur' => [
            'label' => 'Manual Prosedur',
            'label_full' => 'Manual Prosedur / SOP',
            'category' => 'standar', // Same tab as standar
            'tree_based' => true,
            'has_poin' => true,
            'has_default_poin' => false,
            'default_poin' => [],
            'can_generate_indikator' => false,
            'mappable_to' => ['standar'],
            'parent_types' => ['standar'],
            'child_types' => ['formulir'],
            'show_approval' => true,
            'icon' => 'ti ti-file-description',
        ],

        'formulir' => [
            'label' => 'Formulir',
            'label_full' => 'Formulir / Template',
            'category' => 'standar',
            'tree_based' => true,
            'has_poin' => true,
            'has_default_poin' => false,
            'default_poin' => [],
            'can_generate_indikator' => false,
            'mappable_to' => ['standar', 'manual_prosedur'],
            'parent_types' => ['standar', 'manual_prosedur'],
            'child_types' => [],
            'show_approval' => true,
            'icon' => 'ti ti-forms',
        ],

        // ───────────────────────────────────────────────────────
        // PLANNING CHAIN (Visi → Misi → RJP → Renstra → Renop)
        // ───────────────────────────────────────────────────────

        'visi' => [
            'label' => 'Visi',
            'label_full' => 'Visi Universitas',
            'category' => 'kebijakan',
            'tree_based' => false, // Single document view
            'has_poin' => true,
            'has_default_poin' => false,
            'default_poin' => [],
            'can_generate_indikator' => false,
            'mappable_to' => [], // Top-level, no mapping needed
            'parent_types' => [],
            'child_types' => [],
            'show_approval' => true,
            'icon' => 'ti ti-eye',
        ],

        'misi' => [
            'label' => 'Misi',
            'label_full' => 'Misi Universitas',
            'category' => 'kebijakan',
            'tree_based' => false,
            'has_poin' => true,
            'has_default_poin' => false,
            'default_poin' => [],
            'can_generate_indikator' => false,
            'mappable_to' => ['visi'],
            'parent_types' => [],
            'child_types' => [],
            'show_approval' => true,
            'icon' => 'ti ti-bullseye',
        ],

        'rjp' => [
            'label' => 'RJP',
            'label_full' => 'Rencana Jangka Panjang',
            'category' => 'kebijakan',
            'tree_based' => false,
            'has_poin' => true,
            'has_default_poin' => false,
            'default_poin' => [],
            'can_generate_indikator' => false,
            'mappable_to' => ['misi'],
            'parent_types' => [],
            'child_types' => [],
            'show_approval' => true,
            'icon' => 'ti ti-calendar',
        ],

        'renstra' => [
            'label' => 'Renstra',
            'label_full' => 'Rencana Strategis',
            'category' => 'kebijakan',
            'tree_based' => false,
            'has_poin' => true,
            'has_default_poin' => false,
            'default_poin' => [],
            'can_generate_indikator' => false,
            'mappable_to' => ['rjp'],
            'parent_types' => [],
            'child_types' => [],
            'show_approval' => true,
            'icon' => 'ti ti-chart-bar',
        ],

        'renop' => [
            'label' => 'Renop',
            'label_full' => 'Rencana Operasional',
            'category' => 'kebijakan',
            'tree_based' => false,
            'has_poin' => true,
            'has_default_poin' => false,
            'default_poin' => [],
            'can_generate_indikator' => true,
            'indikator_via_label' => true, // Uses label 'renop' instead of poin
            'mappable_to' => ['renstra'],
            'parent_types' => [],
            'child_types' => [],
            'show_approval' => true,
            'icon' => 'ti ti-calendar-event',
            'tabs' => ['overview', 'indikator-renop', 'approval'],
        ],
    ];

    /**
     * Get configuration for a specific document type
     */
    public static function for(string $jenis): self
    {
        $jenis = strtolower(trim($jenis));

        if (! isset(self::$config[$jenis])) {
            // Return default config for unknown types
            return new self($jenis, self::$config['standar']);
        }

        return new self($jenis, self::$config[$jenis]);
    }

    /**
     * Get all document types
     */
    public static function all(): array
    {
        return array_keys(self::$config);
    }

    /**
     * Get document types by category
     */
    public static function byCategory(string $category): array
    {
        return array_filter(
            self::$config,
            fn ($config) => $config['category'] === $category
        );
    }

    /**
     * Get all types that use tree-based view
     */
    public static function treeBasedTypes(): array
    {
        return array_keys(array_filter(
            self::$config,
            fn ($config) => $config['tree_based'] === true
        ));
    }

    /**
     * Get all types that can generate indicators
     */
    public static function indikatorGeneratorTypes(): array
    {
        return array_keys(array_filter(
            self::$config,
            fn ($config) => $config['can_generate_indikator'] === true
        ));
    }

    // ─────────────────────────────────────────────────────────
    // INSTANCE METHODS - Fluent API for configuration access
    // ─────────────────────────────────────────────────────────

    public function __construct(
        private string $jenis,
        private array $configData
    ) {}

    public function jenis(): string
    {
        return $this->jenis;
    }

    public function label(): string
    {
        return $this->configData['label'];
    }

    public function labelFull(): string
    {
        return $this->configData['label_full'];
    }

    public function category(): string
    {
        return $this->configData['category'];
    }

    public function isTreeBased(): bool
    {
        return $this->configData['tree_based'];
    }

    public function hasPoin(): bool
    {
        return $this->configData['has_poin'];
    }

    public function hasDefaultPoin(): bool
    {
        return $this->configData['has_default_poin'];
    }

    public function getDefaultPoin(): array
    {
        return $this->configData['default_poin'];
    }

    public function canGenerateIndikator(): bool
    {
        return $this->configData['can_generate_indikator'];
    }

    public function getIndikatorPoinIndex(): ?int
    {
        return $this->configData['indikator_poin_index'] ?? null;
    }

    public function isIndikatorViaLabel(): bool
    {
        return $this->configData['indikator_via_label'] ?? false;
    }

    public function mappableTo(): array
    {
        return $this->configData['mappable_to'];
    }

    public function canMapTo(string $targetJenis): bool
    {
        return in_array($targetJenis, $this->configData['mappable_to']);
    }

    public function parentTypes(): array
    {
        return $this->configData['parent_types'];
    }

    public function canHaveParent(string $parentJenis): bool
    {
        return empty($this->configData['parent_types'])
        || in_array($parentJenis, $this->configData['parent_types']);
    }

    public function childTypes(): array
    {
        return $this->configData['child_types'];
    }

    public function showApproval(): bool
    {
        return $this->configData['show_approval'];
    }

    public function icon(): string
    {
        return $this->configData['icon'];
    }

    public function tabs(): array
    {
        return $this->configData['tabs'] ?? ['overview', 'approval'];
    }

    /**
     * Check if a specific tab should be shown
     */
    public function hasTab(string $tabName): bool
    {
        $tabs = $this->tabs();

        return in_array($tabName, $tabs);
    }

    /**
     * Get configuration as array (for backward compatibility)
     */
    public function toArray(): array
    {
        return $this->configData;
    }
}
