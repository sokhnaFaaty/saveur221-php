<?php

declare(strict_types=1);

namespace App\Services;

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfService
{
    public function generate(string $template, array $data = [], ?string $filename = null): never
    {
        $html = $this->renderView($template, $data);

        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isRemoteEnabled', false);
        $options->set('isPhpEnabled', false);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $output = $dompdf->output();
        $name = $filename ?? $template . '.pdf';
        $name = preg_replace('/[^A-Za-z0-9._-]/', '-', $name) ?? 'document.pdf';

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $name . '"');
        header('Content-Length: ' . strlen($output));
        echo $output;
        exit;
    }

    private function renderView(string $template, array $data): string
    {
        if (!defined('VIEW_PATH')) {
            define('VIEW_PATH', dirname(__DIR__, 2) . '/views');
        }

        extract($data, EXTR_SKIP);

        ob_start();
        include VIEW_PATH . '/' . $template . '.php';
        return (string) ob_get_clean();
    }
}