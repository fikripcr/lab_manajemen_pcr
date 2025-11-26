<?php

namespace App\Services\Sys;

use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Storage;

class DocxService
{
    /**
     * Replace variables in a DOCX template with provided data
     *
     * @param string $templatePath Path to the DOCX template file
     * @param array|string $variables Associative array of variables to replace or JSON string
     * @param string|null $outputPath Optional path to save the modified document
     * @return string Path to the modified document
     */
    public function replaceVariablesInDocx($templatePath, $variables, $outputPath = null)
    {
        try {
            // Create a temporary file if no output path is provided
            if (!$outputPath) {
                $outputPath = storage_path('app/temp/' . uniqid() . '.docx');
                // Ensure temp directory exists
                $outputDir = dirname($outputPath);
                if (!file_exists($outputDir)) {
                    mkdir($outputDir, 0755, true);
                }
            }

            // Check if template file exists
            if (!file_exists($templatePath)) {
                throw new \Exception("Template file does not exist: {$templatePath}");
            }

            // Copy the original template to the output path first
            $copyResult = copy($templatePath, $outputPath);
            if (!$copyResult) {
                throw new \Exception("Failed to copy template file to output location: {$outputPath}");
            }

            // Handle both array and JSON string inputs for variables
            if (is_string($variables)) {
                $variables = json_decode($variables, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new \Exception("Invalid JSON format for variables: " . json_last_error_msg());
                }
            }

            // Load the template using PhpWord
            $templateProcessor = new TemplateProcessor($outputPath);

            // Prepare variables for replacement
            $replacements = [];
            foreach ($variables as $key => $value) {
                // Format the key as expected by PhpWord (surrounded by ${})
                $replacements[$key] = $value;
            }

            // Replace all variables in one go
            $templateProcessor->setVariables($replacements);

            // Save the modified document
            $templateProcessor->save();

            return $outputPath;
        } catch (\Exception $e) {
            throw new \Exception("Error processing DOCX template: " . $e->getMessage());
        }
    }

    /**
     * Replace variables in DOCX and return as a download response
     *
     * @param string $templatePath Path to the DOCX template file
     * @param array $variables Associative array of variables to replace
     * @param string $fileName Name for the output file
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function replaceVariablesAndDownload($templatePath, $variables, $fileName = 'document.docx')
    {
        $outputPath = $this->replaceVariablesInDocx($templatePath, $variables);
        
        return response()->download($outputPath, $fileName)->deleteFileAfterSend(true);
    }

    /**
     * Replace variables in DOCX and store in a specific location
     *
     * @param string $templatePath Path to the DOCX template file
     * @param array $variables Associative array of variables to replace
     * @param string $storagePath Path in the storage to save the file
     * @return string Path where the file was saved
     */
    public function replaceVariablesAndStore($templatePath, $variables, $storagePath)
    {
        $outputPath = $this->replaceVariablesInDocx($templatePath, $variables);
        
        // Move the file to the desired storage location
        $finalPath = storage_path("app/{$storagePath}");
        $finalDir = dirname($finalPath);
        
        if (!file_exists($finalDir)) {
            mkdir($finalDir, 0755, true);
        }
        
        rename($outputPath, $finalPath);
        
        return $storagePath;
    }

    /**
     * Replace variables in DOCX template with complex data (tables, blocks)
     *
     * @param string $templatePath Path to the DOCX template file
     * @param array $variables Associative array of variables to replace
     * @param array $tables Array of table data where key is block name and value is array of rows
     * @param array $blocks Array of block data to duplicate
     * @param string|null $outputPath Optional path to save the modified document
     * @return string Path to the modified document
     */
    public function replaceComplexVariablesInDocx($templatePath, $variables = [], $tables = [], $blocks = [], $outputPath = null)
    {
        try {
            // Create a temporary file if no output path is provided
            if (!$outputPath) {
                $outputPath = storage_path('app/temp/' . uniqid() . '.docx');
                // Ensure temp directory exists
                if (!file_exists(dirname($outputPath))) {
                    mkdir(dirname($outputPath), 0755, true);
                }
            }

            // Create a copy of the template to work with
            if (!file_exists($templatePath)) {
                throw new \Exception("Template file does not exist: {$templatePath}");
            }

            copy($templatePath, $outputPath);
            
            // Load the template using PhpWord
            $templateProcessor = new TemplateProcessor($outputPath);

            // Replace simple variables
            if (!empty($variables)) {
                $templateProcessor->setVariables($variables);
            }

            // Replace table data
            foreach ($tables as $tableName => $tableData) {
                if (is_array($tableData) && !empty($tableData)) {
                    $templateProcessor->cloneRow($tableName, count($tableData));
                    
                    foreach ($tableData as $index => $rowData) {
                        foreach ($rowData as $key => $value) {
                            $templateProcessor->setValue($key . '#' . ($index + 1), $value);
                        }
                    }
                }
            }

            // Replace block data
            foreach ($blocks as $blockName => $blockData) {
                if (is_array($blockData) && !empty($blockData)) {
                    $templateProcessor->cloneBlock($blockName, 0, true, $blockData);
                }
            }

            // Save the modified document
            $templateProcessor->save();

            return $outputPath;
        } catch (\Exception $e) {
            throw new \Exception("Error processing DOCX template: " . $e->getMessage());
        }
    }
}