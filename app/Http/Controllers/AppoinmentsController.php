<?php

namespace App\Http\Controllers;

use App\Appoinments;
use Illuminate\Http\Request;

class AppoinmentsController extends Controller
{
    public function index()
    {
        return response()->json(['status'=>'ok','data'=>Appoinments::all()], 200);
    }

	public function store(Request $request)
	{
        $request->validate([
            'date' => 'required|string|unique:appoinments',
            'email' => 'required|string'
        ]);

        $request->date = $request->input('date');
        $request->email = $request->input('email');

		$nuevoAppoinment=Appoinments::create([
            'date' => $request->date,
            'email' => $request->email
        ]);

        return response()->json([
            'message' => 'Cita creada exitosamente!'
        ], 201);
	}
    public function show($date)
    {
		$appoinment=Appoinments::whereDate('date',$date)->get();
		if (!$appoinment)
		{
            return response()->json(['errors'=>array(['code'=>404,'message'=>'Ya existe una cita.'])],404);
        }
		return response()->json(['status'=>'ok','data'=>$appoinment]);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $appoinment=Appoinments::find('id',$id);
        if (!$appoinment)
        {
            return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra una cita con ese código.'])],404);
        }
        $date=$request->input('date');
        $email=$request->input('email');

        if ($request->method() === 'PATCH')
        {
            $bandera = false;
            if ($date)
            {
                $appoinment->date = $date;
                $bandera=true;
            }
            if ($email)
            {
                $appoinment->email = $email;
                $bandera=true;
            }
            if ($bandera)
            {
                $appoinment->save();
                return response()->json(['status'=>'ok','data'=>$appoinment], 200);
            }
            else
            {
                return response()->json(['errors'=>array(['code'=>304,'message'=>'No se ha modificado ningún dato de la cita.'])],304);
            }
        }

        if (!$date || !$email)
        {
            return response()->json(['errors'=>array(['code'=>422,'message'=>'Faltan valores para completar el procesamiento.'])],422);
        }

        $appoinment->date = $date;
        $appoinment->email = $email;
        $appoinment->save();
        return response()->json(['status'=>'ok','data'=>$appoinment], 200);
    }

    public function destroy($id)
    {
        $appoinment=Appoinments::find('id',$id);

        if (!$appoinment)
        {
            return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra una cita con ese código.'])],404);
        }

        $appoinment->delete();
        return response()->json(['code'=>204,'message'=>'Se ha eliminado la cita correctamente.'],204);
    }
}
