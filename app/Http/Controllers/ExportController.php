<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{

    public function exportCSV (Request $request)  {
        $user = Auth::user();
        
        if($user->role != 'admin'){
            abort(403,'Unauthorized');
        }
       
        $appointments = Appointment::with('user')->whereLike('title',"%$request->search%")->get();
        
        
         $headers = [
            'Content-Disposition' => 'attachment; filename="appointments-' . now()->format('Y-m-d') . '.csv"',
            'Content-Type' => 'text/csv; charset=UTF-8',
        ];

        return new StreamedResponse(function () use ($appointments) {
            $handle = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($handle, [
                'ID',
                'User',
                'Email',
                'Title',
                'Description',
                'Start Time',
                'End Time',
                'Location',
                'Status',
                'Created At',
            ]);

            // Add data rows
            foreach ($appointments as $appointment) {
                fputcsv($handle, [
                    $appointment->id,
                    $appointment->user->name,
                    $appointment->user->email,
                    $appointment->title,
                    $appointment->description,
                    $appointment->start_time->format('Y-m-d H:i:s'),
                    $appointment->end_time->format('Y-m-d H:i:s'),
                    $appointment->location,
                    $appointment->status,
                    $appointment->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }
}
