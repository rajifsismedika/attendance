<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Carbon\Carbon;
use Inertia\Inertia;

class OdooReaderController extends Controller
{
    public function index() {
        $file_path = resource_path('files/odoo3.xlsx');

        $reader = ReaderEntityFactory::createReaderFromFile($file_path);
        $reader->open($file_path);

        $employees = [
            'Hanif Aulia Sabri',
            'Ivan Julian',
            'M. Fajar',
            'Pajri Al Zukri',
            'Rajif Afif',
            'Tubagus Fajri Mulyana',
        ];


        $headers = [
            'name'
        ];

        $mapProjectName = [
            'SOONE (Dokotel - Telekonsultasi)' => 'Docotel',
            'HALO AWAL BROS' => 'HAB',
            'KAVACARE' => 'Kavacare'
        ];

        $tanggals = [];
        $data = [];

        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $index => $row) {
                // Tanggal
                if ($index == 3) {
                    foreach ($row->getCells() as $cell_index => $cell) {
                        if ($cell_index > 0) {
                            $val = $cell->getValue();
                            if (!empty($val)) {
                                $currentTanggal = $val;
                                $currentTanggal = Carbon::parse($currentTanggal)->format('d/m/Y');
                            }
                            $tanggals[] = $currentTanggal;

                            $data[$cell_index]['tanggal'] = $currentTanggal;
                        }
                    }
                }

                // Task Name
                if ($index == 4) {
                    foreach ($row->getCells() as $cell_index => $cell) {
                        if ($cell_index > 0) {
                            $val = $cell->getValue();
                            if (!empty($val)) {
                                $currentTask = $val;
                            }

                            $data[$cell_index]['task'] = $currentTask;
                        }
                    }
                }

                // Project Name
                if ($index == 5) {
                    foreach ($row->getCells() as $cell_index => $cell) {
                        if ($cell_index > 0) {
                            $val = $cell->getValue();
                            if (!empty($val)) {
                                $currentProject = $val;
                                $currentProject = isset($mapProjectName[$currentProject]) ? $mapProjectName[$currentProject] : $currentProject;
                            }

                            $data[$cell_index]['project'] = $currentProject;
                        }
                    }
                }

                // Start Reading Data
                if ($index >= 7) {




                    // $columns = [];
                    foreach ($row->getCells() as $cell_index => $cell) {
                        if ($cell_index == 0) {
                            $currentEmployee = $cell->getValue();
                            $currentEmployee = str_replace('     ', '', $currentEmployee);
                        }

                        $val = $cell->getValue();

                        if (empty($val)) {
                            continue;
                        } else {
                            $data[$cell_index]['employee'] = $currentEmployee;
                            $data[$cell_index]['time'] = $val;
                        }
                    }
                }
            }

            return Inertia::render('Odoo', ['data' => array_values($data)]);
        }
    }
}
