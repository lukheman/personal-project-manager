<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class InvoiceController extends Controller
{
    /**
     * Download invoice PDF for a project.
     */
    public function download(Project $project): Response
    {
        $project->load(['client', 'features.category', 'payments']);

        $pdf = Pdf::loadView('pdf.invoice', [
            'project' => $project,
            'invoiceNumber' => 'INV-' . str_pad($project->id, 5, '0', STR_PAD_LEFT),
            'invoiceDate' => now()->format('d F Y'),
        ]);

        $filename = 'invoice-' . $project->id . '-' . now()->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Stream invoice PDF for preview.
     */
    public function preview(Project $project): Response
    {
        $project->load(['client', 'features.category', 'payments']);

        $pdf = Pdf::loadView('pdf.invoice', [
            'project' => $project,
            'invoiceNumber' => 'INV-' . str_pad($project->id, 5, '0', STR_PAD_LEFT),
            'invoiceDate' => now()->format('d F Y'),
        ]);

        return $pdf->stream('invoice-' . $project->id . '.pdf');
    }
}
