<?php

namespace App\Http\Controllers;
use App\Models\Cars;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class CarsController extends Controller
{

    private $model;
    
    public function __construct(Cars $cars)
    {
        $this->model = $cars;
    }

    public function getAll()
    {
        $cars = $this->model->all();
        
        try{
            if (count($cars) > 0)
            {
                return response()->json($cars, Response::HTTP_OK);
            }
            else
            {
                return response()->json([], Response::HTTP_OK);
            }
        }
        catch (QueryException $exception)
        {
            return response()->json(['error'=> 'erro de conexao com o banco de dados'], Response:: HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function get($id)
    {
        $car = $this->model->find($id);
        
        try
        {
            if($car)
            {
                return response()->json($car, Response::HTTP_OK);
            }
            else
            {
                return response()->json(null, Response::HTTP_OK);
            }
        }
        catch (QueryException $exception)
        {
            return response()->json(['error'=> 'erro de conexao com o banco de dados'], Response:: HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(Request $request)
    {
        
        $validator = Validator::Make(
            $request->all(),
            [
                'name'          => 'required | max:80',
                'description'   => 'required',
                'model'         => 'required | max:10 | min:2',
                'date'          => 'required | date_format: "Y-m-d"'
            ]
        );

        if ($validator->fails())
        {
            return response()->json( $validator->errors(), Response::HTTP_BAD_REQUEST);
        }
        else
        {

            try
            {
                $car = $this->model->create($request->all());
                return response()->json($car, Response::HTTP_CREATED);         
            }
            catch (QueryException $exception)
            {
                return response()->json(['error'=> 'erro de conexao com o banco de dados'], Response:: HTTP_INTERNAL_SERVER_ERROR);    
            }
        }
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name'          => 'required | max:80',
                'description'   => 'required',
                'model'         => 'required | max:10 | min:2',
                'date'          => 'required | date_format: "Y-m-d"'
            ]
        );
        if($validator->fails())
        {
            return response()->json( $validator->errors(), Response::HTTP_BAD_REQUEST );
        }
        else
        {

            try 
            {
                $car = $this->model->find($id)->update($request->all());
                return response()->json($car, Response::HTTP_OK);
            }
            catch (QueryException $exception)
            {
                return response()->json(['error'=> 'erro de conexao com o banco de dados'], Response:: HTTP_INTERNAL_SERVER_ERROR); 
            }
        }
    }
    public function destroy($id)
    {
        try
        {
            $car = $this->model->find($id)->delete();
            return response()->json(null, Response::HTTP_OK);
        } 
        catch (QueryException $exception)
        {
            return response()->json(['error'=> 'erro de conexao com o banco de dados'], Response:: HTTP_INTERNAL_SERVER_ERROR);   
        }
    }
}
