<?php

namespace App\Http\Controllers;

use App\Models\AssistanceCase;
use App\Models\Citizen;
use App\Models\Visa;

class PrintController extends Controller
{
    public function citizen(Citizen $citizen)
    {
        $citizen->load(['visas', 'assistanceCases']);

        return view('print.citizen', compact('citizen'));
    }

    public function visa(Visa $visa)
    {
        $visa->load('citizen');

        return view('print.visa', compact('visa'));
    }

    public function case(AssistanceCase $assistance)
    {
        $assistance->load('citizen');

        return view('print.case', ['case' => $assistance]);
    }
}
